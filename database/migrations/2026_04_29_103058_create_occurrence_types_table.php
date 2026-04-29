<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('occurrence_types', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique();
            $table->string('name', 100);
            // Normal | Urgente | GBV
            $table->enum('alert_level', ['normal', 'urgent', 'gbv'])->default('normal');
            // Prazo de resolução em dias
            $table->unsignedSmallInteger('sla_days')->default(15);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('occurrence_types');
    }
};