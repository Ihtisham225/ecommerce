<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    protected $fillable = ['customer_id'];

    public function items()
    {
        return $this->hasMany(WishlistItem::class);
    }
}

