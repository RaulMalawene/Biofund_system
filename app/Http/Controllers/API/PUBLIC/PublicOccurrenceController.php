<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\Occurrence\StoreExternalOccurrenceRequest;
use App\Models\Category;
use App\Models\District;
use App\Models\Occurrence;
use App\Models\OccurrenceType;
use App\Models\Project;
use App\Models\Province;
use App\Services\OccurrenceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * PublicOccurrenceController
 *
 * Endpoints públicos — não requerem autenticação.
 * Usados pelo formulário público de submissão e pela
 * página de acompanhamento por código de seguimento.
 *
 * Também serve os dados de referência necessários
 * para preencher os selects do formulário público
 * (projectos, categorias, províncias, etc.).
 */
class PublicOccurrenceController extends Controller
{
    public function __construct(
        private OccurrenceService $occurrenceService
    ) {}

    /**
     * Submete uma nova ocorrência via formulário público.
     * Não requer login. O reclamante recebe o tracking_code por email.
     *
     * ROTA: POST /api/public/occurrences
     * ACESSO: Público (sem autenticação)
     *
     * Body (multipart/form-data para suportar ficheiros):
     *   {
     *     "complainant_name": "João Silva",
     *     "complainant_email": "joao@email.com",   (opcional)
     *     "complainant_phone": "+258 84 000 0000",  (opcional)
     *     "project_id": 1,
     *     "category_id": 1,
     *     "subcategory_id": 2,                     (opcional)
     *     "occurrence_type_id": 1,
     *     "province_id": 1,
     *     "district_id": 3,                        (opcional)
     *     "location_detail": "Bairro Central",     (opcional)
     *     "subject": "Poluição do rio",
     *     "description": "Descrição detalhada...",
     *     "occurrence_date": "2024-03-15",         (opcional)
     *     "attachments[]": [ficheiro1, ficheiro2]  (opcional, máx 5 ficheiros, 10MB cada)
     *   }
     *
     * Resposta de sucesso (201):
     *   {
     *     "message": "Ocorrência registada com sucesso.",
     *     "tracking_code": "MDR-K7P2QA-2024",
     *     "due_date": "30/03/2024"
     *   }
     */
    public function store(StoreExternalOccurrenceRequest $request): JsonResponse
    {
        $files = $request->hasFile('attachments')
            ? $request->file('attachments')
            : [];

        $occurrence = $this->occurrenceService->createExternal(
            data: $request->validated(),
            files: $files
        );

        return response()->json([
            'message'           => 'A sua ocorrência foi registada com sucesso.',
            'tracking_code'     => $occurrence->tracking_code,
            'due_date'          => $occurrence->due_date?->format('d/m/Y'),
            'attachments_count' => $occurrence->attachments->count(),
            'info'              => 'Guarde o código de seguimento para acompanhar o estado da sua ocorrência.',
        ], 201);
    }

    /**
     * Permite ao reclamante acompanhar a sua ocorrência pelo tracking_code.
     * Retorna o estado actual e o histórico público (sem notas internas).
     *
     * ROTA: GET /api/public/occurrences/track/{code}
     * ACESSO: Público (sem autenticação)
     *
     * Parâmetro de rota:
     *   code → ex: MDR-K7P2QA-2024
     *
     * Resposta de sucesso (200):
     *   {
     *     "tracking_code": "MDR-K7P2QA-2024",
     *     "subject": "Poluição do rio",
     *     "status": "in_review",
     *     "status_label": "Em Análise",
     *     "status_color": "blue",
     *     "project": "FNDS-001",
     *     "submitted_at": "15/03/2024",
     *     "due_date": "30/03/2024",
     *     "history": [
     *       { "status": "Pendente", "comment": "Registada.", "date": "15/03/2024 10:00" },
     *       { "status": "Em Análise", "comment": "A ser tratada.", "date": "16/03/2024 09:00" }
     *     ]
     *   }
     */
    public function track(string $code): JsonResponse
    {
        $occurrence = Occurrence::with([
            'project',
            'province',
            'district',
            'category',
            'subcategory',
            'occurrenceType',
            'attachments',
            'statusHistory' => fn($q) => $q->orderBy('changed_at', 'asc'),
        ])->where('tracking_code', strtoupper($code))->first();

        if (!$occurrence) {
            return response()->json([
                'message' => 'Código de seguimento não encontrado. Verifique e tente novamente.',
            ], 404);
        }

        return response()->json([
            // ── Identificação ─────────────────────────────────────
            'tracking_code'   => $occurrence->tracking_code,
            'origin'          => $occurrence->origin->label(),

            // ── Reclamante ────────────────────────────────────────
            'submitted_by'    => $occurrence->complainant_name,

            // ── Classificação ─────────────────────────────────────
            'project' => [
                'name' => $occurrence->project->name,
                'code' => $occurrence->project->code,
            ],
            'category'    => $occurrence->category->name,
            'subcategory' => $occurrence->subcategory?->name,
            'type' => [
                'name'        => $occurrence->occurrenceType->name,
                'alert_level' => $occurrence->occurrenceType->alert_level->value,
                'alert_label' => $occurrence->occurrenceType->alert_level->label(),
                'sla_days'    => $occurrence->occurrenceType->sla_days,
            ],

            // ── Conteúdo preenchido ───────────────────────────────
            'subject'         => $occurrence->subject,
            'description'     => $occurrence->description,
            'occurrence_date' => $occurrence->occurrence_date?->format('d/m/Y'),

            // ── Localização ───────────────────────────────────────
            'province'        => $occurrence->province->name,
            'district'        => $occurrence->district?->name,
            'location_detail' => $occurrence->location_detail,

            // ── Estado actual ─────────────────────────────────────
            'status'       => $occurrence->status->value,
            'status_label' => $occurrence->status->label(),
            'status_color' => $occurrence->status->color(),
            'is_overdue'   => $occurrence->isOverdue(),

            // ── Datas ─────────────────────────────────────────────
            'submitted_at' => $occurrence->created_at->format('d/m/Y H:i'),
            'due_date'     => $occurrence->due_date?->format('d/m/Y'),

            // ── Anexos (metadados — download requer autenticação) ─
            'attachments' => $occurrence->attachments->map(fn($a) => [
                'name'     => $a->original_name,
                'size'     => $a->getFormattedSize(),
                'mime'     => $a->mime_type,
                'is_image' => $a->isImage(),
            ]),

            // ── Histórico público (sem notas internas) ────────────
            'history' => $occurrence->statusHistory->map(fn($h) => [
                'from'       => $h->from_status?->label(),
                'to'         => $h->to_status->label(),
                'to_color'   => $h->to_status->color(),
                'comment'    => $h->comment,
                'date'       => $h->changed_at->format('d/m/Y H:i'),
            ]),
        ], 200);
    }

    /**
     * Retorna os dados de referência para preencher o formulário público.
     * Inclui projectos, categorias, tipos, províncias e distritos activos.
     *
     * ROTA: GET /api/public/form-data
     * ACESSO: Público (sem autenticação)
     *
     * Resposta (200):
     *   {
     *     "projects": [...],
     *     "categories": [...],
     *     "occurrence_types": [...],
     *     "provinces": [...]
     *   }
     */
    public function formData(): JsonResponse
    {
        return response()->json([
            'projects' => Project::active()->select('id', 'name', 'code')
                ->orderBy('name')->get(),

            'categories' => Category::active()
                ->with(['subcategories' => fn($q) => $q->where('is_active', true)])
                ->select('id', 'name', 'code')
                ->orderBy('name')
                ->get(),

            'occurrence_types' => OccurrenceType::active()
                ->select('id', 'name', 'code', 'alert_level', 'sla_days')
                ->orderBy('name')
                ->get(),

            'provinces' => Province::active()
                ->select('id', 'name', 'code')
                ->orderBy('name')
                ->get(),
        ], 200);
    }

    /**
     * Retorna os distritos de uma província específica.
     * Chamado pelo frontend quando o utilizador selecciona uma província
     * para preencher o campo de distrito.
     *
     * ROTA: GET /api/public/provinces/{province}/districts
     * ACESSO: Público (sem autenticação)
     *
     * Parâmetro de rota:
     *   province → ID da província
     *
     * Resposta (200):
     *   { "districts": [{ "id": 1, "name": "Manhiça" }, ...] }
     */
    public function districtsByProvince(Province $province): JsonResponse
    {
        $districts = District::where('province_id', $province->id)
            ->where('is_active', true)
            ->select('id', 'name')
            ->orderBy('name')
            ->get();

        return response()->json([
            'districts' => $districts,
        ], 200);
    }
}