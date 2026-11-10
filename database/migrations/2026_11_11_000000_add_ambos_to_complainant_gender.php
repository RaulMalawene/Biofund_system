<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Acrescenta a opção "ambos" ao campo complainant_gender (Sexo do utente).
 *
 * Só acrescenta um valor ao ENUM existente; os dados actuais não são alterados.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE occurrences MODIFY complainant_gender ENUM('masculino', 'feminino', 'ambos') NULL");
    }

    public function down(): void
    {
        // O valor "ambos" deixa de existir: essas ocorrências ficam sem sexo indicado.
        DB::table('occurrences')->where('complainant_gender', 'ambos')->update(['complainant_gender' => null]);
        DB::statement("ALTER TABLE occurrences MODIFY complainant_gender ENUM('masculino', 'feminino') NULL");
    }
};
