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
        Schema::create('order_transactions', function (Blueprint $table) {
            $table->id();

            $table->string('type');       
            // "authorization", "capture", "refund", "void", "sale"

            $table->enum('status', ['pending', 'paid', 'failed', 'refunded', 'partially_refunded', 'partially_paid'])->default('pending'); 
            // pending, success, failed

            $table->decimal('amount', 10, 2);
            $table->string('payment_method')->nullable(); 
            // e.g. "card", "paypal", etc.

            $table->string('gateway')->nullable(); 
            // stripe, cod, paypal

            $table->string('transaction_id')->nullable(); 
            // gateway transaction ID

            $table->json('meta')->nullable(); 
            // raw gateway response

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_transactions');
    }
};
