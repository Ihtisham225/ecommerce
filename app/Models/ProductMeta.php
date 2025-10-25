<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductMeta extends Model
{
    protected $fillable = ['product_id', 'key', 'value', 'namespace', 'type'];

    protected $casts = [
        'value' => 'array',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function scopeKey($q, $key)
    {
        return $q->where('key', $key);
    }
}


