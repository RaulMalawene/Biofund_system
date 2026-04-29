<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Histórico completo de mudanças de estado de cada ocorrência.
     * Cada vez que o status muda, um registo é inserido aqui.
     * O comentário é OBRIGATÓRIO quando status = resolved ou rejected.
     */
    public function up(): void
    {
        Schema::create('occurrence_status_history', function (Blueprint $table) {
            $table->id();

            $table->foreignId('occurrence_id')
                  ->constrained('occurrences')
                  ->onDelete('cascade');

            $table->enum('from_status', ['pending', 'in_review', 'resolved', 'rejected', 'closed'])->nullable();
            $table->enum('to_status', ['pending', 'in_review', 'resolved', 'rejected', 'closed']);

            // Utilizador que fez a mudança (null = sistema automático)
            $table->foreignId('changed_by')
                  ->nullable()
                  ->constrained('users')
                  ->onDelete('set null');

            // Comentário público (visível ao reclamante)
            $table->text('comment')->nullable();

            // Nota interna (NÃO visível ao reclamante)
            $table->text('internal_note')->nullable();

            $table->timestamp('changed_at')->useCurrent();
            $table->timestamps();

            $table->index('occurrence_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('occurrence_status_history');
    }
};