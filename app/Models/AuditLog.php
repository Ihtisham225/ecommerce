<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'model_type',
        'model_id',
        'user_id',
        'action',
        'changes',
    ];

    protected $casts = [
        'changes' => 'array',
    ];

    /**
     * Polymorphic relation to any model being audited
     */
    public function auditable()
    {
        return $this->morphTo(null, 'model_type', 'model_id');
    }

    /**
     * User performing the action
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
