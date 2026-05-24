<?php

namespace App\Http\Controllers\Api\Gestor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Occurrence\StoreInternalOccurrenceRequest;
use App\Http\Requests\Occurrence\UpdateOccurrenceStatusRequest;
use App\Http\Resources\OccurrenceResource;
use App\Enums\AlertLevelEnum;
use App\Enums\OccurrenceStatusEnum;
use App\Enums\RoleEnum;
use App\Models\Occurrence;
use App\Models\User;
use App\Services\OccurrenceService;
use App\Services\AuditService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * GestorOccurrenceController
 *
 * Regras de visibilidade por perfil:
 *   - Admin       → vê TODAS as ocorrências
 *   - Gestor      → vê TODAS as ocorrências da SUA província
 *   - Funcionário → vê APENAS as ocorrências que ele próprio registou
 *
 * Regras de acção:
 *   - Admin e Gestor → podem validar, rejeitar, atribuir
 *   - Gestor         → pode atribuir ao admin (escalar) ou a si próprio
 *   - Funcionário    → só regista, não pode validar/rejeitar/atribuir
 */
class GestorOccurrenceController extends Controller
{
    public function __construct(
        private OccurrenceService $occurrenceService,
        private AuditService      $auditService,
    ) {}

    /**
     * ROTA: GET /api/occurrences
     * ACESSO: admin, gestor, funcionario
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $user  = $request->user();
        $query = Occurrence::with([
            'project:id,name,code',
            'province:id,name',
            'district:id,name',
            'category:id,name',
            'subcategory:id,name',
            'occurrenceType:id,name,alert_level',
            'assignedTo:id,name',
            'submittedBy:id,name',
        ])->withCount('attachments');

        // Pré-computa IDs de província/projecto do gestor para reutilizar nas queries.
        $gestorProvinceIds = [];
        $gestorProjectIds  = [];
        if ($user->isGestor()) {
            $gestorProvinceIds = collect($user->province_id ? [$user->province_id] : [])
                ->merge($user->provinces()->pluck('provinces.id'))
                ->unique()->values()->all();
            $gestorProjectIds = $user->projects()->pluck('projects.id')->all();
        }

        // Restrição por perfil
        match ($user->role) {
            RoleEnum::Funcionario => $query->where('submitted_by_user_id', $user->id),

            // Gestor vê:
            //   - ocorrências da sua província / projecto
            //   - ocorrências que ele próprio submeteu
            //   - ocorrências submetidas por funcionários da mesma província ou projecto
            RoleEnum::Gestor => $query->where(fn($q) =>
                $q->whereIn('province_id', $gestorProvinceIds)
                  ->orWhereIn('project_id', $gestorProjectIds)
                  ->orWhere('submitted_by_user_id', $user->id)
                  ->orWhereHas('submittedBy', fn($q2) =>
                      $q2->where('role', RoleEnum::Funcionario->value)
                         ->where(fn($q3) =>
                             $q3->whereIn('province_id', $gestorProvinceIds)
                                ->orWhereHas('provinces', fn($q4) =>
                                    $q4->whereIn('provinces.id', $gestorProvinceIds)
                                )
                                ->orWhereHas('projects', fn($q4) =>
                                    $q4->whereIn('projects.id', $gestorProjectIds)
                                )
                         )
                  )
            ),

            RoleEnum::Admin => null,
        };

        // Filtro de visibilidade de alertas para gestores.
        // Ocorrências submetidas pelo próprio gestor são sempre visíveis,
        // independentemente do nível de alerta e das suas permissões.
        if ($user->isGestor()) {
            if (!$user->receives_urgent_alerts) {
                $query->where(fn($q) =>
                    $q->where('alert_type', '!=', AlertLevelEnum::Urgent->value)
                      ->orWhereNull('alert_type')
                      ->orWhere('submitted_by_user_id', $user->id)
                );
            }
            if (!$user->receives_gbv_alerts) {
                $query->where(fn($q) =>
                    $q->where('alert_type', '!=', AlertLevelEnum::Gbv->value)
                      ->orWhereNull('alert_type')
                      ->orWhere('submitted_by_user_id', $user->id)
                );
            }
        }

        // Modo histórico: só ocorrências terminais (resolvidas ou não validadas).
        // Para gestores, exclui também ocorrências submetidas pelo admin.
        if ($request->boolean('history')) {
            $query->whereIn('status', [
                OccurrenceStatusEnum::Resolvido->value,
                OccurrenceStatusEnum::NaoValidado->value,
            ]);
            if ($user->isGestor()) {
                $query->where(fn($q) =>
                    $q->whereNull('submitted_by_user_id')
                      ->orWhere('submitted_by_user_id', $user->id)
                      ->orWhereHas('submittedBy', fn($q2) =>
                          $q2->where('role', '!=', RoleEnum::Admin->value)
                      )
                );
            }
        }

        // Modo activo: só ocorrências não terminais (por validar, por resolver, resolvendo).
        if ($request->boolean('active_only')) {
            $query->whereNotIn('status', [
                OccurrenceStatusEnum::Resolvido->value,
                OccurrenceStatusEnum::NaoValidado->value,
            ]);
        }

        // Filtros opcionais
        $query->when($request->status, fn($q) => $q->where('status', $request->status));
        $query->when($request->alert_type, fn($q) => $q->where('alert_type', $request->alert_type));
        $query->when($request->project_id, fn($q) => $q->where('project_id', $request->project_id));
        $query->when($request->province_id, fn($q) => $q->where('province_id', $request->province_id));
        $query->when($request->category_id, fn($q) => $q->where('category_id', $request->category_id));
        $query->when($request->submission_channel, fn($q) => $q->where('submission_channel', $request->submission_channel));
        $query->when($request->occurrence_type_id, fn($q) => $q->where('occurrence_type_id', $request->occurrence_type_id));
        $query->when($request->date_from, fn($q) => $q->whereDate('created_at', '>=', $request->date_from));
        $query->when($request->date_to, fn($q) => $q->whereDate('created_at', '<=', $request->date_to));

        // Listas de IDs separadas por vírgula (usadas pelo frontend do gestor)
        $query->when($request->project_ids, fn($q) =>
            $q->whereIn('project_id', array_filter(explode(',', $request->project_ids)))
        );
        $query->when($request->province_ids, fn($q) =>
            $q->whereIn('province_id', array_filter(explode(',', $request->province_ids)))
        );

        $query->when($request->search, fn($q) =>
            $q->where(fn($q2) =>
                $q2->where('subject', 'like', "%{$request->search}%")
                   ->orWhere('tracking_code', 'like', "%{$request->search}%")
                   ->orWhere('complainant_name', 'like', "%{$request->search}%")
            )
        );

        // Origem: externo, interno, ou only_mine (atribuídas ao utilizador)
        $query->when($request->origin && $request->origin !== 'only_mine', fn($q) =>
            $q->where('origin', $request->origin)
        );
        if ($request->boolean('only_mine')) {
            $query->where('submitted_by_user_id', $user->id);
        }

        $query->orderBy('created_at', 'desc');
        $perPage = min($request->integer('per_page', 15), 500);

        return OccurrenceResource::collection($query->paginate($perPage));
    }

    /**
     * ROTA: GET /api/occurrences/{occurrence}
     * ACESSO: admin, gestor, funcionario (com restrições)
     */
    public function show(Request $request, Occurrence $occurrence): OccurrenceResource|JsonResponse
    {
        if (!$this->canAccess($request->user(), $occurrence)) {
            return response()->json(['message' => 'Não tem acesso a esta ocorrência.'], 403);
        }

        $occurrence->load([
            'project', 'province', 'district',
            'category', 'subcategory', 'occurrenceType',
            'assignedTo:id,name,email',
            'reviewedBy:id,name',
            'submittedBy:id,name',
            'attachments',
            'statusHistory.changedBy:id,name',
        ]);

        return new OccurrenceResource($occurrence);
    }

    /**
     * ROTA: POST /api/occurrences
     * ACESSO: admin, gestor, funcionario
     *
     * Body: multipart/form-data
     *   Campos de texto normais + attachments[] (ficheiros, máx 5, 10MB cada)
     *   Formatos aceites: jpg, jpeg, png, pdf, doc, docx, mp4, mp3
     */
    public function store(StoreInternalOccurrenceRequest $request): JsonResponse
    {
        $files = $request->hasFile('attachments') ? $request->file('attachments') : [];

        $occurrence = $this->occurrenceService->createInternal(
            data:  $request->validated(),
            user:  $request->user(),
            files: $files
        );

        return response()->json([
            'message'       => 'Ocorrência registada com sucesso.',
            'tracking_code' => $occurrence->tracking_code,
            'occurrence_id' => $occurrence->id,
            'attachments'   => $occurrence->attachments->map(fn($a) => [
                'id'       => $a->id,
                'name'     => $a->original_name,
                'size'     => $a->getFormattedSize(),
                'mime'     => $a->mime_type,
                'is_image' => $a->isImage(),
                'url'      => $a->getUrl(),
            ]),
        ], 201);
    }

    /**
     * Muda o estado de uma ocorrência.
     * Comentário/justificação é OBRIGATÓRIO ao resolver ou rejeitar.
     *
     * ROTA: PATCH /api/occurrences/{occurrence}/status
     * ACESSO: admin, gestor (protegido na rota — funcionario não acede)
     */
    public function updateStatus(
        UpdateOccurrenceStatusRequest $request,
        Occurrence $occurrence
    ): JsonResponse {
        $user      = $request->user();
        $newStatus = OccurrenceStatusEnum::from($request->status);

        if (!$this->canAccess($user, $occurrence)) {
            return response()->json(['message' => 'Não tem acesso a esta ocorrência.'], 403);
        }

        $occurrence = $this->occurrenceService->changeStatus(
            occurrence:   $occurrence,
            newStatus:    $newStatus,
            changedBy:    $user,
            comment:      $request->comment,
            internalNote: $request->internal_note,
        );

        return response()->json([
            'message'      => 'Estado actualizado com sucesso.',
            'status'       => $occurrence->status->value,
            'status_label' => $occurrence->status->label(),
            'status_color' => $occurrence->status->color(),
        ], 200);
    }

    /**
     * Adiciona um comentário a uma ocorrência sem alterar o estado.
     * Disponível em todos os estados do ciclo de vida.
     *
     * ROTA: POST /api/occurrences/{occurrence}/comment
     * ACESSO: admin, gestor
     */
    public function addComment(Request $request, Occurrence $occurrence): JsonResponse
    {
        $request->validate([
            'comment'       => ['required', 'string', 'min:2', 'max:2000'],
            'internal_note' => ['nullable', 'string', 'max:2000'],
        ]);

        $user = $request->user();

        if (!$this->canAccess($user, $occurrence)) {
            return response()->json(['message' => 'Não tem acesso a esta ocorrência.'], 403);
        }

        $this->occurrenceService->addComment(
            occurrence:   $occurrence,
            addedBy:      $user,
            comment:      $request->comment,
            internalNote: $request->internal_note,
        );

        return response()->json(['message' => 'Comentário adicionado com sucesso.'], 200);
    }

    /**
     * Atribui uma ocorrência a um gestor ou ao administrador.
     *
     * Regras:
     *   - Admin → pode atribuir a qualquer gestor ou admin.
     *   - Gestor → pode atribuir a si próprio ou escalar para o admin.
     *
     * ROTA: PATCH /api/occurrences/{occurrence}/assign
     * ACESSO: admin, gestor (protegido na rota)
     */
    public function assign(Request $request, Occurrence $occurrence): JsonResponse
    {
        $request->validate([
            'user_id' => ['required', 'integer', 'exists:users,id'],
        ]);

        $user   = $request->user();
        $target = User::findOrFail($request->user_id);

        if ($user->isGestor()) {
            if (!$this->canAccess($user, $occurrence)) {
                return response()->json(['message' => 'Não tem acesso a esta ocorrência.'], 403);
            }
            // Gestor só pode atribuir a si próprio ou escalar para um admin
            if ($target->id !== $user->id && !$target->isAdmin()) {
                return response()->json([
                    'message' => 'Só pode atribuir a si próprio ou escalar para o administrador.',
                ], 422);
            }
        }

        $occurrence = $this->occurrenceService->assignToGestor(
            occurrence: $occurrence,
            gestor:     $target,
            assignedBy: $user,
        );

        return response()->json([
            'message'     => "Ocorrência atribuída a {$target->name} com sucesso.",
            'assigned_to' => ['id' => $target->id, 'name' => $target->name],
        ], 200);
    }

    /**
     * ROTA: DELETE /api/occurrences/{occurrence}
     * ACESSO: admin (protegido na rota)
     */
    public function destroy(Request $request, Occurrence $occurrence): JsonResponse
    {
        $this->auditService->logDeleted($occurrence);
        $occurrence->delete();

        return response()->json(['message' => 'Ocorrência removida com sucesso.'], 200);
    }

    /**
     * ROTA: GET /api/occurrences/deleted
     * ACESSO: admin (protegido na rota)
     */
    public function deleted(): AnonymousResourceCollection
    {
        $occurrences = Occurrence::onlyTrashed()
            ->with(['project:id,name', 'province:id,name', 'category:id,name'])
            ->orderBy('deleted_at', 'desc')
            ->paginate(15);

        return OccurrenceResource::collection($occurrences);
    }

    /**
     * Actualiza o projecto e/ou categoria de uma ocorrência.
     * Apenas admin e gestor podem re-classificar.
     *
     * ROTA: PATCH /api/occurrences/{occurrence}/classification
     * ACESSO: admin, gestor
     */
    public function updateClassification(Request $request, Occurrence $occurrence): JsonResponse
    {
        if (!$this->canAccess($request->user(), $occurrence)) {
            return response()->json(['message' => 'Não tem acesso a esta ocorrência.'], 403);
        }

        $data = $request->validate([
            'project_id'  => ['sometimes', 'integer', 'exists:projects,id'],
            'category_id' => ['sometimes', 'integer', 'exists:categories,id'],
        ]);

        $occurrence->update($data);

        $occurrence->load(['project:id,name,code', 'category:id,name']);

        return response()->json([
            'message'     => 'Classificação actualizada com sucesso.',
            'project'     => $occurrence->project  ? ['id' => $occurrence->project->id,  'name' => $occurrence->project->name]  : null,
            'category'    => $occurrence->category ? ['id' => $occurrence->category->id, 'name' => $occurrence->category->name] : null,
        ], 200);
    }

    // ─── Helpers ────────────────────────────────────────────────

    private function canAccess(User $user, Occurrence $occurrence): bool
    {
        return match ($user->role) {
            RoleEnum::Admin       => true,
            RoleEnum::Gestor      => $occurrence->submitted_by_user_id === $user->id
                                     || ($this->gestorHasProvince($user, $occurrence->province_id)
                                         && $this->gestorCanSeeAlert($user, $occurrence))
                                     || $this->gestorCanAccessViaSubmitter($user, $occurrence),
            RoleEnum::Funcionario => $occurrence->submitted_by_user_id === $user->id,
        };
    }

    private function gestorHasProvince(User $user, ?int $provinceId): bool
    {
        if ($provinceId === null) return false;
        return $user->province_id === $provinceId
            || $user->provinces()->where('provinces.id', $provinceId)->exists();
    }

    /**
     * Verifica se o gestor pode aceder a uma ocorrência com base no perfil
     * do seu autor: funcionários da mesma província ou projecto são visíveis.
     */
    private function gestorCanAccessViaSubmitter(User $user, Occurrence $occurrence): bool
    {
        if (!$occurrence->submitted_by_user_id) return false;

        $submitter = $occurrence->submittedBy ?? $occurrence->load('submittedBy')->submittedBy;
        if (!$submitter || $submitter->role !== RoleEnum::Funcionario) return false;

        // Partilha de província (directa ou many-to-many)
        if ($submitter->province_id && $this->gestorHasProvince($user, $submitter->province_id)) {
            return true;
        }
        foreach ($submitter->provinces as $p) {
            if ($this->gestorHasProvince($user, $p->id)) return true;
        }

        // Partilha de projecto
        $gestorProjectIds = $user->projects()->pluck('projects.id');
        foreach ($submitter->projects as $p) {
            if ($gestorProjectIds->contains($p->id)) return true;
        }

        return false;
    }

    /**
     * Verifica se o gestor tem permissão para ver a ocorrência com base
     * no tipo de alerta e nas suas preferências de visibilidade.
     * Ocorrências sem alert_type e ocorrências submetidas pelo próprio gestor
     * são sempre visíveis.
     */
    private function gestorCanSeeAlert(User $user, Occurrence $occurrence): bool
    {
        if ($occurrence->submitted_by_user_id === $user->id) return true;
        if ($occurrence->alert_type === AlertLevelEnum::Urgent && !$user->receives_urgent_alerts) {
            return false;
        }
        if ($occurrence->alert_type === AlertLevelEnum::Gbv && !$user->receives_gbv_alerts) {
            return false;
        }
        return true;
    }
}