<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Adiciona campos visuais e descritivos à tabela categories.
 * Estes campos suportam a interface de gestão de categorias:
 *   - description : texto livre que descreve a categoria
 *   - icon        : chave do ícone SVG (fauna, flora, agua, fogo, pesca, lixo, ar, caca)
 *   - color       : cor hexadecimal para destaque visual (#52B788)
 *   - tags        : JSON array de strings para pesquisa e filtragem
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->text('description')->nullable()->after('name');
            $table->string('icon', 20)->nullable()->after('description');
            $table->string('color', 7)->nullable()->after('icon');   // ex: #52B788
            $table->json('tags')->nullable()->after('color');
        });
    }

    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn(['description', 'icon', 'color', 'tags']);
        });
    }
};