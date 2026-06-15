<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\Occurrence\StoreExternalOccurrenceRequest;
use App\Models\Category;
use App\Models\District;
use App\Models\Occurrence;
use App\Models\OccurrenceAttachment;
use App\Models\OccurrenceType;
use App\Models\Project;
use App\Models\Province;
use App\Services\OccurrenceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class PublicOccurrenceController extends Controller
{
    public function __construct(
        private OccurrenceService $occurrenceService
    ) {}

    /**
     * Submete uma nova ocorrência via formulário público.
     * ROTA: POST /api/public/occurrences
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
     * Acompanhar uma ocorrência pelo tracking_code.
     * ROTA: GET /api/public/occurrences/track/{code}
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
            // Identificação
            'tracking_code'      => $occurrence->tracking_code,
            'origin'             => $occurrence->origin->label(),

            // Reclamante
            'submitted_by'       => $occurrence->complainant_name,
            'complainant_email'  => $occurrence->complainant_email,
            'complainant_phone'  => $occurrence->complainant_phone,
            'complainant_gender' => $occurrence->complainant_gender,
            'complainant_age'    => $occurrence->complainant_age,

            // Classificação
            'project' => [
                'name' => $occurrence->project->name,
                'code' => $occurrence->project->code,
            ],
            'category'    => $occurrence->category->name,
            'subcategory' => $occurrence->subcategory?->name,

            // occurrence_type é nullable - só inclui se existir
            'type' => $occurrence->occurrenceType ? [
                'name'        => $occurrence->occurrenceType->name,
                'alert_level' => $occurrence->occurrenceType->alert_level->value,
                'alert_label' => $occurrence->occurrenceType->alert_level->label(),
                'sla_days'    => $occurrence->occurrenceType->sla_days,
            ] : null,

            // Conteúdo
            'subject'         => $occurrence->subject,
            'description'     => $occurrence->description,
            'occurrence_date' => $occurrence->occurrence_date?->format('d/m/Y'),

            // Localização
            'province'        => $occurrence->province->name,
            'district'        => $occurrence->district?->name,
            'location_detail' => $occurrence->location_detail,

            // Estado
            'status'       => $occurrence->status->value,
            'status_label' => $occurrence->status->label(),
            'status_color' => $occurrence->status->color(),
            'is_overdue'   => $occurrence->isOverdue(),

            // Datas
            'submitted_at' => $occurrence->created_at->format('d/m/Y H:i'),
            'due_date'     => $occurrence->due_date?->format('d/m/Y'),

            // Anexos - inclui id para download público
            'attachments' => $occurrence->attachments->map(fn($a) => [
                'id'       => $a->id,
                'name'     => $a->original_name,
                'size'     => $a->getFormattedSize(),
                'mime'     => $a->mime_type,
                'is_image' => $a->isImage(),
            ]),

            // Histórico público (sem notas internas)
            'history' => $occurrence->statusHistory->map(fn($h) => [
                'from'     => $h->from_status?->label(),
                'to'       => $h->to_status->label(),
                'to_color' => $h->to_status->color(),
                'comment'  => $h->comment,
                'date'     => $h->changed_at->format('d/m/Y H:i'),
            ]),
        ], 200);
    }

    /**
     * Dados de referência para o formulário público.
     * ROTA: GET /api/public/form-data
     *
     * Cache de 1 hora (ficheiro). Invalidado automaticamente pelo
     * ParametrizationController quando categorias, projectos ou tipos são alterados.
     */
    public function formData(): JsonResponse
    {
        $data = Cache::remember('ref.form_data', 3600, fn() => [
            'projects' => Project::active()
                ->select('id', 'name', 'code')
                ->orderBy('name')
                ->get()
                ->toArray(),

            'categories' => Category::active()
                ->select('id', 'name', 'code')
                ->with(['subcategories' => fn($q) => $q->where('is_active', true)
                    ->select('id', 'category_id', 'name')
                    ->orderBy('name')])
                ->orderBy('name')
                ->get()
                ->toArray(),

            'occurrence_types' => OccurrenceType::active()
                ->select('id', 'name', 'code', 'alert_level', 'sla_days')
                ->orderBy('name')
                ->get()
                ->toArray(),

            'provinces' => Province::active()
                ->select('id', 'name', 'code')
                ->orderBy('name')
                ->get()
                ->toArray(),
        ]);

        return response()->json($data, 200);
    }

    /**
     * Distritos de uma província.
     * ROTA: GET /api/public/provinces/{province}/districts
     *
     * Cache de 6 horas por província - distritos são dados geográficos estáticos.
     */
    public function districtsByProvince(Province $province): JsonResponse
    {
        $districts = Cache::remember("ref.districts.{$province->id}", 21600, fn() =>
            District::where('province_id', $province->id)
                ->where('is_active', true)
                ->select('id', 'name')
                ->orderBy('name')
                ->get()
                ->toArray()
        );

        return response()->json(['districts' => $districts], 200);
    }

    /**
     * Download / pré-visualização pública de um anexo.
     * Verifica que o anexo pertence à ocorrência com esse tracking_code.
     * ROTA: GET /api/public/occurrences/{code}/attachments/{attachmentId}
     */
    public function downloadAttachment(string $code, int $attachmentId): mixed
    {
        $occurrence = Occurrence::where('tracking_code', strtoupper($code))->first();

        if (!$occurrence) {
            return response()->json(['message' => 'Ocorrência não encontrada.'], 404);
        }

        $attachment = $occurrence->attachments()->find($attachmentId);

        if (!$attachment) {
            return response()->json(['message' => 'Anexo não encontrado.'], 404);
        }

        // Usa o disco guardado no registo do anexo - respeita a configuração do Laravel
        $disk = $attachment->disk ?? 'local';

        if (!Storage::disk($disk)->exists($attachment->path)) {
            return response()->json(['message' => 'Ficheiro não disponível.'], 404);
        }

        $fullPath = Storage::disk($disk)->path($attachment->path);

        // Imagens e PDFs abrem inline no browser; outros ficheiros fazem download
        $inlineTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'application/pdf'];
        $disposition = in_array($attachment->mime_type, $inlineTypes) ? 'inline' : 'attachment';
        $filename    = $attachment->original_name;

        return response()->file($fullPath, [
            'Content-Type'        => $attachment->mime_type,
            'Content-Disposition' => $disposition . '; filename="' . $filename . '"',
        ]);
    }
}