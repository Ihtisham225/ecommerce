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
        Schema::create('course_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->foreignId('instructor_id')->nullable()->constrained()->nullOnDelete();

            // Multilingual fields
            $table->json('title')->nullable(); // e.g. {"en": "Batch 1 - November", "ar": "الدفعة الأولى - نوفمبر"}
            $table->json('venue')->nullable(); // e.g. {"en": "Istanbul", "ar": "إسطنبول"}

            // Schedule details
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();

            // Meta info
            $table->string('days')->nullable(); // "5 Days"
            $table->decimal('cost', 10, 3)->nullable();
            $table->boolean('is_active')->default(true);

            // Optional: if you want per-schedule language, session type, etc.
            $table->string('language')->nullable(); // "English", "Arabic"
            $table->string('session')->nullable(); // "2025", "2026"
            $table->string('nature')->nullable();
            $table->enum('type', ['course', 'workshop'])->default('course');
            $table->foreignId('country_id')->nullable()->constrained()->nullOnDelete();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_schedules');
    }
};
