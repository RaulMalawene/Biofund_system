<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $a           = $this->resource->getAttributes();
        $role        = $this->role;
        $lastLoginAt = $this->last_login_at;
        $createdAt   = $this->created_at;

        return [
            'id'         => $a['id'],
            'name'       => $a['name'],
            'email'      => $a['email'],
            'phone'      => $a['phone'],
            'role'       => $role->value,
            'role_label' => $role->label(),

            'management_scope' => $a['management_scope'],

            'province' => $this->whenLoaded('province', fn() => [
                'id'   => $this->province->id,
                'name' => $this->province->name,
            ]),

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

            'receives_urgent_alerts' => $a['receives_urgent_alerts'],
            'receives_gbv_alerts'    => $a['receives_gbv_alerts'],
            'is_active'              => $a['is_active'],
            'last_login_at'          => $lastLoginAt?->format('d/m/Y H:i'),

            'created_by' => $this->whenLoaded('createdBy', fn() => [
                'id'   => $this->createdBy->id,
                'name' => $this->createdBy->name,
            ]),

            'assigned_occurrences_count'  => $this->whenNotNull($a['assigned_occurrences_count'] ?? null),
            'submitted_occurrences_count' => $this->whenNotNull($a['submitted_occurrences_count'] ?? null),
            'created_at' => $createdAt->format('d/m/Y'),
        ];
    }
}