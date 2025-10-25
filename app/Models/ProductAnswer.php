<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductAnswer extends Model
{
    protected $fillable = [
        'question_id', 'user_id', 'answer', 'is_approved'
    ];

    protected $casts = [
        'is_approved' => 'boolean',
    ];

    public function question()
    {
        return $this->belongsTo(ProductQuestion::class, 'question_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
