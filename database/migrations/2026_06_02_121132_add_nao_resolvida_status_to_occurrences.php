<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Adiciona 'nao_resolvida' à coluna status da tabela occurrences.
        // Usado pelo comando occurrences:mark-unresolved para marcar
        // automaticamente ocorrências sem actividade há mais de 5 dias.
        DB::statement("
            ALTER TABLE occurrences
            MODIFY COLUMN status
            ENUM('por_validar','por_resolver','nao_validado','resolvendo','resolvido','nao_resolvida')
            NOT NULL DEFAULT 'por_validar'
        ");

        // Mesma extensão para from_status (nullable) em occurrence_status_history
        DB::statement("
            ALTER TABLE occurrence_status_history
            MODIFY COLUMN from_status
            ENUM('por_validar','por_resolver','nao_validado','resolvendo','resolvido','nao_resolvida')
            NULL
        ");

        // E para to_status (not null) em occurrence_status_history
        DB::statement("
            ALTER TABLE occurrence_status_history
            MODIFY COLUMN to_status
            ENUM('por_validar','por_resolver','nao_validado','resolvendo','resolvido','nao_resolvida')
            NOT NULL
        ");
    }

    public function down(): void
    {
        // Remove 'nao_resolvida' — só seguro se não existirem registos com esse valor
        DB::statement("
            ALTER TABLE occurrences
            MODIFY COLUMN status
            ENUM('por_validar','por_resolver','nao_validado','resolvendo','resolvido')
            NOT NULL DEFAULT 'por_validar'
        ");

        DB::statement("
            ALTER TABLE occurrence_status_history
            MODIFY COLUMN from_status
            ENUM('por_validar','por_resolver','nao_validado','resolvendo','resolvido')
            NULL
        ");

        DB::statement("
            ALTER TABLE occurrence_status_history
            MODIFY COLUMN to_status
            ENUM('por_validar','por_resolver','nao_validado','resolvendo','resolvido')
            NOT NULL
        ");
    }
};
