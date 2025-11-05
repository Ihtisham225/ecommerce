<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    protected $fillable = [
        'code', 'symbol', 'exchange_rate', 'is_default'
    ];

    protected $casts = [
        'exchange_rate' => 'decimal:6',
        'is_default' => 'boolean',
    ];
    
    public static function default()
    {
        return static::where('is_default', true)->first() ?? static::first();
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    public function exchangeRates()
    {
        return $this->hasMany(ExchangeRate::class, 'base_currency', 'code');
    }

    public function updateTotalsForCurrency(Currency $currency)
    {
        $rate = $currency->exchange_rate;
        
        $this->subtotal = $this->subtotal * $rate;
        $this->tax_total = $this->tax_total * $rate;
        $this->discount_total = $this->discount_total * $rate;
        $this->grand_total = $this->grand_total * $rate;
        $this->currency()->associate($currency);
        $this->save();
    }
}
