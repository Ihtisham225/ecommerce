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
        Schema::create('exchange_rates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('base_currency_id')->references('id')->on('currencies');
            $table->foreignId('target_currency_id')->references('id')->on('currencies');
            $table->string('target_currency', 3);
            $table->decimal('rate', 15, 6);
            $table->timestamp('last_updated_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exchange_rates');
    }
};
