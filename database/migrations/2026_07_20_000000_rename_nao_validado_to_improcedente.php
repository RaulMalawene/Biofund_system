<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Renomeia o valor 'nao_validado' para 'improcedente' nas colunas ENUM
     * de estado, seguindo o mesmo padrão (alargar → migrar dados → estreitar)
     * usado em 2026_05_21_000001_update_occurrence_status_enum_values.php.
     */
    public function up(): void
    {
        // occurrences.status
        DB::statement("
            ALTER TABLE occurrences
            MODIFY COLUMN status
            ENUM('por_validar','por_resolver','nao_validado','resolvendo','resolvido','nao_resolvida','improcedente')
            NOT NULL DEFAULT 'por_validar'
        ");
        DB::statement("UPDATE occurrences SET status = 'improcedente' WHERE status = 'nao_validado'");
        DB::statement("
            ALTER TABLE occurrences
            MODIFY COLUMN status
            ENUM('por_validar','por_resolver','improcedente','resolvendo','resolvido','nao_resolvida')
            NOT NULL DEFAULT 'por_validar'
        ");

        // occurrence_status_history.from_status
        DB::statement("
            ALTER TABLE occurrence_status_history
            MODIFY COLUMN from_status
            ENUM('por_validar','por_resolver','nao_validado','resolvendo','resolvido','improcedente')
            NULL
        ");
        DB::statement("UPDATE occurrence_status_history SET from_status = 'improcedente' WHERE from_status = 'nao_validado'");
        DB::statement("
            ALTER TABLE occurrence_status_history
            MODIFY COLUMN from_status
            ENUM('por_validar','por_resolver','improcedente','resolvendo','resolvido')
            NULL
        ");

        // occurrence_status_history.to_status
        DB::statement("
            ALTER TABLE occurrence_status_history
            MODIFY COLUMN to_status
            ENUM('por_validar','por_resolver','nao_validado','resolvendo','resolvido','improcedente')
            NOT NULL
        ");
        DB::statement("UPDATE occurrence_status_history SET to_status = 'improcedente' WHERE to_status = 'nao_validado'");
        DB::statement("
            ALTER TABLE occurrence_status_history
            MODIFY COLUMN to_status
            ENUM('por_validar','por_resolver','improcedente','resolvendo','resolvido')
            NOT NULL
        ");
    }

    public function down(): void
    {
        // occurrences.status
        DB::statement("
            ALTER TABLE occurrences
            MODIFY COLUMN status
            ENUM('por_validar','por_resolver','improcedente','resolvendo','resolvido','nao_resolvida','nao_validado')
            NOT NULL DEFAULT 'por_validar'
        ");
        DB::statement("UPDATE occurrences SET status = 'nao_validado' WHERE status = 'improcedente'");
        DB::statement("
            ALTER TABLE occurrences
            MODIFY COLUMN status
            ENUM('por_validar','por_resolver','nao_validado','resolvendo','resolvido','nao_resolvida')
            NOT NULL DEFAULT 'por_validar'
        ");

        // occurrence_status_history.from_status
        DB::statement("
            ALTER TABLE occurrence_status_history
            MODIFY COLUMN from_status
            ENUM('por_validar','por_resolver','improcedente','resolvendo','resolvido','nao_validado')
            NULL
        ");
        DB::statement("UPDATE occurrence_status_history SET from_status = 'nao_validado' WHERE from_status = 'improcedente'");
        DB::statement("
            ALTER TABLE occurrence_status_history
            MODIFY COLUMN from_status
            ENUM('por_validar','por_resolver','nao_validado','resolvendo','resolvido')
            NULL
        ");

        // occurrence_status_history.to_status
        DB::statement("
            ALTER TABLE occurrence_status_history
            MODIFY COLUMN to_status
            ENUM('por_validar','por_resolver','improcedente','resolvendo','resolvido','nao_validado')
            NOT NULL
        ");
        DB::statement("UPDATE occurrence_status_history SET to_status = 'nao_validado' WHERE to_status = 'improcedente'");
        DB::statement("
            ALTER TABLE occurrence_status_history
            MODIFY COLUMN to_status
            ENUM('por_validar','por_resolver','nao_validado','resolvendo','resolvido')
            NOT NULL
        ");
    }
};
