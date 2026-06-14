<?php

namespace Database\Seeders;

use App\Models\OccurrenceType;
use App\Models\Project;
use Illuminate\Database\Seeder;

/**
 * OccurrenceTypeSeeder
 *
 * Popula os tipos de ocorrência pré-definidos.
 * O SLA (prazo em dias) é definido por tipo e nível de alerta:
 *   - normal  → 15 dias
 *   - urgent  → 5 dias
 *   - gbv     → 3 dias (prioridade máxima)
 */
class OccurrenceTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            [
                'code'        => 'REC',
                'name'        => 'Reclamação',
                'alert_level' => 'normal',
                'sla_days'    => 15,
            ],
            [
                'code'        => 'CON',
                'name'        => 'Consulta',
                'alert_level' => 'normal',
                'sla_days'    => 10,
            ],
            [
                'code'        => 'SUG',
                'name'        => 'Sugestão',
                'alert_level' => 'normal',
                'sla_days'    => 20,
            ],
            [
                'code'        => 'ELO',
                'name'        => 'Elogio',
                'alert_level' => 'normal',
                'sla_days'    => 30,
            ],
            [
                'code'        => 'URG',
                'name'        => 'Reclamação Urgente',
                'alert_level' => 'urgent',
                'sla_days'    => 5,
                'is_active'   => false,
            ],
            [
                'code'        => 'GBV',
                'name'        => 'Violência Baseada no Género (VBG)',
                'alert_level' => 'gbv',
                'sla_days'    => 3,
            ],
        ];

        foreach ($types as $type) {
            OccurrenceType::updateOrCreate(
                ['code' => $type['code']],
                [
                    'name'        => $type['name'],
                    'alert_level' => $type['alert_level'],
                    'sla_days'    => $type['sla_days'],
                    'is_active'   => $type['is_active'] ?? true,
                ]
            );
        }

        $this->command->info('✅ Tipos de ocorrência inseridos.');
    }
}