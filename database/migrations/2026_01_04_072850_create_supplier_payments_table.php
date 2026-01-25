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
        Schema::create('supplier_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->constrained()->onDelete('cascade');
            $table->foreignId('expense_id')->nullable()->constrained()->onDelete('set null');
            $table->string('reference_number')->unique();
            $table->decimal('amount', 15, 2);
            $table->decimal('previous_balance', 15, 2);
            $table->decimal('new_balance', 15, 2);
            $table->enum('payment_method', ['cash', 'bank_transfer', 'check', 'credit_card', 'digital_wallet', 'other']);
            $table->string('payment_reference')->nullable();
            $table->date('payment_date');
            $table->enum('status', ['pending', 'completed', 'paid', 'overdue', 'partial', 'failed', 'cancelled'])->default('pending');
            $table->enum('payment_type', ['partial', 'full'])->default('partial');
            $table->text('notes')->nullable();
            $table->json('attachments')->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supplier_payments');
    }
};
