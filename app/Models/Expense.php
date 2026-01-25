<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Expense extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'supplier_id',
        'user_id',
        'reference_number',
        'category',
        'title',
        'description',
        'amount',
        'tax_amount',
        'total_amount',
        'payment_method',
        'payment_reference',
        'date',
        'due_date',
        'status',
        'type',
        'attachments',
        'notes'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'date' => 'date',
        'due_date' => 'date',
        'attachments' => 'array'
    ];

    // Relationships
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function purchaseItems()
    {
        return $this->hasMany(PurchaseItem::class);
    }

    public function payments()
    {
        return $this->hasMany(SupplierPayment::class);
    }

    // Scopes
    public function scopePurchase($query)
    {
        return $query->where('type', 'purchase');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', 'overdue');
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('date', now()->month)
                    ->whereYear('date', now()->year);
    }

    // Methods
    public function markAsPaid()
    {
        $this->status = 'paid';
        $this->save();

        return $this;
    }
    
    public function markAsPartial()
    {
        $this->status = 'partial';
        $this->save();

        return $this;
    }
    
    // Calculate remaining amount
    public function getRemainingAmountAttribute()
    {
        $totalPaid = $this->payments()->where('status', 'completed')->sum('amount');
        return max(0, $this->total_amount - $totalPaid);
    }
    
    // Check if expense is partially paid
    public function isPartiallyPaid()
    {
        $totalPaid = $this->payments()->where('status', 'completed')->sum('amount');
        return $totalPaid > 0 && $totalPaid < $this->total_amount;
    }

    public function isOverdue()
    {
        return $this->due_date && $this->due_date < now() && $this->status !== 'paid';
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($expense) {
            if (!$expense->reference_number) {
                $expense->reference_number = 'EXP-' . strtoupper(uniqid());
            }
        });

        static::created(function ($expense) {
            if ($expense->supplier && $expense->type === 'purchase') {
                $expense->supplier->updateBalance($expense->total_amount, 'add');
            }
        });

        static::updated(function ($expense) {
            if ($expense->isDirty('total_amount') && $expense->supplier) {
                $oldAmount = $expense->getOriginal('total_amount');
                $difference = $expense->total_amount - $oldAmount;
                
                if ($difference != 0) {
                    $expense->supplier->updateBalance($difference, 'add');
                }
            }
        });
    }
}