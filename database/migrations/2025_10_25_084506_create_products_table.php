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
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            // Basic info
            $table->string('title')->nullable(); // {"en": "T-Shirt", "ur": "قمیض"}
            $table->text('description')->nullable();

            // SKU and type
            $table->string('sku')->unique();
            $table->string('type')->default('simple'); // simple, variable, digital, etc.

            // Pricing
            $table->decimal('price', 15, 3)->nullable();
            $table->decimal('compare_at_price', 15, 3)->nullable();

            // Inventory
            $table->integer('stock_quantity')->nullable();
            $table->boolean('track_stock')->default(true);
            $table->string('stock_status')->default('in_stock'); // in_stock, out_of_stock, etc.

            // Status & Visibility
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->timestamp('published_at')->nullable();

            // SEO & Slug
            $table->string('slug')->unique();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();

            // Associations
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('brand_id')->nullable()->constrained()->nullOnDelete();

            // Platform Integration
            $table->string('external_id')->nullable(); // Shopify/WooCommerce ID
            $table->string('platform')->nullable(); // shopify, woocommerce, local
            $table->string('handle')->nullable(); // Shopify handle or Woo slug
            $table->json('raw_data')->nullable(); // store full JSON response

            // Ownership
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
