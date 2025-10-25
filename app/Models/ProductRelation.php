<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductRelation extends Model
{
    protected $fillable = ['product_id', 'related_product_id', 'type'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function related()
    {
        return $this->belongsTo(Product::class, 'related_product_id');
    }

    public function scopeType($q, $type)
    {
        return $q->where('type', $type);
    }
}
