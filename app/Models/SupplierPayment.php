<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SupplierPayment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'supplier_id',
        'expense_id',
        'reference_number',
        'amount',
        'previous_balance',
        'new_balance',
        'payment_method',
        'payment_reference',
        'payment_date',
        'status',
        'payment_type', // Add payment type
        'notes',
        'attachments',
        'user_id'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'previous_balance' => 'decimal:2',
        'new_balance' => 'decimal:2',
        'payment_date' => 'date',
        'attachments' => 'array'
    ];

    // Relationships
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function expense()
    {
        return $this->belongsTo(Expense::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopePartial($query)
    {
        return $query->where('payment_type', 'partial');
    }

    public function scopeFull($query)
    {
        return $query->where('payment_type', 'full');
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('payment_date', now()->month)
                    ->whereYear('payment_date', now()->year);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($payment) {
            if (!$payment->reference_number) {
                $payment->reference_number = 'PAY-' . strtoupper(uniqid());
            }

            // Set previous balance
            if (!$payment->previous_balance) {
                $payment->previous_balance = $payment->supplier->current_balance;
            }
        });

        static::created(function ($payment) {
            if ($payment->status === 'completed') {
                // Update supplier balance
                $payment->supplier->updateBalance($payment->amount, 'subtract');
                $payment->new_balance = $payment->supplier->current_balance;
                $payment->saveQuietly();

                // Mark associated expense as paid/partial if exists
                if ($payment->expense) {
                    $totalPaid = SupplierPayment::where('expense_id', $payment->expense->id)
                        ->where('status', 'completed')
                        ->sum('amount');

                    if ($totalPaid >= $payment->expense->total_amount) {
                        $payment->expense->update(['status' => 'paid']);
                    } elseif ($totalPaid > 0) {
                        $payment->expense->update(['status' => 'partial']);
                    }
                }
            }
        });
    }
    
    // Accessor to check if payment is partial
    public function getIsPartialAttribute()
    {
        return $this->payment_type === 'partial';
    }
    
    // Accessor to check if payment is full
    public function getIsFullAttribute()
    {
        return $this->payment_type === 'full';
    }
}