<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabela central do sistema.
     *
     * Origens possíveis:
     *  - 'external' : utilizador externo (sem login) - submissão pública
     *  - 'internal' : funcionário ou admin autenticado
     *
     * Estados do ciclo de vida:
     *  pending   → recebida, aguarda análise
     *  in_review → em análise pelo gestor/admin
     *  resolved  → resolvida (com comentário obrigatório)
     *  rejected  → rejeitada (com comentário obrigatório)
     *  closed    → encerrada após confirmação/prazo
     */
    public function up(): void
    {
        Schema::create('occurrences', function (Blueprint $table) {
            $table->id();

            // Código único de seguimento (público)
            $table->string('tracking_code', 20)->unique();

            // Origem
            $table->enum('origin', ['external', 'internal'])->default('external');

            // Se origem = internal, guarda o ID do utilizador autenticado
            $table->foreignId('submitted_by_user_id')
                  ->nullable()
                  ->constrained('users')
                  ->onDelete('set null');

            // Dados do reclamante externo (preenchidos quando origin = external)
            $table->string('complainant_name', 150)->nullable();
            $table->string('complainant_email', 150)->nullable();
            $table->string('complainant_phone', 30)->nullable();

            // Classificação
            $table->foreignId('project_id')
                  ->constrained('projects')
                  ->onDelete('restrict');
            $table->foreignId('category_id')
                  ->constrained('categories')
                  ->onDelete('restrict');
            $table->foreignId('subcategory_id')
                  ->nullable()
                  ->constrained('subcategories')
                  ->onDelete('set null');
            $table->foreignId('occurrence_type_id')
                  ->constrained('occurrence_types')
                  ->onDelete('restrict');

            // Localização
            $table->foreignId('province_id')
                  ->constrained('provinces')
                  ->onDelete('restrict');
            $table->foreignId('district_id')
                  ->nullable()
                  ->constrained('districts')
                  ->onDelete('set null');
            $table->string('location_detail', 255)->nullable(); // detalhe livre

            // Conteúdo
            $table->string('subject', 255);
            $table->text('description');
            $table->date('occurrence_date')->nullable();

            // Estado e ciclo de vida
            $table->enum('status', ['pending', 'in_review', 'resolved', 'rejected', 'closed'])
                  ->default('pending');

            // Responsável atribuído (gestor/admin)
            $table->foreignId('assigned_to')
                  ->nullable()
                  ->constrained('users')
                  ->onDelete('set null');

            // Quem fez a última validação / rejeição
            $table->foreignId('reviewed_by')
                  ->nullable()
                  ->constrained('users')
                  ->onDelete('set null');
            $table->timestamp('reviewed_at')->nullable();

            // Prazo de resolução (calculado a partir do SLA do tipo)
            $table->date('due_date')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Índices
            $table->index('tracking_code');
            $table->index('status');
            $table->index('province_id');
            $table->index('project_id');
            $table->index('assigned_to');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('occurrences');
    }
};