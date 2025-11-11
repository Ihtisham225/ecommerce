<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;
class Collection extends Model
{
    protected $fillable = ['title', 'description', 'slug', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public static function boot()
    {
        parent::boot();

        static::saving(function ($collection) {
            if (empty($collection->slug)) {
                $collection->slug = Str::slug($collection->title);
            }
        });
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_collection');
    }

    public function scopeActive($q)
    {
        return $q->where('is_active', 1);
    }
}

