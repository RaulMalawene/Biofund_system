<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Adiciona o campo occurrence_origin ("Origem da ocorrência") à tabela occurrences.
 *
 * Substitui o campo "Assunto" nos formulários de registo interno
 * (admin, gestor e funcionário interno). É texto livre e não deve ser
 * confundido com `origin` (external/internal), que indica o canal de entrada.
 *
 * Nullable porque as ocorrências antigas e as do formulário público não o têm.
 * A coluna `subject` mantém-se para preservar os registos existentes.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('occurrences', function (Blueprint $table) {
            $table->string('occurrence_origin', 255)->nullable()->after('subject');
        });
    }

    public function down(): void
    {
        Schema::table('occurrences', function (Blueprint $table) {
            $table->dropColumn('occurrence_origin');
        });
    }
};
