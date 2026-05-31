<?php

namespace App\Services;

use App\Enums\OccurrenceStatusEnum;
use App\Enums\OriginEnum;
use App\Models\Occurrence;
use App\Models\OccurrenceAttachment;
use App\Models\OccurrenceStatusHistory;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

/**
 * OccurrenceService
 *
 * Contém toda a lógica de negócio relacionada com ocorrências.
 * Os controllers chamam este serviço - não executam lógica directamente.
 *
 * Responsabilidades:
 *   - Criar ocorrências (externas e internas)
 *   - Processar e guardar anexos
 *   - Gerir o ciclo de vida (mudanças de estado)
 *   - Atribuir responsáveis
 *   - Validar transições de estado
 */
class OccurrenceService
{
    public function __construct(
        private TrackingCodeService $trackingCodeService,
        private NotificationService $notificationService,
        private AuditService        $auditService,
    ) {}

    /**
     * Cria uma nova ocorrência submetida pelo formulário público (sem login).
     * Gera o tracking_code, calcula o due_date e envia as notificações.
     *
     * Usa uma transacção de base de dados para garantir que ou tudo é
     * guardado ou nada é guardado (atomicidade).
     *
     * @param  array  $data    Dados validados do formulário público
     * @param  array  $files   Ficheiros anexados (opcional)
     * @return Occurrence      A ocorrência criada
     */
    public function createExternal(array $data, array $files = []): Occurrence
    {
        return DB::transaction(function () use ($data, $files) {
            // occurrence_type_id é opcional no formulário público -
            // quando não é fornecido o due_date fica null e é calculado
            // manualmente pelo gestor após atribuição.
            $type = isset($data['occurrence_type_id'])
                ? \App\Models\OccurrenceType::findOrFail($data['occurrence_type_id'])
                : null;

            $occurrence = Occurrence::create([
                ...Arr::except($data, ['attachments']),
                'tracking_code' => $this->trackingCodeService->generate(),
                'origin'        => OriginEnum::External,
                'status'        => OccurrenceStatusEnum::PorValidar,
                'due_date'      => $type?->calculateDueDate(),
            ]);

            // Regista o estado inicial no histórico
            $this->recordStatusHistory(
                occurrence: $occurrence,
                from: null,
                to: OccurrenceStatusEnum::PorValidar,
                changedBy: null,
                comment: 'Ocorrência registada via formulário público.'
            );

            // Processa e guarda os ficheiros anexados
            if (!empty($files)) {
                $this->storeAttachments($occurrence, $files, uploadedBy: null);
            }

            // Carrega as relações necessárias para as notificações e resposta
            $occurrence->load(['occurrenceType', 'project', 'province', 'attachments']);

            // Envia notificações (email ao reclamante + alertas aos gestores)
            $this->notificationService->notifyOccurrenceCreated($occurrence);

            // Regista na auditoria
            $this->auditService->logCreated($occurrence);

            Cache::forget('dashboard.admin');

            return $occurrence;
        });
    }

    /**
     * Cria uma nova ocorrência submetida por um utilizador interno autenticado
     * (funcionário, gestor ou admin).
     *
     * O fluxo é idêntico ao externo, a diferença é que:
     *   - origin = internal
     *   - submitted_by_user_id = ID do utilizador autenticado
     *   - Os campos complainant_* não são preenchidos
     *
     * @param  array  $data    Dados validados do formulário interno
     * @param  User   $user    Utilizador autenticado que submete
     * @param  array  $files   Ficheiros anexados (opcional)
     * @return Occurrence
     */
    public function createInternal(array $data, User $user, array $files = []): Occurrence
    {
        return DB::transaction(function () use ($data, $user, $files) {
            $type = isset($data['occurrence_type_id'])
                ? \App\Models\OccurrenceType::findOrFail($data['occurrence_type_id'])
                : null;

            $occurrence = Occurrence::create([
                ...Arr::except($data, ['attachments']),
                'tracking_code'        => $this->trackingCodeService->generate(),
                'origin'               => OriginEnum::Internal,
                'submitted_by_user_id' => $user->id,
                'assigned_to'          => $user->id,
                'status'               => OccurrenceStatusEnum::PorValidar,
                'due_date'             => $type?->calculateDueDate(),
            ]);

            $this->recordStatusHistory(
                occurrence: $occurrence,
                from: null,
                to: OccurrenceStatusEnum::PorValidar,
                changedBy: $user->id,
                comment: "Ocorrência registada internamente por {$user->name}."
            );

            if (!empty($files)) {
                $this->storeAttachments($occurrence, $files, uploadedBy: $user->id);
            }

            $occurrence->load(['occurrenceType', 'project', 'province', 'attachments']);
            $this->notificationService->notifyOccurrenceCreated($occurrence);
            $this->auditService->logCreated($occurrence);

            Cache::forget('dashboard.admin');
            Cache::forget("dashboard.gestor.{$user->id}");

            return $occurrence;
        });
    }

    /**
     * Muda o estado de uma ocorrência.
     *
     * Valida se a transição é permitida (ex: não pode ir de closed para pending).
     * Quando o estado é resolved ou rejected, o comentário é OBRIGATÓRIO.
     *
     * @param  Occurrence              $occurrence  Ocorrência a actualizar
     * @param  OccurrenceStatusEnum    $newStatus   Novo estado pretendido
     * @param  User                    $changedBy   Utilizador que faz a mudança
     * @param  string|null             $comment     Comentário público (obrigatório em resolved/rejected)
     * @param  string|null             $internalNote Nota interna (não visível ao reclamante)
     * @return Occurrence
     * @throws ValidationException  Se a transição for inválida ou o comentário faltar
     */
    public function changeStatus(
        Occurrence $occurrence,
        OccurrenceStatusEnum $newStatus,
        User $changedBy,
        ?string $comment = null,
        ?string $internalNote = null
    ): Occurrence {
        // 1. Valida se a transição é permitida
        if (!$occurrence->status->canTransitionTo($newStatus)) {
            throw ValidationException::withMessages([
                'status' => "Não é possível mudar de '{$occurrence->status->label()}' para '{$newStatus->label()}'.",
            ]);
        }

        // 2. Valida que o comentário existe quando obrigatório
        if ($newStatus->requiresComment() && empty($comment)) {
            throw ValidationException::withMessages([
                'comment' => "O comentário é obrigatório ao {$newStatus->label()} uma ocorrência.",
            ]);
        }

        // 3. Verifica se o utilizador tem permissão para as decisões finais
        if (
            in_array($newStatus, [OccurrenceStatusEnum::NaoValidado, OccurrenceStatusEnum::Resolvido])
            && !$changedBy->canValidate()
        ) {
            throw ValidationException::withMessages([
                'status' => 'Não tem permissão para validar ou resolver ocorrências.',
            ]);
        }

        return DB::transaction(function () use (
            $occurrence, $newStatus, $changedBy, $comment, $internalNote
        ) {
            $oldStatus = $occurrence->status;

            // Quando uma ocorrência pública (sem responsável) é validada pela
            // primeira vez (por_validar → por_resolver), o utilizador que valida
            // passa a ser o responsável automaticamente.
            $autoAssign = $oldStatus === OccurrenceStatusEnum::PorValidar
                && $newStatus === OccurrenceStatusEnum::PorResolver
                && is_null($occurrence->assigned_to);

            // Actualiza a ocorrência
            $occurrence->update([
                'status'      => $newStatus,
                'assigned_to' => $autoAssign ? $changedBy->id : $occurrence->assigned_to,
                'reviewed_by' => in_array($newStatus, [
                    OccurrenceStatusEnum::NaoValidado,
                    OccurrenceStatusEnum::Resolvido,
                ]) ? $changedBy->id : $occurrence->reviewed_by,
                'reviewed_at' => in_array($newStatus, [
                    OccurrenceStatusEnum::NaoValidado,
                    OccurrenceStatusEnum::Resolvido,
                ]) ? now() : $occurrence->reviewed_at,
            ]);

            // Regista a mudança no histórico
            $this->recordStatusHistory(
                occurrence: $occurrence,
                from: $oldStatus,
                to: $newStatus,
                changedBy: $changedBy->id,
                comment: $comment,
                internalNote: $internalNote
            );

            // Notifica o reclamante
            $occurrence->load(['occurrenceType', 'project', 'province', 'submittedBy', 'assignedTo']);
            $this->notificationService->notifyStatusChanged($occurrence, $comment);

            // Regista na auditoria
            $this->auditService->logStatusChanged(
                $occurrence,
                $oldStatus->value,
                $newStatus->value,
                $comment
            );

            Cache::forget('dashboard.admin');
            Cache::forget("dashboard.gestor.{$changedBy->id}");

            // update() já actualizou os atributos em memória - fresh() seria um SELECT redundante
            return $occurrence;
        });
    }

    /**
     * Atribui uma ocorrência a um gestor.
     * Verifica se o gestor pertence ao projecto e à área geográfica da ocorrência.
     *
     * @param  Occurrence  $occurrence   Ocorrência a atribuir
     * @param  User        $gestor       Gestor a quem atribuir
     * @param  User        $assignedBy   Quem faz a atribuição (admin)
     * @return Occurrence
     * @throws ValidationException  Se o gestor não for elegível
     */
    public function assignToGestor(
        Occurrence $occurrence,
        User $gestor,
        User $assignedBy
    ): Occurrence {
        // Valida que o utilizador é gestor ou admin
        if (!$gestor->canValidate()) {
            throw ValidationException::withMessages([
                'assigned_to' => 'Só é possível atribuir ocorrências a gestores ou administradores.',
            ]);
        }

        // Valida que o gestor provincial pertence à mesma província da ocorrência
        if (
            $gestor->management_scope === 'provincial'
            && $gestor->province_id !== $occurrence->province_id
        ) {
            throw ValidationException::withMessages([
                'assigned_to' => 'Este gestor não tem jurisdição sobre a província desta ocorrência.',
            ]);
        }

        $occurrence->update(['assigned_to' => $gestor->id]);

        $occurrence->load(['occurrenceType', 'project', 'province']);
        $this->notificationService->notifyAssigned($occurrence, $gestor);
        $this->auditService->logUpdated(
            $occurrence,
            ['assigned_to' => null],
            ['assigned_to' => $gestor->id]
        );

        // load() já carregou as relações necessárias - fresh() criaria queries redundantes
        $occurrence->load('assignedTo');
        return $occurrence;
    }

    /**
     * Adiciona um comentário a uma ocorrência sem alterar o seu estado.
     * Disponível em todos os estados do ciclo de vida.
     */
    public function addComment(
        Occurrence $occurrence,
        User $addedBy,
        string $comment,
        ?string $internalNote = null
    ): void {
        $this->recordStatusHistory(
            occurrence: $occurrence,
            from: $occurrence->status,
            to: $occurrence->status,
            changedBy: $addedBy->id,
            comment: $comment,
            internalNote: $internalNote,
        );

        $occurrence->load(['occurrenceType', 'project', 'province', 'submittedBy']);
        $this->notificationService->notifyStatusChanged($occurrence, $comment);
    }

    // ─── Métodos privados ────────────────────────────────────────

    /**
     * Guarda os ficheiros anexados no disco configurado.
     * Cada ficheiro é guardado em storage/app/occurrences/{occurrence_id}/
     *
     * @param  Occurrence  $occurrence
     * @param  array       $files        Array de UploadedFile
     * @param  int|null    $uploadedBy   ID do utilizador que fez o upload (null = externo)
     */
    private function storeAttachments(
        Occurrence $occurrence,
        array $files,
        ?int $uploadedBy
    ): void {
        foreach ($files as $file) {
            if (!($file instanceof UploadedFile)) {
                continue;
            }

            $storedName = uniqid('attach_', true) . '.' . $file->getClientOriginalExtension();
            $path       = $file->storeAs(
                "occurrences/{$occurrence->id}",
                $storedName,
                'local'
            );

            OccurrenceAttachment::create([
                'occurrence_id' => $occurrence->id,
                'uploaded_by'   => $uploadedBy,
                'original_name' => $file->getClientOriginalName(),
                'stored_name'   => $storedName,
                'disk'          => 'local',
                'path'          => $path,
                'mime_type'     => $file->getMimeType(),
                'size_bytes'    => $file->getSize(),
            ]);
        }
    }

    /**
     * Regista uma entrada no histórico de estados da ocorrência.
     * Chamado em cada mudança de estado para manter o histórico completo.
     *
     * @param  Occurrence               $occurrence
     * @param  OccurrenceStatusEnum|null $from
     * @param  OccurrenceStatusEnum      $to
     * @param  int|null                  $changedBy
     * @param  string|null               $comment
     * @param  string|null               $internalNote
     */
    private function recordStatusHistory(
        Occurrence $occurrence,
        ?OccurrenceStatusEnum $from,
        OccurrenceStatusEnum $to,
        ?int $changedBy,
        ?string $comment = null,
        ?string $internalNote = null
    ): void {
        OccurrenceStatusHistory::create([
            'occurrence_id' => $occurrence->id,
            'from_status'   => $from?->value,
            'to_status'     => $to->value,
            'changed_by'    => $changedBy,
            'comment'       => $comment,
            'internal_note' => $internalNote,
            'changed_at'    => now(),
        ]);
    }
}