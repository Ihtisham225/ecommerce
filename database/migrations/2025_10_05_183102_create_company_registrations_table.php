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
        Schema::create('company_registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_schedule_id')->constrained()->cascadeOnDelete();
            
            // A. Course details (redundant for quick admin view)
            $table->string('course_code')->nullable();
            $table->string('course_title')->nullable();
            $table->date('course_date')->nullable();
            $table->string('venue')->nullable();
            $table->string('language')->nullable();

            // B. Company details
            $table->string('country');
            $table->string('company_name');
            $table->string('website')->nullable();
            $table->string('nature_of_business')->nullable();
            $table->string('postal_address');

            // C. Contact person
            $table->string('salutation');
            $table->string('full_name');
            $table->string('job_title');
            $table->string('email');
            $table->string('telephone');
            $table->string('mobile');

            // D. Participants
            $table->integer('number_of_participants');
            $table->string('heard_from')->nullable();

            // Meta
            $table->string('status')->default('pending');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_registrations');
    }
};
