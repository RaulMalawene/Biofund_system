<?php

namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Database\Seeder;

/**
 * ProjectSeeder
 *
 * Popula os projectos iniciais do FNDS/BIOFUND.
 * Novos projectos podem ser adicionados pelo administrador via painel.
 */
class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        $projects = [
            [
                'code' => 'FNDS-001',
                'name' => 'Fundo Nacional de Desenvolvimento Sustentável',
                'description' => 'Projecto principal de gestão ambiental e social financiado pelo Banco Mundial.',
            ],
            [
                'code' => 'BIOFUND-001',
                'name' => 'BIOFUND - Fundação para a Conservação da Biodiversidade',
                'description' => 'Gestão e conservação da biodiversidade em Moçambique.',
            ],
            [
                'code' => 'RESILIM-001',
                'name' => 'Projecto de Resiliência Climática',
                'description' => 'Apoio às comunidades rurais vulneráveis às alterações climáticas.',
            ],
        ];

        foreach ($projects as $project) {
            Project::updateOrCreate(
                ['code' => $project['code']],
                [
                    'name'        => $project['name'],
                    'description' => $project['description'],
                    'is_active'   => true,
                ]
            );
        }

        $this->command->info('✅ Projectos inseridos.');
    }
}