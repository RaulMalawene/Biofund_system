<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Adiciona o campo icon à tabela projects.
 * Suporta os ícones visuais da interface de gestão de projectos:
 *   nature, tree, water, fire, drop, person, leaf, fish
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->string('icon', 20)->nullable()->default('nature')->after('description');
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('icon');
        });
    }
};