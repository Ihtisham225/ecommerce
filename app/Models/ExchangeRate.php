<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExchangeRate extends Model
{
    protected $fillable = [
        'base_currency_id', 'target_currency_id', 'rate', 'last_updated_at'
    ];

    protected $casts = [
        'rate' => 'decimal:6',
        'last_updated_at' => 'datetime',
    ];

    public function baseCurrency()
    {
        return $this->belongsTo(Currency::class, 'base_currency_id');
    }

    public function targetCurrency()
    {
        return $this->belongsTo(Currency::class, 'target_currency_id');
    }
}

