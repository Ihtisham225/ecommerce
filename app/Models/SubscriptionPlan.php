<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
{
    protected $fillable = [
        'name', 'price', 'duration_days', 'features', 'is_active'
    ];

    protected $casts = [
        'price' => 'decimal:3',
        'features' => 'array',
        'is_active' => 'boolean',
    ];
}

