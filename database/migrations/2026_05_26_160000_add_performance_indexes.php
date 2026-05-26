<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Colunas de filtro frequente em occurrences sem índice
        Schema::table('occurrences', function (Blueprint $table) {
            $table->index('alert_type',          'occ_alert_type_idx');
            $table->index('submission_channel',  'occ_submission_channel_idx');

            // Combinações mais comuns nos filtros de gestor/admin
            $table->index(['province_id', 'status'], 'occ_province_status_idx');

            // Queries de SLA e ocorrências em atraso
            $table->index(['status', 'due_date'], 'occ_status_due_date_idx');
        });

        // Scope active() usado em todas as listagens públicas e de parametrização
        Schema::table('categories', function (Blueprint $table) {
            $table->index('is_active', 'cat_is_active_idx');
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->index('is_active', 'proj_is_active_idx');
        });

        Schema::table('subcategories', function (Blueprint $table) {
            $table->index('is_active', 'subcat_is_active_idx');
        });

        Schema::table('districts', function (Blueprint $table) {
            $table->index('is_active', 'dist_is_active_idx');
        });

        Schema::table('occurrence_types', function (Blueprint $table) {
            $table->index('is_active', 'oct_is_active_idx');
        });

        // Ordenação de notificações por data
        Schema::table('notifications_log', function (Blueprint $table) {
            $table->index('created_at', 'notif_created_at_idx');
        });

        // Carregamento ordenado do histórico de uma ocorrência
        Schema::table('occurrence_status_history', function (Blueprint $table) {
            $table->index(['occurrence_id', 'changed_at'], 'osh_occurrence_changed_at_idx');
        });
    }

    public function down(): void
    {
        Schema::table('occurrences', function (Blueprint $table) {
            $table->dropIndex('occ_alert_type_idx');
            $table->dropIndex('occ_submission_channel_idx');
            $table->dropIndex('occ_province_status_idx');
            $table->dropIndex('occ_status_due_date_idx');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->dropIndex('cat_is_active_idx');
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->dropIndex('proj_is_active_idx');
        });

        Schema::table('subcategories', function (Blueprint $table) {
            $table->dropIndex('subcat_is_active_idx');
        });

        Schema::table('districts', function (Blueprint $table) {
            $table->dropIndex('dist_is_active_idx');
        });

        Schema::table('occurrence_types', function (Blueprint $table) {
            $table->dropIndex('oct_is_active_idx');
        });

        Schema::table('notifications_log', function (Blueprint $table) {
            $table->dropIndex('notif_created_at_idx');
        });

        Schema::table('occurrence_status_history', function (Blueprint $table) {
            $table->dropIndex('osh_occurrence_changed_at_idx');
        });
    }
};
