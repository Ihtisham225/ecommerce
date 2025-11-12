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
        Schema::create('product_shipping', function (Blueprint $table) {
            $table->id();

            // The product this shipping data belongs to
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();

            // Optional link to a specific variant
            $table->foreignId('variant_id')->nullable()->constrained('product_variants')->cascadeOnDelete();

            // Core shipping fields
            $table->boolean('requires_shipping')->default(true);
            $table->decimal('weight', 10, 2)->nullable();
            $table->decimal('width', 10, 2)->nullable();
            $table->decimal('height', 10, 2)->nullable();
            $table->decimal('length', 10, 2)->nullable();

            $table->timestamps();

            // Optional index for faster lookups
            $table->index(['product_id', 'variant_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_shipping');
    }
};
