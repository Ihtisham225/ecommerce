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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('disk')->default('public');
            $table->string('file_path'); // e.g. courses/slug/brochure.pdf
            $table->string('file_type'); // pdf, jpg, zip, etc.
            $table->string('document_type'); // e.g. outline, profile_pic, cv;
            $table->string('mime_type');
            $table->unsignedBigInteger('size');
            $table->nullableMorphs('documentable'); // e.g. Course, Page
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
