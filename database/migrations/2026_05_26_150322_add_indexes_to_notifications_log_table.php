<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('notifications_log', function (Blueprint $table) {
            // Índice composto para a query de contagem de não-lidas por utilizador
            // NotificationLog::where('user_id')->system()->unread()->count()
            $table->index(['user_id', 'channel', 'read_at'], 'notif_user_channel_read_idx');
        });
    }

    public function down(): void
    {
        Schema::table('notifications_log', function (Blueprint $table) {
            $table->dropIndex('notif_user_channel_read_idx');
        });
    }
};
