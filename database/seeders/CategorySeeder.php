<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\OccurrenceType;
use App\Models\Project;
use App\Models\Subcategory;
use Illuminate\Database\Seeder;

/**
 * CategorySeeder
 *
 * Popula as categorias e subcategorias pré-definidas do sistema.
 * Estas categorias reflectem as áreas de actuação dos projectos
 * FNDS/BIOFUND conforme definido no Manual do Administrador.
 */
class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'code' => 'AMB',
                'name' => 'Ambiental',
                'subcategories' => [
                    'Desflorestação', 'Poluição de Água', 'Queimadas',
                    'Degradação do Solo', 'Caça Furtiva', 'Pesca Ilegal',
                ],
            ],
            [
                'code' => 'AC',
                'name' => 'Área de Conservação',
                'subcategories' => [
                    'Invasão de Área Protegida', 'Conflito Humano-Fauna',
                    'Extracção Ilegal de Recursos', 'Gestão de Parques',
                ],
            ],
            [
                'code' => 'DP',
                'name' => 'Desempenho de Projecto',
                'subcategories' => [
                    'Atraso na Execução', 'Qualidade das Obras',
                    'Falta de Transparência', 'Desvio de Fundos',
                    'Incumprimento de Contrato',
                ],
            ],
            [
                'code' => 'GBV',
                'name' => 'GBV - Violência Baseada no Género',
                'subcategories' => [
                    'Violência Doméstica', 'Assédio Sexual',
                    'Exploração Sexual', 'Casamento Prematuro',
                    'Violência Psicológica',
                ],
            ],
            [
                'code' => 'SOC',
                'name' => 'Social',
                'subcategories' => [
                    'Reassentamento Involuntário', 'Perda de Meios de Subsistência',
                    'Acesso a Serviços Básicos', 'Discriminação',
                    'Conflito Comunitário',
                ],
            ],
        ];

        foreach ($categories as $catData) {
            $category = Category::updateOrCreate(
                ['code' => $catData['code']],
                ['name' => $catData['name'], 'is_active' => true]
            );

            foreach ($catData['subcategories'] as $subName) {
                Subcategory::updateOrCreate(
                    ['name' => $subName, 'category_id' => $category->id],
                    ['is_active' => true]
                );
            }
        }

        $this->command->info('✅ Categorias e subcategorias inseridas.');
    }
}