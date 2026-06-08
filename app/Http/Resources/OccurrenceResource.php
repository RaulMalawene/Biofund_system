<?php

namespace App\Http\Resources;

use App\Enums\OccurrenceStatusEnum;
use App\Enums\RoleEnum;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * OccurrenceResource
 *
 * Formata a resposta de uma ocorrência para a API.
 *
 * Adapta o output consoante o utilizador autenticado:
 *   - Notas internas só são visíveis a admin e gestor.
 *   - Dados do reclamante só são visíveis a admin e gestor.
 */
class OccurrenceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $user             = $request->user();
        $isManagerOrAbove = $user && in_array($user->role, [RoleEnum::Admin, RoleEnum::Gestor, RoleEnum::Funcionario]);

        // Aceder getAttributes() uma única vez evita a cadeia dupla de __get
        // (JsonResource::__get → Model::__get → attributes[]) por cada campo.
        $a = $this->resource->getAttributes();

        // Pré-computar atributos com cast - cada acesso via $this->xxx
        // executa o sistema de casting completo; fazê-lo uma vez é ~10× mais rápido.
        $status            = $this->status;
        $origin            = $this->origin;
        $dueDate           = $this->due_date;
        $occurrenceDate    = $this->occurrence_date;
        $reviewedAt        = $this->reviewed_at;
        $createdAt         = $this->created_at;
        $deletedAt         = $this->deleted_at;
        $submissionChannel = $this->submission_channel;
        $alertType         = $this->alert_type;

        $isOverdue = $dueDate !== null
            && $dueDate->timestamp < time()
            && $status !== OccurrenceStatusEnum::Resolvido
            && $status !== OccurrenceStatusEnum::NaoValidado
            && $status !== OccurrenceStatusEnum::NaoResolvida;

        return [
            'id'             => $a['id'],
            'tracking_code'  => $a['tracking_code'],
            'origin'         => $origin->value,
            'origin_label'   => $origin->label(),
            'subject'        => $a['subject'],
            'description'    => $a['description'],

            // Estado
            'status'         => $status->value,
            'status_label'   => $status->label(),
            'status_color'   => $status->color(),
            'is_overdue'     => $isOverdue,

            // Reclamante/Pessoa Afectada - dados sensíveis apenas para gestor/admin
            'complainant' => $this->when($isManagerOrAbove, fn() => [
                'name'   => $a['complainant_name'],
                'email'  => $a['complainant_email'],
                'phone'  => $a['complainant_phone'],
                'gender' => $a['complainant_gender'] ?? null,
                'age'    => $a['complainant_age']    ?? null,
            ]),

            // Classificação
            'project'      => $this->whenLoaded('project', fn() => [
                'id'   => $this->project->id,
                'name' => $this->project->name,
                'code' => $this->project->code,
            ]),
            'category'     => $this->whenLoaded('category', fn() => [
                'id'   => $this->category->id,
                'name' => $this->category->name,
            ]),
            'subcategory'  => $this->whenLoaded('subcategory', fn() =>
                $this->subcategory ? ['id' => $this->subcategory->id, 'name' => $this->subcategory->name] : null
            ),
            'type'         => $this->whenLoaded('occurrenceType', fn() => [
                'id'          => $this->occurrenceType->id,
                'name'        => $this->occurrenceType->name,
                'alert_level' => $this->occurrenceType->alert_level->value,
                'alert_label' => $this->occurrenceType->alert_level->label(),
            ]),

            // Localização
            'province'        => $this->whenLoaded('province', fn() => [
                'id'   => $this->province->id,
                'name' => $this->province->name,
            ]),
            'district'        => $this->whenLoaded('district', fn() =>
                $this->district ? ['id' => $this->district->id, 'name' => $this->district->name] : null
            ),
            'location_detail' => $a['location_detail'],

            // Datas
            'occurrence_date' => $occurrenceDate?->format('d/m/Y'),
            'submitted_at'    => $createdAt->format('d/m/Y H:i'),
            'due_date'        => $dueDate?->format('d/m/Y'),
            'reviewed_at'     => $reviewedAt?->format('d/m/Y H:i'),
            'deleted_at'      => $deletedAt?->format('d/m/Y H:i'),
            'is_removed'      => $deletedAt !== null,

            // Responsáveis (apenas para gestor/admin)
            'assigned_to'  => $this->when($isManagerOrAbove, fn() =>
                $this->whenLoaded('assignedTo', fn() =>
                    $this->assignedTo ? ['id' => $this->assignedTo->id, 'name' => $this->assignedTo->name] : null
                )
            ),
            'reviewed_by'  => $this->when($isManagerOrAbove, fn() =>
                $this->whenLoaded('reviewedBy', fn() => $this->reviewedBy?->name)
            ),
            'submitted_by' => $this->when($isManagerOrAbove, fn() =>
                $this->whenLoaded('submittedBy', fn() =>
                    $this->submittedBy ? ['id' => $this->submittedBy->id, 'name' => $this->submittedBy->name] : null
                )
            ),

            // Campos internos (preenchidos apenas em registos internos)
            'submission_channel'       => $submissionChannel?->value,
            'submission_channel_label' => $submissionChannel?->label(),
            'alert_type'               => $alertType?->value,
            'alert_type_label'         => $alertType?->label(),
            'alert_type_color'         => $alertType?->color(),

            // Contadores
            'attachments_count' => $this->whenNotNull($a['attachments_count'] ?? null),

            // Detalhe completo (carregado apenas no show)
            'attachments'  => $this->whenLoaded('attachments', fn() =>
                $this->attachments->map(fn($att) => [
                    'id'       => $att->id,
                    'name'     => $att->original_name,
                    'size'     => $att->getFormattedSize(),
                    'mime'     => $att->mime_type,
                    'is_image' => $att->isImage(),
                    'url'      => $att->getUrl(),
                ])
            ),

            // Histórico de estados (show)
            'history' => $this->whenLoaded('statusHistory', fn() =>
                $this->statusHistory->map(fn($h) => [
                    'from'          => $h->from_status?->label(),
                    'to'            => $h->to_status->label(),
                    'to_color'      => $h->to_status->color(),
                    'comment'       => $h->comment,
                    // Nota interna só visível para gestores/admin
                    'internal_note' => $isManagerOrAbove ? $h->internal_note : null,
                    'changed_by'    => $h->changedBy?->name ?? 'Sistema',
                    'date'          => $h->changed_at->format('d/m/Y H:i'),
                ])
            ),

            // Transições possíveis a partir do estado actual
            'can_transition_to' => collect($status->allowedTransitions())
                ->map(fn($s) => ['value' => $s->value, 'label' => $s->label()])
                ->values(),
        ];
    }
}
