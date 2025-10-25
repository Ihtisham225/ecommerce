<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaxZone extends Model
{
    protected $fillable = [
        'name', 'country_code', 'state', 'rate', 'is_default'
    ];

    protected $casts = [
        'rate' => 'decimal:2',
        'is_default' => 'boolean',
    ];
}
