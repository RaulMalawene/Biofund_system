<?php

namespace App\Http\Controllers\Api\Gestor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Occurrence\StoreInternalOccurrenceRequest;
use App\Http\Requests\Occurrence\UpdateOccurrenceStatusRequest;
use App\Http\Resources\OccurrenceResource;
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

        // Restrição por perfil
        match ($user->role) {
            RoleEnum::Funcionario => $query->where('submitted_by_user_id', $user->id),
            RoleEnum::Gestor      => $query->where('province_id', $user->province_id),
            RoleEnum::Admin       => null,
        };

        // Filtros opcionais
        $query->when($request->status, fn($q) => $q->where('status', $request->status));
        $query->when($request->project_id, fn($q) => $q->where('project_id', $request->project_id));
        $query->when($request->province_id, fn($q) => $q->where('province_id', $request->province_id));
        $query->when($request->category_id, fn($q) => $q->where('category_id', $request->category_id));
        $query->when($request->occurrence_type_id, fn($q) => $q->where('occurrence_type_id', $request->occurrence_type_id));
        $query->when($request->date_from, fn($q) => $q->whereDate('created_at', '>=', $request->date_from));
        $query->when($request->date_to, fn($q) => $q->whereDate('created_at', '<=', $request->date_to));
        $query->when($request->search, fn($q) =>
            $q->where(fn($q2) =>
                $q2->where('subject', 'like', "%{$request->search}%")
                   ->orWhere('tracking_code', 'like', "%{$request->search}%")
                   ->orWhere('complainant_name', 'like', "%{$request->search}%")
            )
        );
        if ($request->boolean('only_mine')) {
            $query->where('assigned_to', $user->id);
        }

        $query->orderBy('created_at', 'desc');
        $perPage = min($request->integer('per_page', 15), 100);

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

        if ($user->isGestor() && $user->province_id !== $occurrence->province_id) {
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
            if ($user->province_id !== $occurrence->province_id) {
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

    // ─── Helper ─────────────────────────────────────────────────

    private function canAccess(User $user, Occurrence $occurrence): bool
    {
        return match ($user->role) {
            RoleEnum::Admin       => true,
            RoleEnum::Gestor      => $user->province_id === $occurrence->province_id,
            RoleEnum::Funcionario => $occurrence->submitted_by_user_id === $user->id,
        };
    }
}