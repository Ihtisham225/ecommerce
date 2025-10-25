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
        Schema::create('galleries', function (Blueprint $table) {
            $table->id();
            $table->json('title');                  // Event/Workshop/Course name
            $table->json('description')->nullable();  // Optional description
            $table->year('year')->nullable();         // For filtering
            $table->enum('layout', ['grid', 'slider', 'mixed'])->default('grid'); // How to display
            $table->boolean('featured')->default(false); // Highlighted on homepage
            $table->boolean('is_active')->default(true);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('galleries');
    }
};
