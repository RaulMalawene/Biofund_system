<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Registo de todas as notificações enviadas.
     * Cobre notificações para reclamantes externos (por email)
     * e para utilizadores internos (email / sistema).
     */
    public function up(): void
    {
        Schema::create('notifications_log', function (Blueprint $table) {
            $table->id();

            $table->foreignId('occurrence_id')
                  ->constrained('occurrences')
                  ->onDelete('cascade');

            // Destinatário interno (se aplicável)
            $table->foreignId('user_id')
                  ->nullable()
                  ->constrained('users')
                  ->onDelete('set null');

            // Destinatário externo (email do reclamante)
            $table->string('recipient_email', 150)->nullable();

            $table->enum('channel', ['email', 'sms', 'system'])->default('email');
            $table->string('event_type', 80); // ex: occurrence_created, status_changed, assigned
            $table->text('message')->nullable();

            $table->enum('status', ['pending', 'sent', 'failed'])->default('pending');
            $table->timestamp('sent_at')->nullable();
            $table->text('error_message')->nullable();

            $table->timestamps();

            $table->index('occurrence_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications_log');
    }
};