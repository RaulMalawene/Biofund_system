<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * DatabaseSeeder
 *
 * Orquestra a execução de todos os seeders na ordem correcta,
 * respeitando as dependências entre tabelas (foreign keys).
 *
 * Ordem de execução:
 *   1. ProvinceSeeder      → províncias + distritos (sem dependências)
 *   2. CategorySeeder      → categorias + subcategorias (sem dependências)
 *   3. OccurrenceTypeSeeder → tipos de ocorrência (sem dependências)
 *   4. ProjectSeeder        → projectos (sem dependências)
 *   5. AdminSeeder          → admin padrão (depende de provinces para province_id=null)
 *
 * Para executar: php artisan db:seed
 * Para executar individualmente: php artisan db:seed --class=AdminSeeder
 * Para recomeçar do zero: php artisan migrate:fresh --seed
 */
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('🚀 A iniciar os seeders do MDR...');
        $this->command->newLine();

        $this->call([
            ProvinceSeeder::class,
            CategorySeeder::class,
            OccurrenceTypeSeeder::class,
            ProjectSeeder::class,
            AdminSeeder::class,
        ]);

        $this->command->newLine();
        $this->command->info('✅ Base de dados inicializada com sucesso!');
        $this->command->table(
            ['Credencial', 'Valor'],
            [
                ['Email',   'admin@mdr.biofund.org.mz'],
                ['Senha',   'Admin@MDR2024'],
                ['⚠️  Nota', 'Altera a senha após o primeiro login!'],
            ]
        );
    }
}
