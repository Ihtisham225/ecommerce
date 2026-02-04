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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('customer_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('status', ['pending', 'confirmed', 'processing', 'completed', 'cancelled'])->nullable()->default('pending');
            $table->enum('payment_status', ['pending', 'paid', 'failed', 'refunded', 'partially_refunded', 'partially_paid'])->nullable()->default('pending');
            $table->enum('shipping_status', ['pending', 'ready_for_shipment', 'shipped', 'delivered'])->nullable()->default('pending');
            $table->enum('source', ['online', 'in_store'])->default('online');
            
            // Pricing
            $table->decimal('subtotal', 15, 3)->default(0);
            $table->decimal('discount_total', 15, 3)->default(0);
            $table->decimal('tax_total', 15, 3)->default(0);
            $table->decimal('shipping_total', 15, 3)->default(0);
            $table->decimal('grand_total', 15, 3)->default(0);
            $table->boolean('tax_inclusive')->default(false);
            $table->decimal('tax_rate', 15, 3)->default(0);
            
            // Shipping
            $table->foreignId('shipping_rate_id')->nullable()->constrained()->nullOnDelete();
            $table->string('shipping_method')->nullable();
            
            // Additional fields
            $table->text('platform')->nullable();
            $table->text('external_id')->nullable();
            $table->json('raw_data')->nullable();
            $table->text('notes')->nullable();
            $table->text('admin_notes')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamp('completed_at')->nullable();
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
        Schema::dropIfExists('orders');
    }
};
