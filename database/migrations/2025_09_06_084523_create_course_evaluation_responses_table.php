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
        Schema::create('course_evaluation_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_evaluation_id')->constrained()->cascadeOnDelete();
            $table->foreignId('question_id')->constrained('course_evaluation_questions')->cascadeOnDelete();
            $table->string('answer'); // e.g., strongly_agree, agree, neutral, disagree, strongly_disagree
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_evaluation_responses');
    }
};
