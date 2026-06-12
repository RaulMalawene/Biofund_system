<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Adiciona o valor 'observador' ao ENUM de role da tabela users.
     */
    public function up(): void
    {
        DB::statement("
            ALTER TABLE users
            MODIFY COLUMN role
            ENUM('admin','gestor','funcionario','observador')
            NOT NULL
        ");
    }

    public function down(): void
    {
        DB::statement("UPDATE users SET role = 'funcionario' WHERE role = 'observador'");

        DB::statement("
            ALTER TABLE users
            MODIFY COLUMN role
            ENUM('admin','gestor','funcionario')
            NOT NULL
        ");
    }
};
