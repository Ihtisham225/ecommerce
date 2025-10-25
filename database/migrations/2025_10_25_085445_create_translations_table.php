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
        Schema::create('translations', function (Blueprint $table) {
            $table->id();
            $table->string('locale', 10); // en, ar, fr
            $table->string('translatable_type');
            $table->unsignedBigInteger('translatable_id');
            $table->string('field');
            $table->text('value')->nullable();
            $table->timestamps();

            $table->index(['translatable_type', 'translatable_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('translations');
    }
};
