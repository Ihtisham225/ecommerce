<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class CourseRegistration extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'course_schedule_id',
        'user_id',
        'status',
        'notes'
    ];

    protected $casts = [
        'status' => 'string',
    ];

    // Relationships
    public function courseSchedule()
    {
        return $this->belongsTo(CourseSchedule::class, 'course_schedule_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status','pending');
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status','confirmed');
    }
}
