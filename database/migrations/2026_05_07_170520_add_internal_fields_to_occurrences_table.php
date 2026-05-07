<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Adiciona dois campos exclusivos para registos internos:
     *   - submission_channel → como a ocorrência chegou ao sistema
     *   - alert_type         → nível de alerta definido pelo utilizador interno
     *
     * Ambos são nullable na BD pois ocorrências externas (formulário público)
     * não preenchem estes campos. A validação obrigatória é feita no
     * StoreInternalOccurrenceRequest.
     */
    public function up(): void
    {
        Schema::table('occurrences', function (Blueprint $table) {
            $table->string('submission_channel')->nullable()->after('occurrence_date');
            $table->string('alert_type')->nullable()->after('submission_channel');
        });
    }

    public function down(): void
    {
        Schema::table('occurrences', function (Blueprint $table) {
            $table->dropColumn(['submission_channel', 'alert_type']);
        });
    }
};
