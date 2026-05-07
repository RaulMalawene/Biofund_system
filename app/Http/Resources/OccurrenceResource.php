<?php

namespace App\Http\Resources;

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
        $user              = $request->user();
        $isManagerOrAbove  = $user && in_array($user->role, [RoleEnum::Admin, RoleEnum::Gestor]);

        return [
            'id'             => $this->id,
            'tracking_code'  => $this->tracking_code,
            'origin'         => $this->origin->value,
            'origin_label'   => $this->origin->label(),
            'subject'        => $this->subject,
            'description'    => $this->description,

            // Estado
            'status'         => $this->status->value,
            'status_label'   => $this->status->label(),
            'status_color'   => $this->status->color(),
            'is_overdue'     => $this->isOverdue(),

            // Reclamante — dados sensíveis apenas para gestor/admin
            'complainant' => $this->when($isManagerOrAbove, fn() => [
                'name'  => $this->complainant_name,
                'email' => $this->isExternal()
                    ? $this->attributes['complainant_email'] ?? null
                    : $this->submittedBy?->email,
                'phone' => $this->isExternal()
                    ? $this->complainant_phone
                    : $this->submittedBy?->phone,
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
            'location_detail' => $this->location_detail,

            // Datas
            'occurrence_date' => $this->occurrence_date?->format('d/m/Y'),
            'submitted_at'    => $this->created_at->format('d/m/Y H:i'),
            'due_date'        => $this->due_date?->format('d/m/Y'),
            'reviewed_at'     => $this->reviewed_at?->format('d/m/Y H:i'),

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
            'submission_channel' => $this->submission_channel?->value,
            'submission_channel_label' => $this->submission_channel?->label(),
            'alert_type'         => $this->alert_type?->value,
            'alert_type_label'   => $this->alert_type?->label(),
            'alert_type_color'   => $this->alert_type?->color(),

            // Contadores
            'attachments_count' => $this->whenNotNull($this->attachments_count ?? null),

            // Detalhe completo (carregado apenas no show)
            'attachments'  => $this->whenLoaded('attachments', fn() =>
                $this->attachments->map(fn($a) => [
                    'id'       => $a->id,
                    'name'     => $a->original_name,
                    'size'     => $a->getFormattedSize(),
                    'mime'     => $a->mime_type,
                    'is_image' => $a->isImage(),
                    'url'      => $a->getUrl(),
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
            'can_transition_to' => collect($this->status->allowedTransitions())
                ->map(fn($s) => ['value' => $s->value, 'label' => $s->label()])
                ->values(),
        ];
    }
}