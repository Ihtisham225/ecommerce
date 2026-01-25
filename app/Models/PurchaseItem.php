<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'expense_id',
        'product_id',
        'variant_id',
        'quantity',
        'unit_price',
        'total_price',
        'description'
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2'
    ];

    // Relationships
    public function expense()
    {
        return $this->belongsTo(Expense::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }


    protected static function boot()
    {
        parent::boot();

        static::saving(function ($item) {
            $item->total_price = $item->quantity * $item->unit_price;
        });
    }
}