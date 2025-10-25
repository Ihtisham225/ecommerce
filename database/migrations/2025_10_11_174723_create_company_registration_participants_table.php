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
        Schema::create('company_registration_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_registration_id')->constrained()->cascadeOnDelete();

            $table->string('salutation')->nullable();
            $table->string('full_name');
            $table->string('participant_number')->nullable();
            $table->string('email')->nullable();
            $table->string('mobile')->nullable();
            $table->string('city_of_living')->nullable();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_registration_participants');
    }
};
