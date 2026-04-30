<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabela única para todos os utilizadores internos:
     * - admin
     * - gestor
     * - funcionario (utilizador interno)
     *
     * O utilizador externo (sem login) NÃO tem registo aqui.
     * A área de gestão pode ser 'national' (sem province_id)
     * ou provincial (com province_id obrigatório).
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            // Identificação
            $table->string('name', 150);
            $table->string('email', 150)->unique();
            $table->string('phone', 30)->nullable();
            $table->string('password');

            // Perfil de acesso
            $table->enum('role', ['admin', 'gestor', 'funcionario']);

            // Área de gestão geográfica
            $table->enum('management_scope', ['national', 'provincial'])->default('national');
            $table->foreignId('province_id')
                  ->nullable()
                  ->constrained('provinces')
                  ->onDelete('restrict');

            // Configuração de alertas
            $table->boolean('receives_urgent_alerts')->default(false);
            $table->boolean('receives_gbv_alerts')->default(false);

            // Estado da conta
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_login_at')->nullable();

            // Quem criou este utilizador (sempre o admin)
            $table->foreignId('created_by')
                  ->nullable()
                  ->constrained('users')
                  ->onDelete('set null');

            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};