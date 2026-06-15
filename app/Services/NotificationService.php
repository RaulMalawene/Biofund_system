<?php

namespace App\Services;

use App\Enums\AlertLevelEnum;
use App\Models\NotificationLog;
use App\Models\Occurrence;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    public function notifyOccurrenceCreated(Occurrence $occurrence): void
    {
        // Tanto para ocorrências externas como internas, o email de confirmação
        // vai sempre para o reclamante (complainant_email).
        // Para internas: o admin/gestor registou em nome de alguém - essa pessoa
        // deve ser notificada, não quem registou.
        // Se não foi fornecido email do reclamante, não é enviado nenhum email.
        $recipientEmail = $occurrence->complainant_email;

        if ($recipientEmail) {
            $this->sendEmail(
                occurrence: $occurrence,
                recipientEmail: $recipientEmail,
                userId: null,
                eventType: 'occurrence_created',
                subject: "MDR - Reclamação registada | {$occurrence->tracking_code}",
                body: $this->buildCreatedMessage($occurrence)
            );
        }

        // Determina o nível de alerta: preferência para o tipo da ocorrência;
        // em ocorrências internas sem tipo definido usa o alert_type manual.
        $alertLevel = $occurrence->occurrenceType?->alert_level ?? $occurrence->alert_type;
        if ($alertLevel) {
            $this->notifyByAlertLevel($occurrence, $alertLevel);
        }

        // Notifica administradores e gestores responsáveis (sistema + email)
        $this->notifyResponsibleUsers($occurrence);
    }

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
            subject: "MDR - Actualização da ocorrência {$occurrence->tracking_code}",
            body: $this->buildStatusChangedMessage($occurrence, $comment)
        );
    }

    public function notifyAssigned(Occurrence $occurrence, User $gestor): void
    {
        $this->sendEmail(
            occurrence: $occurrence,
            recipientEmail: $gestor->email,
            userId: $gestor->id,
            eventType: 'occurrence_assigned',
            subject: "MDR - Nova ocorrência atribuída | {$occurrence->tracking_code}",
            body: $this->buildAssignedMessage($occurrence, $gestor)
        );
    }

    /**
     * Notifica os administradores (sempre) e os gestores responsáveis pela
     * província/projecto da ocorrência (ou de âmbito nacional).
     *
     * Cada utilizador elegível recebe:
     *   - Uma notificação interna (channel = system, visível no painel)
     *   - Um email "Nova ocorrência registada" (channel = email)
     */
    private function notifyResponsibleUsers(Occurrence $occurrence): void
    {
        $subject = $occurrence->subject ?? $occurrence->tracking_code;
        $message = "Nova ocorrência registada: {$subject} [{$occurrence->tracking_code}]";

        $users = User::active()
            ->where(function ($q) use ($occurrence) {
                // Admins recebem sempre (âmbito global)
                $q->where('role', 'admin')
                  // Gestores recebem apenas se a ocorrência estiver no seu âmbito
                  ->orWhere(function ($gq) use ($occurrence) {
                      $gq->where('role', 'gestor')
                         ->where(function ($scope) use ($occurrence) {
                             // Âmbito nacional: recebe sempre
                             $scope->where('management_scope', 'national')
                                   // Ou pertence à província E ao projecto da ocorrência (AND)
                                   ->orWhere(function ($and) use ($occurrence) {
                                       $and->where(fn($pq) =>
                                               $pq->where('province_id', $occurrence->province_id)
                                                  ->orWhereHas('provinces', fn($q2) =>
                                                      $q2->where('provinces.id', $occurrence->province_id)
                                                  )
                                           );
                                       if ($occurrence->project_id) {
                                           $and->whereHas('projects', fn($q2) =>
                                               $q2->where('projects.id', $occurrence->project_id)
                                           );
                                       }
                                   });
                         });
                  });
            })
            ->get();

        if ($users->isEmpty()) return;

        $now  = now();
        $rows = $users->map(fn($user) => [
            'occurrence_id'   => $occurrence->id,
            'user_id'         => $user->id,
            'recipient_email' => $user->email,
            'channel'         => 'system',
            'event_type'      => 'occurrence_created',
            'message'         => $message,
            'status'          => 'sent',
            'sent_at'         => $now,
            'created_at'      => $now,
            'updated_at'      => $now,
        ])->all();

        // Um único INSERT em vez de N INSERTs individuais
        NotificationLog::insert($rows);

        foreach ($users as $user) {
            $this->sendEmail(
                occurrence: $occurrence,
                recipientEmail: $user->email,
                userId: $user->id,
                eventType: 'occurrence_created_responsible',
                subject: "MDR - Nova ocorrência registada | {$occurrence->tracking_code}",
                body: $this->buildNewOccurrenceMessage($occurrence, $user)
            );
        }
    }

    private function notifyByAlertLevel(Occurrence $occurrence, AlertLevelEnum $level): void
    {
        $column = $level->userAlertColumn();

        // Nível normal: já coberto pelo email genérico de notifyResponsibleUsers().
        if ($column === null) {
            return;
        }

        $users = User::active()->where($column, true)->get();

        foreach ($users as $user) {
            $this->sendEmail(
                occurrence: $occurrence,
                recipientEmail: $user->email,
                userId: $user->id,
                eventType: $level === AlertLevelEnum::Gbv ? 'alert_gbv' : 'alert_urgent',
                subject: "[{$level->label()}] MDR - Nova ocorrência | {$occurrence->tracking_code}",
                body: $this->buildAlertMessage($occurrence, $level)
            );
        }
    }

    private function sendEmail(
        Occurrence $occurrence,
        string $recipientEmail,
        ?int $userId,
        string $eventType,
        string $subject,
        string $body
    ): void {
        try {
            $log = NotificationLog::create([
                'occurrence_id'   => $occurrence->id,
                'user_id'         => $userId,
                'recipient_email' => $recipientEmail,
                'channel'         => 'email',
                'event_type'      => $eventType,
                'message'         => $body,
                'status'          => 'pending',
            ]);

            Mail::raw($body, function ($mail) use ($recipientEmail, $subject) {
                $mail->to($recipientEmail)->subject($subject);
            });

            $log->update(['status' => 'sent', 'sent_at' => now()]);

        } catch (\Throwable $e) {
            Log::error("MDR NotificationService: falha ao enviar email para {$recipientEmail}", [
                'occurrence_id' => $occurrence->id,
                'event_type'    => $eventType,
                'error'         => $e->getMessage(),
            ]);
        }
    }

    /**
     * Número de dias úteis sem actualização de estado a partir do qual o
     * comando occurrences:mark-unresolved marca esta ocorrência como
     * 'Não Resolvida'. Usado para informar o gestor no email.
     */
    private function statusUpdateBusinessDaysLimit(Occurrence $occurrence): int
    {
        return $occurrence->statusUpdateBusinessDaysLimit();
    }

    // ─── Templates ───────────────────────────────────────────────

    private function buildCreatedMessage(Occurrence $occurrence): string
    {
        // Variáveis locais - o heredoc não suporta operadores (??) dentro de {}
        $name       = $occurrence->complainant_name ?? 'Reclamante';
        $subject    = $occurrence->subject          ?? 'Não especificado';
        $dueDate    = $occurrence->statusUpdateDueDate()->format('d/m/Y');
        $code       = $occurrence->tracking_code;
        $url        = config('app.url') . "/acompanhar?codigo={$code}";
        $project    = $occurrence->project->name;
        $registedAt = $occurrence->created_at->format('d/m/Y H:i');

        return <<<TEXT
Prezado(a) $name,

A sua ocorrência foi registada com sucesso no sistema MDR - Mecanismo de Diálogo e Reclamações.

Código de seguimento: $code

Guarde este código para acompanhar o estado da sua ocorrência em:
$url

Assunto: $subject
Projecto: $project
Data de registo: $registedAt
Prazo de resolução: $dueDate

Com os melhores cumprimentos,
Equipa MDR - BIOFUND
TEXT;
    }

    private function buildStatusChangedMessage(Occurrence $occurrence, ?string $comment): string
    {
        $name         = $occurrence->complainant_name ?? 'Reclamante';
        $statusLabel  = $occurrence->status->label();
        $code         = $occurrence->tracking_code;
        $responseLine = $comment ? "Resposta: {$comment}" : '';
        $url          = config('app.url') . "/acompanhar?codigo={$code}";

        return <<<TEXT
Prezado(a) $name,

O estado da sua ocorrência foi actualizado.

Código de seguimento: $code
Novo estado: $statusLabel
$responseLine

Acompanhe a sua ocorrência em:
$url

Com os melhores cumprimentos,
Equipa MDR - BIOFUND
TEXT;
    }

    private function buildAssignedMessage(Occurrence $occurrence, User $gestor): string
    {
        $gestorName  = $gestor->name;
        $code        = $occurrence->tracking_code;
        $subject     = $occurrence->subject              ?? 'Não especificado';
        $typeName    = $occurrence->occurrenceType?->name ?? 'Não definido';
        $project     = $occurrence->project->name;
        $province    = $occurrence->province->name;
        $dueDate     = $occurrence->due_date
                        ? $occurrence->due_date->format('d/m/Y')
                        : 'A definir';
        $statusLimit = $this->statusUpdateBusinessDaysLimit($occurrence);

        return <<<TEXT
Prezado(a) $gestorName,

Foi-lhe atribuída uma nova ocorrência para tratamento.

Código: $code
Assunto: $subject
Tipo: $typeName
Projecto: $project
Província: $province
Prazo: $dueDate

Atenção: se o estado desta ocorrência não for actualizado em $statusLimit dias úteis, o sistema marcá-la-á automaticamente como "Não Resolvida".

Aceda ao painel MDR para tratar esta ocorrência.

Com os melhores cumprimentos,
Sistema MDR - BIOFUND
TEXT;
    }

    private function buildNewOccurrenceMessage(Occurrence $occurrence, User $user): string
    {
        $name        = $user->name;
        $code        = $occurrence->tracking_code;
        $origin      = $occurrence->isExternal() ? 'Formulário público' : 'Registo interno';
        $subject     = $occurrence->subject               ?? 'Não especificado';
        $typeName    = $occurrence->occurrenceType?->name ?? 'Não definido';
        $project     = $occurrence->project->name;
        $province    = $occurrence->province->name;
        $dueDate     = $occurrence->statusUpdateDueDate()->format('d/m/Y');
        $statusLimit = $this->statusUpdateBusinessDaysLimit($occurrence);

        return <<<TEXT
Prezado(a) $name,

Foi registada uma nova ocorrência na sua área de gestão.

Código: $code
Origem: $origin
Assunto: $subject
Tipo: $typeName
Projecto: $project
Província: $province
Prazo: $dueDate

Atenção: se o estado desta ocorrência não for actualizado em $statusLimit dias úteis, o sistema marcá-la-á automaticamente como "Não Resolvida".

Aceda ao painel MDR para mais detalhes.

Com os melhores cumprimentos,
Sistema MDR - BIOFUND
TEXT;
    }

    private function buildAlertMessage(Occurrence $occurrence, AlertLevelEnum $level): string
    {
        $alertLabel  = $level->label();
        $code        = $occurrence->tracking_code;
        $subject     = $occurrence->subject ?? 'Não especificado';
        $project     = $occurrence->project->name;
        $province    = $occurrence->province->name;
        $dueDate     = $occurrence->due_date
                        ? $occurrence->due_date->format('d/m/Y')
                        : 'A definir';
        $statusLimit = $this->statusUpdateBusinessDaysLimit($occurrence);

        return <<<TEXT
⚠️  ALERTA $alertLabel

Foi registada uma nova ocorrência que requer atenção imediata.

Código: $code
Assunto: $subject
Projecto: $project
Província: $province
Prazo: $dueDate

Atenção: se o estado desta ocorrência não for actualizado em $statusLimit dias úteis, o sistema marcá-la-á automaticamente como "Não Resolvida".

Aceda ao painel MDR para tratar esta ocorrência com urgência.

Sistema MDR - BIOFUND
TEXT;
    }
}