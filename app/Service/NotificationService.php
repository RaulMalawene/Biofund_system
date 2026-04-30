<?php

namespace App\Services;

use App\Enums\AlertLevelEnum;
use App\Models\NotificationLog;
use App\Models\Occurrence;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

/**
 * NotificationService
 *
 * Centraliza o envio de todas as notificações do sistema.
 * Cada notificação é registada na tabela `notifications_log`
 * independentemente de sucesso ou falha no envio.
 *
 * Eventos que disparam notificações:
 *
 *   1. notifyOccurrenceCreated()
 *      → Ao reclamante: confirmação com o tracking_code
 *      → A gestores da área/projecto: nova ocorrência recebida
 *      → Se alerta urgent/gbv: a todos os utilizadores configurados
 *
 *   2. notifyStatusChanged()
 *      → Ao reclamante: o estado da sua ocorrência mudou
 *
 *   3. notifyAssigned()
 *      → Ao gestor: foi-lhe atribuída uma ocorrência
 */
class NotificationService
{
    /**
     * Notifica sobre a criação de uma nova ocorrência.
     * Envia email de confirmação ao reclamante e alerta aos gestores responsáveis.
     *
     * @param  Occurrence  $occurrence  A ocorrência recém-criada
     */
    public function notifyOccurrenceCreated(Occurrence $occurrence): void
    {
        // 1. Notifica o reclamante (se tiver email)
        $recipientEmail = $occurrence->isExternal()
            ? $occurrence->complainant_email
            : $occurrence->submittedBy?->email;

        if ($recipientEmail) {
            $this->sendEmail(
                occurrence: $occurrence,
                recipientEmail: $recipientEmail,
                userId: null,
                eventType: 'occurrence_created',
                subject: "MDR — Reclamação registada | {$occurrence->tracking_code}",
                body: $this->buildCreatedMessage($occurrence)
            );
        }

        // 2. Notifica gestores de acordo com o nível de alerta
        $alertLevel = $occurrence->occurrenceType->alert_level;
        $this->notifyByAlertLevel($occurrence, $alertLevel);
    }

    /**
     * Notifica o reclamante quando o estado da sua ocorrência muda.
     *
     * @param  Occurrence  $occurrence  A ocorrência actualizada
     * @param  string      $comment     Comentário público da mudança de estado
     */
    public function notifyStatusChanged(Occurrence $occurrence, ?string $comment): void
    {
        $recipientEmail = $occurrence->isExternal()
            ? $occurrence->complainant_email
            : $occurrence->submittedBy?->email;

        if (!$recipientEmail) {
            return;
        }

        $this->sendEmail(
            occurrence: $occurrence,
            recipientEmail: $recipientEmail,
            userId: null,
            eventType: 'status_changed',
            subject: "MDR — Actualização da ocorrência {$occurrence->tracking_code}",
            body: $this->buildStatusChangedMessage($occurrence, $comment)
        );
    }

    /**
     * Notifica o gestor quando uma ocorrência lhe é atribuída.
     *
     * @param  Occurrence  $occurrence  A ocorrência atribuída
     * @param  User        $gestor      O gestor que recebe a atribuição
     */
    public function notifyAssigned(Occurrence $occurrence, User $gestor): void
    {
        $this->sendEmail(
            occurrence: $occurrence,
            recipientEmail: $gestor->email,
            userId: $gestor->id,
            eventType: 'occurrence_assigned',
            subject: "MDR — Nova ocorrência atribuída | {$occurrence->tracking_code}",
            body: $this->buildAssignedMessage($occurrence, $gestor)
        );
    }

    // ─── Métodos privados ────────────────────────────────────────

    /**
     * Determina quem notificar com base no nível de alerta da ocorrência.
     * - normal  → gestores da província e projecto da ocorrência
     * - urgent  → todos os utilizadores com receives_urgent_alerts = true
     * - gbv     → todos os utilizadores com receives_gbv_alerts = true
     *
     * @param  Occurrence      $occurrence
     * @param  AlertLevelEnum  $level
     */
    private function notifyByAlertLevel(Occurrence $occurrence, AlertLevelEnum $level): void
    {
        $column = $level->userAlertColumn();

        if ($column === null) {
            // Nível normal → notifica apenas os gestores da província/projecto
            $gestores = User::active()
                ->where('role', 'gestor')
                ->where(function ($q) use ($occurrence) {
                    $q->where('management_scope', 'national')
                      ->orWhere('province_id', $occurrence->province_id);
                })
                ->whereHas('projects', fn($q) =>
                    $q->where('project_id', $occurrence->project_id)
                )
                ->get();
        } else {
            // Nível urgent ou gbv → notifica todos os utilizadores configurados
            $gestores = User::active()->where($column, true)->get();
        }

        foreach ($gestores as $gestor) {
            $this->sendEmail(
                occurrence: $occurrence,
                recipientEmail: $gestor->email,
                userId: $gestor->id,
                eventType: $level === AlertLevelEnum::Gbv ? 'alert_gbv' : 'alert_urgent',
                subject: "[{$level->label()}] MDR — Nova ocorrência | {$occurrence->tracking_code}",
                body: $this->buildAlertMessage($occurrence, $level)
            );
        }
    }

    /**
     * Envia o email e regista o resultado na tabela notifications_log.
     * Em caso de falha, regista o erro mas não lança excepção
     * (para não interromper o fluxo principal da aplicação).
     */
    private function sendEmail(
        Occurrence $occurrence,
        string $recipientEmail,
        ?int $userId,
        string $eventType,
        string $subject,
        string $body
    ): void {
        // Cria o registo de notificação como pendente
        $log = NotificationLog::create([
            'occurrence_id'   => $occurrence->id,
            'user_id'         => $userId,
            'recipient_email' => $recipientEmail,
            'channel'         => 'email',
            'event_type'      => $eventType,
            'message'         => $body,
            'status'          => 'pending',
        ]);

        try {
            // Envia o email usando o mailer configurado no .env
            Mail::raw($body, function ($mail) use ($recipientEmail, $subject) {
                $mail->to($recipientEmail)->subject($subject);
            });

            // Actualiza o log como enviado
            $log->update(['status' => 'sent', 'sent_at' => now()]);

        } catch (\Throwable $e) {
            // Regista a falha sem interromper a aplicação
            $log->update([
                'status'        => 'failed',
                'error_message' => $e->getMessage(),
            ]);

            Log::error("MDR NotificationService: falha ao enviar email para {$recipientEmail}", [
                'occurrence_id' => $occurrence->id,
                'event_type'    => $eventType,
                'error'         => $e->getMessage(),
            ]);
        }
    }

    // ─── Templates de mensagem ───────────────────────────────────

    private function buildCreatedMessage(Occurrence $occurrence): string
    {
        return <<<TEXT
        Prezado(a) {$occurrence->complainant_name},

        A sua ocorrência foi registada com sucesso no sistema MDR — Mecanismo de Diálogo e Reclamações.

        Código de seguimento: {$occurrence->tracking_code}

        Guarde este código para acompanhar o estado da sua ocorrência em:
        {$_ENV['APP_URL']}/acompanhar?codigo={$occurrence->tracking_code}

        Assunto: {$occurrence->subject}
        Projecto: {$occurrence->project->name}
        Data de registo: {$occurrence->created_at->format('d/m/Y H:i')}
        Prazo de resolução: {$occurrence->due_date?->format('d/m/Y') ?? 'A definir'}

        Com os melhores cumprimentos,
        Equipa MDR — BIOFUND/FNDS
        TEXT;
    }

    private function buildStatusChangedMessage(Occurrence $occurrence, ?string $comment): string
    {
        $statusLabel = $occurrence->status->label();

        return <<<TEXT
        Prezado(a) {$occurrence->complainant_name},

        O estado da sua ocorrência foi actualizado.

        Código de seguimento: {$occurrence->tracking_code}
        Novo estado: {$statusLabel}
        {$comment ? "Resposta: {$comment}" : ''}

        Acompanhe a sua ocorrência em:
        {$_ENV['APP_URL']}/acompanhar?codigo={$occurrence->tracking_code}

        Com os melhores cumprimentos,
        Equipa MDR — BIOFUND/FNDS
        TEXT;
    }

    private function buildAssignedMessage(Occurrence $occurrence, User $gestor): string
    {
        return <<<TEXT
        Prezado(a) {$gestor->name},

        Foi-lhe atribuída uma nova ocorrência para tratamento.

        Código: {$occurrence->tracking_code}
        Assunto: {$occurrence->subject}
        Tipo: {$occurrence->occurrenceType->name}
        Projecto: {$occurrence->project->name}
        Província: {$occurrence->province->name}
        Prazo: {$occurrence->due_date?->format('d/m/Y') ?? 'A definir'}

        Aceda ao painel MDR para tratar esta ocorrência.

        Com os melhores cumprimentos,
        Sistema MDR — BIOFUND/FNDS
        TEXT;
    }

    private function buildAlertMessage(Occurrence $occurrence, AlertLevelEnum $level): string
    {
        return <<<TEXT
        ⚠️  ALERTA {$level->label()}

        Foi registada uma nova ocorrência que requer atenção imediata.

        Código: {$occurrence->tracking_code}
        Assunto: {$occurrence->subject}
        Projecto: {$occurrence->project->name}
        Província: {$occurrence->province->name}
        Prazo: {$occurrence->due_date?->format('d/m/Y') ?? 'A definir'}

        Aceda ao painel MDR para tratar esta ocorrência com urgência.

        Sistema MDR — BIOFUND/FNDS
        TEXT;
    }
}