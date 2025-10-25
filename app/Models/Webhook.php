<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Webhook extends Model
{
    protected $fillable = [
        'name', 'url', 'event', 'status', 'headers'
    ];

    protected $casts = [
        'headers' => 'array',
    ];
}

