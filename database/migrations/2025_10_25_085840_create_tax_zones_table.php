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
        Schema::create('tax_zones', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('country_code', 2)->nullable();
            $table->string('state')->nullable();
            $table->decimal('rate', 5, 2); // e.g. 15.00%
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tax_zones');
    }
};
