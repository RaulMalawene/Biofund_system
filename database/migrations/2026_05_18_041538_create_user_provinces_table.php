<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Cria a tabela pivot user_provinces.
 *
 * Permite que um gestor seja associado a múltiplas províncias.
 * Para funcionários (que têm apenas uma província), apenas um registo
 * é inserido nesta tabela (correspondente ao province_id da tabela users).
 *
 * A coluna province_id na tabela users mantém-se como "província principal"
 * para retrocompatibilidade com queries existentes.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_provinces', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->cascadeOnDelete();
            $table->foreignId('province_id')
                  ->constrained('provinces')
                  ->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['user_id', 'province_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_provinces');
    }
};