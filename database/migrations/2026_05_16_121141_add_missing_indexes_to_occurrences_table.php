<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('occurrences', function (Blueprint $table) {
            $table->index('submitted_by_user_id');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::table('occurrences', function (Blueprint $table) {
            $table->dropIndex(['submitted_by_user_id']);
            $table->dropIndex(['created_at']);
        });
    }
};
