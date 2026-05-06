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

        $alertLevel = $occurrence->occurrenceType->alert_level;
        $this->notifyByAlertLevel($occurrence, $alertLevel);
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
            subject: "MDR — Actualização da ocorrência {$occurrence->tracking_code}",
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
            subject: "MDR — Nova ocorrência atribuída | {$occurrence->tracking_code}",
            body: $this->buildAssignedMessage($occurrence, $gestor)
        );
    }

    private function notifyByAlertLevel(Occurrence $occurrence, AlertLevelEnum $level): void
    {
        $column = $level->userAlertColumn();

        if ($column === null) {
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

    private function sendEmail(
        Occurrence $occurrence,
        string $recipientEmail,
        ?int $userId,
        string $eventType,
        string $subject,
        string $body
    ): void {
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
            Mail::raw($body, function ($mail) use ($recipientEmail, $subject) {
                $mail->to($recipientEmail)->subject($subject);
            });

            $log->update(['status' => 'sent', 'sent_at' => now()]);

        } catch (\Throwable $e) {
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

    // ─── Templates ───────────────────────────────────────────────

    private function buildCreatedMessage(Occurrence $occurrence): string
    {
        $dueDate = $occurrence->due_date
            ? $occurrence->due_date->format('d/m/Y')
            : 'A definir';

        $url = config('app.url') . "/acompanhar?codigo={$occurrence->tracking_code}";

        return <<<TEXT
Prezado(a) {$occurrence->complainant_name},

A sua ocorrência foi registada com sucesso no sistema MDR — Mecanismo de Diálogo e Reclamações.

Código de seguimento: {$occurrence->tracking_code}

Guarde este código para acompanhar o estado da sua ocorrência em:
{$url}

Assunto: {$occurrence->subject}
Projecto: {$occurrence->project->name}
Data de registo: {$occurrence->created_at->format('d/m/Y H:i')}
Prazo de resolução: {$dueDate}

Com os melhores cumprimentos,
Equipa MDR — BIOFUND/FNDS
TEXT;
    }

    private function buildStatusChangedMessage(Occurrence $occurrence, ?string $comment): string
    {
        $statusLabel = $occurrence->status->label();

        $responseLine = $comment
            ? "Resposta: {$comment}"
            : '';

        $url = config('app.url') . "/acompanhar?codigo={$occurrence->tracking_code}";

        return <<<TEXT
Prezado(a) {$occurrence->complainant_name},

O estado da sua ocorrência foi actualizado.

Código de seguimento: {$occurrence->tracking_code}
Novo estado: {$statusLabel}
{$responseLine}

Acompanhe a sua ocorrência em:
{$url}

Com os melhores cumprimentos,
Equipa MDR — BIOFUND/FNDS
TEXT;
    }

    private function buildAssignedMessage(Occurrence $occurrence, User $gestor): string
    {
        $dueDate = $occurrence->due_date
            ? $occurrence->due_date->format('d/m/Y')
            : 'A definir';

        return <<<TEXT
Prezado(a) {$gestor->name},

Foi-lhe atribuída uma nova ocorrência para tratamento.

Código: {$occurrence->tracking_code}
Assunto: {$occurrence->subject}
Tipo: {$occurrence->occurrenceType->name}
Projecto: {$occurrence->project->name}
Província: {$occurrence->province->name}
Prazo: {$dueDate}

Aceda ao painel MDR para tratar esta ocorrência.

Com os melhores cumprimentos,
Sistema MDR — BIOFUND/FNDS
TEXT;
    }

    private function buildAlertMessage(Occurrence $occurrence, AlertLevelEnum $level): string
    {
        $dueDate = $occurrence->due_date
            ? $occurrence->due_date->format('d/m/Y')
            : 'A definir';

        return <<<TEXT
⚠️  ALERTA {$level->label()}

Foi registada uma nova ocorrência que requer atenção imediata.

Código: {$occurrence->tracking_code}
Assunto: {$occurrence->subject}
Projecto: {$occurrence->project->name}
Província: {$occurrence->province->name}
Prazo: {$dueDate}

Aceda ao painel MDR para tratar esta ocorrência com urgência.

Sistema MDR — BIOFUND/FNDS
TEXT;
    }
}