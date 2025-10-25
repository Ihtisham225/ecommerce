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
        Schema::create('store_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete(); // merchant
            $table->string('store_name');
            $table->string('store_email')->nullable();
            $table->string('store_phone')->nullable();
            $table->string('currency_code', 3)->default('USD');
            $table->string('timezone')->nullable();
            $table->string('logo')->nullable();
            $table->json('settings')->nullable(); // e.g. theme, tax, shipping config
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('store_settings');
    }
};
