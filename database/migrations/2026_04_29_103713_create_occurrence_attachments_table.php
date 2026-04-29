<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('occurrence_attachments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('occurrence_id')
                  ->constrained('occurrences')
                  ->onDelete('cascade');

            // Quem fez o upload (null = utilizador externo)
            $table->foreignId('uploaded_by')
                  ->nullable()
                  ->constrained('users')
                  ->onDelete('set null');

            $table->string('original_name', 255);
            $table->string('stored_name', 255);       // nome no disco/storage
            $table->string('disk', 50)->default('local'); // local | s3 | etc
            $table->string('path', 500);
            $table->string('mime_type', 100);
            $table->unsignedBigInteger('size_bytes');

            $table->timestamps();

            $table->index('occurrence_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('occurrence_attachments');
    }
};