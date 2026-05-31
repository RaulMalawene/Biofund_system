<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Torna occurrence_type_id e subject nullable na tabela occurrences.
 *
 * Motivo: o formulário público não exige estes campos - o reclamante
 * externo pode não saber qual o tipo exacto de ocorrência, e o assunto
 * é opcional. O gestor preenche / corrige estes valores após triagem.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('occurrences', function (Blueprint $table) {
            $table->dropForeign(['occurrence_type_id']);

            $table->unsignedBigInteger('occurrence_type_id')
                  ->nullable()
                  ->change();

            $table->foreign('occurrence_type_id')
                  ->references('id')
                  ->on('occurrence_types')
                  ->onDelete('restrict');

            $table->string('subject', 255)
                  ->nullable()
                  ->change();
        });
    }

    public function down(): void
    {
        Schema::table('occurrences', function (Blueprint $table) {
            $table->dropForeign(['occurrence_type_id']);

            $table->unsignedBigInteger('occurrence_type_id')
                  ->nullable(false)
                  ->change();

            $table->foreign('occurrence_type_id')
                  ->references('id')
                  ->on('occurrence_types')
                  ->onDelete('restrict');

            $table->string('subject', 255)
                  ->nullable(false)
                  ->change();
        });
    }
};
