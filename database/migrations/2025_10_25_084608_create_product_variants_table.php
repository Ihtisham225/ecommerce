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
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();

            $table->foreignId('product_id')->constrained()->cascadeOnDelete();

            $table->string('sku')->nullable();
            $table->string('barcode')->nullable();
            $table->string('title')->nullable(); // e.g. "Red / XL"
            
            $table->decimal('price', 15, 3)->nullable();
            $table->decimal('compare_at_price', 15, 3)->nullable();
            $table->decimal('cost_price', 15, 3)->nullable();

            $table->integer('stock_quantity')->nullable();
            $table->boolean('track_stock')->default(true);
            $table->string('stock_status')->default('in_stock');

            $table->json('options')->nullable(); // {"Color": "Red", "Size": "XL"}

            $table->boolean('is_active')->default(true);

            // Platform Integration
            $table->string('external_id')->nullable(); // Shopify/Woo variant ID
            $table->json('raw_data')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};
