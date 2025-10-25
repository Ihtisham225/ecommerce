<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExchangeRate extends Model
{
    protected $fillable = [
        'base_currency', 'target_currency', 'rate', 'last_updated_at'
    ];

    protected $casts = [
        'rate' => 'decimal:6',
        'last_updated_at' => 'datetime',
    ];
}

