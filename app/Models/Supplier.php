<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'company_name',
        'email',
        'phone',
        'address',
        'city',
        'state',
        'country',
        'postal_code',
        'notes',
        'tax_id',
        'payment_terms',
        'opening_balance',
        'current_balance',
        'status'
    ];

    protected $casts = [
        'opening_balance' => 'decimal:2',
        'current_balance' => 'decimal:2',
    ];

    // Relationships
    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    public function payments()
    {
        return $this->hasMany(SupplierPayment::class);
    }

    public function purchaseOrders()
    {
        return $this->hasMany(Expense::class)->where('type', 'purchase');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeWithBalance($query)
    {
        return $query->where('current_balance', '>', 0);
    }

    // Methods
    public function updateBalance($amount, $type = 'add')
    {
        if ($type === 'add') {
            $this->current_balance += $amount;
        } else {
            $this->current_balance -= $amount;
        }
        
        $this->save();
        return $this;
    }

    public function getTotalPurchasesAttribute()
    {
        return $this->expenses()->where('type', 'purchase')->sum('total_amount');
    }

    public function getTotalPaymentsAttribute()
    {
        return $this->payments()->sum('amount');
    }
}