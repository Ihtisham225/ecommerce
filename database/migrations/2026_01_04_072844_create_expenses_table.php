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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('reference_number')->unique();
            $table->string('category');
            $table->string('title');
            $table->text('description')->nullable();
            $table->decimal('amount', 15, 2);
            $table->decimal('tax_amount', 15, 2)->default(0);
            $table->decimal('total_amount', 15, 2);
            $table->enum('payment_method', ['cash', 'bank_transfer', 'check', 'credit_card', 'digital_wallet', 'other']);
            $table->string('payment_reference')->nullable();
            $table->date('date');
            $table->date('due_date')->nullable();
            $table->enum('status', ['pending', 'paid', 'partial', 'overdue', 'cancelled'])->default('pending');
            $table->enum('type', ['purchase', 'operational', 'salary', 'utility', 'other']);
            $table->json('attachments')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
