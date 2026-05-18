<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'name'       => $this->name,
            'email'      => $this->email,
            'phone'      => $this->phone,
            'role'       => $this->role->value,
            'role_label' => $this->role->label(),

            'management_scope' => $this->management_scope,

            // Província principal (retrocompatibilidade)
            'province' => $this->whenLoaded('province', fn() => [
                'id'   => $this->province->id,
                'name' => $this->province->name,
            ]),

            // Todas as províncias (array — gestores podem ter várias)
            'provinces' => $this->whenLoaded('provinces', fn() =>
                $this->provinces->map(fn($p) => [
                    'id'   => $p->id,
                    'name' => $p->name,
                ])
            ),

            'projects' => $this->whenLoaded('projects', fn() =>
                $this->projects->map(fn($p) => [
                    'id'   => $p->id,
                    'name' => $p->name,
                    'code' => $p->code,
                ])
            ),

            'receives_urgent_alerts' => $this->receives_urgent_alerts,
            'receives_gbv_alerts'    => $this->receives_gbv_alerts,
            'is_active'              => $this->is_active,
            'last_login_at'          => $this->last_login_at?->format('d/m/Y H:i'),

            'created_by' => $this->whenLoaded('createdBy', fn() => [
                'id'   => $this->createdBy->id,
                'name' => $this->createdBy->name,
            ]),

            'assigned_occurrences_count'  => $this->whenNotNull($this->assigned_occurrences_count ?? null),
            'submitted_occurrences_count' => $this->whenNotNull($this->submitted_occurrences_count ?? null),
            'created_at' => $this->created_at->format('d/m/Y'),
        ];
    }
}