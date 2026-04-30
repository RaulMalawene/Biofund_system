<?php

namespace App\Http\Controllers\Api\Gestor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Occurrence\StoreInternalOccurrenceRequest;
use App\Http\Requests\Occurrence\UpdateOccurrenceStatusRequest;
use App\Enums\OccurrenceStatusEnum;
use App\Models\Occurrence;
use App\Services\OccurrenceService;
use App\Services\AuditService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * GestorOccurrenceController
 *
 * Gere as operações de ocorrências para utilizadores autenticados:
 * gestores, funcionários e administradores.
 *
 * Regras de visibilidade aplicadas automaticamente:
 *   - Admin nacional → vê todas as ocorrências
 *   - Gestor nacional → vê todas as ocorrências
 *   - Gestor provincial → vê apenas ocorrências da sua província
 *   - Funcionário → vê todas (mas não pode validar/rejeitar)
 */
class GestorOccurrenceController extends Controller
{
    public function __construct(
        private OccurrenceService $occurrenceService,
        private AuditService      $auditService,
    ) {}

    /**
     * Lista as ocorrências de acordo com o scope do utilizador autenticado.
     * Suporta filtros por estado, projecto, província, categoria e período.
     *
     * ROTA: GET /api/occurrences
     * ACESSO: Autenticado (admin, gestor, funcionario)
     *
     * Query params (todos opcionais):
     *   ?status=pending
     *   ?project_id=1
     *   ?province_id=2
     *   ?category_id=3
     *   ?occurrence_type_id=1
     *   ?date_from=2024-01-01
     *   ?date_to=2024-12-31
     *   ?search=texto (pesquisa em subject e tracking_code)
     *   ?per_page=15
     *   ?only_mine=true (apenas as atribuídas a mim)
     *
     * Resposta (200): lista paginada de ocorrências
     */
    public function index(Request $request): JsonResponse
    {
        $user  = $request->user();
        $query = Occurrence::with([
            'project', 'province', 'district',
            'category', 'subcategory', 'occurrenceType',
            'assignedTo:id,name', 'submittedBy:id,name',
        ])->withCount('attachments');

        // Restrição por área geográfica (gestor provincial vê só a sua província)
        if ($user->management_scope === 'provincial' && $user->province_id) {
            $query->where('province_id', $user->province_id);
        }

        // Filtro: apenas as minhas ocorrências
        if ($request->boolean('only_mine')) {
            $query->where('assigned_to', $user->id);
        }

        // Filtros opcionais
        $query->when($request->status, fn($q) =>
            $q->where('status', $request->status)
        );
        $query->when($request->project_id, fn($q) =>
            $q->where('project_id', $request->project_id)
        );
        $query->when($request->province_id, fn($q) =>
            $q->where('province_id', $request->province_id)
        );
        $query->when($request->category_id, fn($q) =>
            $q->where('category_id', $request->category_id)
        );
        $query->when($request->occurrence_type_id, fn($q) =>
            $q->where('occurrence_type_id', $request->occurrence_type_id)
        );
        $query->when($request->date_from, fn($q) =>
            $q->whereDate('created_at', '>=', $request->date_from)
        );
        $query->when($request->date_to, fn($q) =>
            $q->whereDate('created_at', '<=', $request->date_to)
        );
        $query->when($request->search, fn($q) =>
            $q->where(function ($q2) use ($request) {
                $q2->where('subject', 'like', "%{$request->search}%")
                   ->orWhere('tracking_code', 'like', "%{$request->search}%")
                   ->orWhere('complainant_name', 'like', "%{$request->search}%");
            })
        );

        // Ordenação: mais recentes primeiro, urgentes/gbv ao topo
        $query->orderByRaw("FIELD(status, 'pending', 'in_review', 'resolved', 'rejected', 'closed')")
              ->orderByRaw("FIELD(occurrence_types.alert_level, 'gbv', 'urgent', 'normal')")
              ->orderBy('created_at', 'desc');

        $perPage = min($request->integer('per_page', 15), 100);

        return response()->json(
            $query->paginate($perPage)
        , 200);
    }

    /**
     * Retorna o detalhe completo de uma ocorrência, incluindo
     * o histórico de estados e os anexos.
     * As notas internas só são visíveis a gestores e admins.
     *
     * ROTA: GET /api/occurrences/{occurrence}
     * ACESSO: Autenticado (admin, gestor, funcionario)
     *
     * Resposta (200): dados completos da ocorrência
     * Resposta (403): se o gestor provincial tentar ver ocorrência de outra província
     * Resposta (404): se a ocorrência não existir
     */
    public function show(Request $request, Occurrence $occurrence): JsonResponse
    {
        $user = $request->user();

        // Gestor provincial não pode ver ocorrências de outras províncias
        if (
            $user->management_scope === 'provincial'
            && $user->province_id !== $occurrence->province_id
        ) {
            return response()->json([
                'message' => 'Não tem acesso a esta ocorrência.',
            ], 403);
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

        // Define se as notas internas são visíveis
        $canSeeInternalNotes = $user->isAdmin() || $user->isGestor();

        $history = $occurrence->statusHistory->map(fn($h) => [
            'from'          => $h->from_status?->label(),
            'to'            => $h->to_status->label(),
            'to_color'      => $h->to_status->color(),
            'comment'       => $h->comment,
            'internal_note' => $canSeeInternalNotes ? $h->internal_note : null,
            'changed_by'    => $h->changedBy?->name ?? 'Sistema',
            'date'          => $h->changed_at->format('d/m/Y H:i'),
        ]);

        return response()->json([
            'occurrence' => [
                'id'               => $occurrence->id,
                'tracking_code'    => $occurrence->tracking_code,
                'origin'           => $occurrence->origin->label(),
                'subject'          => $occurrence->subject,
                'description'      => $occurrence->description,
                'status'           => $occurrence->status->value,
                'status_label'     => $occurrence->status->label(),
                'status_color'     => $occurrence->status->color(),
                'is_overdue'       => $occurrence->isOverdue(),
                // Reclamante
                'complainant_name'  => $occurrence->complainant_name,
                'complainant_email' => $occurrence->isExternal()
                    ? $occurrence->complainant_email
                    : $occurrence->submittedBy?->email,
                'complainant_phone' => $occurrence->isExternal()
                    ? $occurrence->complainant_phone
                    : $occurrence->submittedBy?->phone,
                // Classificação
                'project'          => $occurrence->project->name,
                'category'         => $occurrence->category->name,
                'subcategory'      => $occurrence->subcategory?->name,
                'type'             => $occurrence->occurrenceType->name,
                'alert_level'      => $occurrence->occurrenceType->alert_level->label(),
                // Localização
                'province'         => $occurrence->province->name,
                'district'         => $occurrence->district?->name,
                'location_detail'  => $occurrence->location_detail,
                // Datas
                'occurrence_date'  => $occurrence->occurrence_date?->format('d/m/Y'),
                'submitted_at'     => $occurrence->created_at->format('d/m/Y H:i'),
                'due_date'         => $occurrence->due_date?->format('d/m/Y'),
                'reviewed_at'      => $occurrence->reviewed_at?->format('d/m/Y H:i'),
                // Responsáveis
                'assigned_to'      => $occurrence->assignedTo
                    ? ['id' => $occurrence->assignedTo->id, 'name' => $occurrence->assignedTo->name]
                    : null,
                'reviewed_by'      => $occurrence->reviewedBy?->name,
                // Sub-entidades
                'attachments'      => $occurrence->attachments->map(fn($a) => [
                    'id'            => $a->id,
                    'name'          => $a->original_name,
                    'size'          => $a->getFormattedSize(),
                    'mime_type'     => $a->mime_type,
                    'is_image'      => $a->isImage(),
                    'url'           => $a->getUrl(),
                ]),
                'history'          => $history,
                // Próximas transições possíveis (para o frontend saber que botões mostrar)
                'can_transition_to' => collect($occurrence->status->allowedTransitions())
                    ->map(fn($s) => ['value' => $s->value, 'label' => $s->label()])
                    ->values(),
            ],
        ], 200);
    }

    /**
     * Submete uma nova ocorrência por um utilizador autenticado.
     * Idêntico ao formulário público mas guarda o ID do utilizador.
     *
     * ROTA: POST /api/occurrences
     * ACESSO: Autenticado (admin, gestor, funcionario)
     */
    public function store(StoreInternalOccurrenceRequest $request): JsonResponse
    {
        $files = $request->hasFile('attachments')
            ? $request->file('attachments')
            : [];

        $occurrence = $this->occurrenceService->createInternal(
            data: $request->validated(),
            user: $request->user(),
            files: $files
        );

        return response()->json([
            'message'       => 'Ocorrência registada com sucesso.',
            'tracking_code' => $occurrence->tracking_code,
            'occurrence_id' => $occurrence->id,
        ], 201);
    }

    /**
     * Muda o estado de uma ocorrência (validar, rejeitar, iniciar análise).
     * Apenas admin e gestor podem validar/rejeitar.
     * Comentário é obrigatório ao resolver ou rejeitar.
     *
     * ROTA: PATCH /api/occurrences/{occurrence}/status
     * ACESSO: Autenticado (admin, gestor — funcionario não pode validar/rejeitar)
     *
     * Body:
     *   {
     *     "status": "resolved",
     *     "comment": "A questão foi resolvida após visita ao local.",
     *     "internal_note": "Coordenação feita com o parceiro X."  (opcional)
     *   }
     */
    public function updateStatus(
        UpdateOccurrenceStatusRequest $request,
        Occurrence $occurrence
    ): JsonResponse {
        $user      = $request->user();
        $newStatus = OccurrenceStatusEnum::from($request->status);

        // Gestor provincial não pode gerir ocorrências de outras províncias
        if (
            $user->management_scope === 'provincial'
            && $user->province_id !== $occurrence->province_id
        ) {
            return response()->json([
                'message' => 'Não tem acesso a esta ocorrência.',
            ], 403);
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
        ], 200);
    }

    /**
     * Atribui uma ocorrência a um gestor.
     * Apenas admins podem atribuir ocorrências.
     *
     * ROTA: PATCH /api/occurrences/{occurrence}/assign
     * ACESSO: Autenticado (apenas admin)
     *
     * Body:
     *   { "user_id": 5 }
     */
    public function assign(Request $request, Occurrence $occurrence): JsonResponse
    {
        // Apenas admins podem atribuir
        if (!$request->user()->isAdmin()) {
            return response()->json([
                'message' => 'Apenas administradores podem atribuir ocorrências.',
            ], 403);
        }

        $request->validate([
            'user_id' => ['required', 'integer', 'exists:users,id'],
        ]);

        $gestor = \App\Models\User::findOrFail($request->user_id);

        $occurrence = $this->occurrenceService->assignToGestor(
            occurrence:  $occurrence,
            gestor:      $gestor,
            assignedBy:  $request->user(),
        );

        return response()->json([
            'message'     => "Ocorrência atribuída a {$gestor->name} com sucesso.",
            'assigned_to' => ['id' => $gestor->id, 'name' => $gestor->name],
        ], 200);
    }

    /**
     * Remove (soft delete) uma ocorrência.
     * A ocorrência continua na base de dados e pode ser vista em "Removidas".
     *
     * ROTA: DELETE /api/occurrences/{occurrence}
     * ACESSO: Autenticado (apenas admin)
     */
    public function destroy(Request $request, Occurrence $occurrence): JsonResponse
    {
        if (!$request->user()->isAdmin()) {
            return response()->json([
                'message' => 'Apenas administradores podem remover ocorrências.',
            ], 403);
        }

        $this->auditService->logDeleted($occurrence);
        $occurrence->delete();

        return response()->json([
            'message' => 'Ocorrência removida com sucesso.',
        ], 200);
    }

    /**
     * Lista as ocorrências removidas (soft deleted).
     * Apenas para consulta — não é possível restaurar via API por agora.
     *
     * ROTA: GET /api/occurrences/deleted
     * ACESSO: Autenticado (apenas admin)
     */
    public function deleted(Request $request): JsonResponse
    {
        if (!$request->user()->isAdmin()) {
            return response()->json(['message' => 'Acesso negado.'], 403);
        }

        $occurrences = Occurrence::onlyTrashed()
            ->with(['project', 'province', 'category'])
            ->orderBy('deleted_at', 'desc')
            ->paginate(15);

        return response()->json($occurrences, 200);
    }
}