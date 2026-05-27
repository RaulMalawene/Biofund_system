<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Índice composto para a query mensal do dashboard:
        // WHERE created_at >= ? GROUP BY YEAR(created_at), MONTH(created_at)
        try {
            Schema::table('occurrences', function (Blueprint $table) {
                $table->index(['status', 'created_at'], 'occ_status_created_at_idx');
            });
        } catch (\Exception $e) {}

        // Índice composto para filtros de gestor que combinam province + project
        try {
            Schema::table('occurrences', function (Blueprint $table) {
                $table->index(['project_id', 'status'], 'occ_project_status_idx');
            });
        } catch (\Exception $e) {}

        // Índice composto para admin user list: WHERE role != 'admin' ORDER BY name
        try {
            Schema::table('users', function (Blueprint $table) {
                $table->index(['role', 'is_active'], 'users_role_active_idx');
            });
        } catch (\Exception $e) {}

        // Índice composto na tabela pivot user_provinces para o eager-load do gestor
        try {
            Schema::table('user_provinces', function (Blueprint $table) {
                $table->index('user_id', 'user_prov_user_id_idx');
            });
        } catch (\Exception $e) {}

        // Índice composto na tabela pivot user_projects para o eager-load do utilizador
        try {
            Schema::table('user_projects', function (Blueprint $table) {
                $table->index('user_id', 'user_proj_user_id_idx');
            });
        } catch (\Exception $e) {}
    }

    public function down(): void
    {
        Schema::table('occurrences', function (Blueprint $table) {
            $table->dropIndex('occ_status_created_at_idx');
            $table->dropIndex('occ_project_status_idx');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('users_role_active_idx');
        });

        Schema::table('user_provinces', function (Blueprint $table) {
            $table->dropIndex('user_prov_user_id_idx');
        });

        Schema::table('user_projects', function (Blueprint $table) {
            $table->dropIndex('user_proj_user_id_idx');
        });
    }
};
