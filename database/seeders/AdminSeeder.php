<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * AdminSeeder
 *
 * Cria a conta de administrador padrão do sistema.
 *
 * ⚠️  IMPORTANTE: Altera a senha padrão após o primeiro login!
 *
 * Este seeder usa updateOrCreate para ser idempotente -
 * pode correr várias vezes sem criar duplicados.
 *
 * Credenciais padrão (alterar em produção):
 *   Email: admin@mdr.biofund.org.mz
 *   Senha: Admin@MDR2024
 */
class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@mdr.biofund.org.mz'],
            [
                'name'                   => 'Administrador MDR',
                'phone'                  => '+258 21 000 000',
                'password'               => 'Admin@MDR2024',
                'role'                   => 'admin',
                'management_scope'       => 'national',   // admin vê tudo
                'province_id'            => null,
                'receives_urgent_alerts' => true,
                'receives_gbv_alerts'    => true,
                'is_active'              => true,
                'created_by'             => null,
            ]
        );

        $this->command->info('✅ Administrador padrão criado.');
        $this->command->warn('⚠️  Lembra-te de alterar a senha padrão após o primeiro login!');
    }
}