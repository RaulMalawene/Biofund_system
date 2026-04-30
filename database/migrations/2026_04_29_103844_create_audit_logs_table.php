<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Log de auditoria de todas as acções relevantes no sistema.
     * Regista quem fez o quê, quando e em que entidade.
     */
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();

            // Utilizador que executou a acção (null = sistema/cron)
            $table->foreignId('user_id')
                  ->nullable()
                  ->constrained('users')
                  ->onDelete('set null');

            // Entidade afectada
            $table->string('auditable_type', 100); // ex: App\Models\Occurrence
            $table->unsignedBigInteger('auditable_id');

            // Tipo de evento
            $table->string('event', 50); // created | updated | deleted | status_changed | login | logout

            // Snapshot dos dados antes e depois (JSON)
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();

            // Metadados de acesso
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent', 500)->nullable();

            $table->timestamp('occurred_at')->useCurrent();
            $table->timestamps();

            $table->index(['auditable_type', 'auditable_id']);
            $table->index('user_id');
            $table->index('occurred_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};