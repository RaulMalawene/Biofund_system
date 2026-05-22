<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Actualiza os valores do ENUM de estado das ocorrências.
     *
     * Mapeamento:
     *   pending   → por_validar
     *   in_review → por_resolver
     *   rejected  → nao_validado
     *   resolved  → resolvido
     *   closed    → resolvido  (estado removido, tratado como resolvido)
     *
     * Novo estado adicionado: resolvendo
     *
     * Ordem: expandir ENUM (aceitar ambos) → migrar dados → restringir ENUM (só novos)
     */
    public function up(): void
    {
        // 1. Expandir enum para aceitar valores antigos E novos simultaneamente
        DB::statement("
            ALTER TABLE occurrences
            MODIFY COLUMN status
            ENUM('pending','in_review','resolved','rejected','closed','por_validar','por_resolver','nao_validado','resolvendo','resolvido')
            NOT NULL DEFAULT 'por_validar'
        ");

        // 2. Migrar dados na tabela occurrences
        DB::statement("UPDATE occurrences SET status = 'por_validar'  WHERE status = 'pending'");
        DB::statement("UPDATE occurrences SET status = 'por_resolver' WHERE status = 'in_review'");
        DB::statement("UPDATE occurrences SET status = 'nao_validado' WHERE status = 'rejected'");
        DB::statement("UPDATE occurrences SET status = 'resolvido'    WHERE status IN ('resolved', 'closed')");

        // 3. Restringir enum apenas aos novos valores
        DB::statement("
            ALTER TABLE occurrences
            MODIFY COLUMN status
            ENUM('por_validar','por_resolver','nao_validado','resolvendo','resolvido')
            NOT NULL DEFAULT 'por_validar'
        ");

        // 4. Expandir enums em occurrence_status_history
        DB::statement("
            ALTER TABLE occurrence_status_history
            MODIFY COLUMN from_status
            ENUM('pending','in_review','resolved','rejected','closed','por_validar','por_resolver','nao_validado','resolvendo','resolvido')
            NULL
        ");
        DB::statement("
            ALTER TABLE occurrence_status_history
            MODIFY COLUMN to_status
            ENUM('pending','in_review','resolved','rejected','closed','por_validar','por_resolver','nao_validado','resolvendo','resolvido')
            NOT NULL
        ");

        // 5. Migrar dados em occurrence_status_history
        DB::statement("UPDATE occurrence_status_history SET from_status = 'por_validar'  WHERE from_status = 'pending'");
        DB::statement("UPDATE occurrence_status_history SET from_status = 'por_resolver' WHERE from_status = 'in_review'");
        DB::statement("UPDATE occurrence_status_history SET from_status = 'nao_validado' WHERE from_status = 'rejected'");
        DB::statement("UPDATE occurrence_status_history SET from_status = 'resolvido'    WHERE from_status IN ('resolved', 'closed')");

        DB::statement("UPDATE occurrence_status_history SET to_status = 'por_validar'  WHERE to_status = 'pending'");
        DB::statement("UPDATE occurrence_status_history SET to_status = 'por_resolver' WHERE to_status = 'in_review'");
        DB::statement("UPDATE occurrence_status_history SET to_status = 'nao_validado' WHERE to_status = 'rejected'");
        DB::statement("UPDATE occurrence_status_history SET to_status = 'resolvido'    WHERE to_status IN ('resolved', 'closed')");

        // 6. Restringir enums em occurrence_status_history apenas aos novos valores
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

    public function down(): void
    {
        // Expandir para aceitar ambos antes de migrar de volta
        DB::statement("
            ALTER TABLE occurrences
            MODIFY COLUMN status
            ENUM('pending','in_review','resolved','rejected','closed','por_validar','por_resolver','nao_validado','resolvendo','resolvido')
            NOT NULL DEFAULT 'pending'
        ");

        DB::statement("UPDATE occurrences SET status = 'pending'   WHERE status = 'por_validar'");
        DB::statement("UPDATE occurrences SET status = 'in_review' WHERE status = 'por_resolver'");
        DB::statement("UPDATE occurrences SET status = 'rejected'  WHERE status = 'nao_validado'");
        DB::statement("UPDATE occurrences SET status = 'resolved'  WHERE status IN ('resolvendo', 'resolvido')");

        DB::statement("
            ALTER TABLE occurrences
            MODIFY COLUMN status
            ENUM('pending','in_review','resolved','rejected','closed')
            NOT NULL DEFAULT 'pending'
        ");

        DB::statement("
            ALTER TABLE occurrence_status_history
            MODIFY COLUMN from_status
            ENUM('pending','in_review','resolved','rejected','closed','por_validar','por_resolver','nao_validado','resolvendo','resolvido')
            NULL
        ");
        DB::statement("
            ALTER TABLE occurrence_status_history
            MODIFY COLUMN to_status
            ENUM('pending','in_review','resolved','rejected','closed','por_validar','por_resolver','nao_validado','resolvendo','resolvido')
            NOT NULL
        ");

        DB::statement("UPDATE occurrence_status_history SET from_status = 'pending'   WHERE from_status = 'por_validar'");
        DB::statement("UPDATE occurrence_status_history SET from_status = 'in_review' WHERE from_status = 'por_resolver'");
        DB::statement("UPDATE occurrence_status_history SET from_status = 'rejected'  WHERE from_status = 'nao_validado'");
        DB::statement("UPDATE occurrence_status_history SET from_status = 'resolved'  WHERE from_status IN ('resolvendo', 'resolvido')");

        DB::statement("UPDATE occurrence_status_history SET to_status = 'pending'   WHERE to_status = 'por_validar'");
        DB::statement("UPDATE occurrence_status_history SET to_status = 'in_review' WHERE to_status = 'por_resolver'");
        DB::statement("UPDATE occurrence_status_history SET to_status = 'rejected'  WHERE to_status = 'nao_validado'");
        DB::statement("UPDATE occurrence_status_history SET to_status = 'resolved'  WHERE to_status IN ('resolvendo', 'resolvido')");

        DB::statement("
            ALTER TABLE occurrence_status_history
            MODIFY COLUMN from_status
            ENUM('pending','in_review','resolved','rejected','closed')
            NULL
        ");
        DB::statement("
            ALTER TABLE occurrence_status_history
            MODIFY COLUMN to_status
            ENUM('pending','in_review','resolved','rejected','closed')
            NOT NULL
        ");
    }
};
