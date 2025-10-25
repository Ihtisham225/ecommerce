<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tax extends Model
{
    protected $fillable = [
        'name', 'rate', 'country_code', 'is_default'
    ];

    protected $casts = [
        'rate' => 'decimal:2',
        'is_default' => 'boolean',
    ];
}

