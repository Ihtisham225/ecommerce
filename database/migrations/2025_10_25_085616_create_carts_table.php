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
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->nullable()->constrained()->nullOnDelete();
            $table->string('session_id')->nullable();
            $table->foreignId('currency_id')->references('id')->on('currencies');

            $table->decimal('subtotal', 15, 3)->default(0);
            $table->decimal('discount_total', 15, 3)->default(0);
            $table->decimal('tax_total', 15, 3)->default(0);
            $table->decimal('grand_total', 15, 3)->default(0);

            $table->softDeletes();
            $table->timestamps();

            $table->index(['customer_id', 'session_id']); // performance boost
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};
