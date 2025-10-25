<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CourseEvaluation extends Model
{
    use SoftDeletes;
    
    protected $fillable = ['course_schedule_id', 'user_id'];

    public function courseSchedule()
    {
        return $this->belongsTo(CourseSchedule::class, 'course_schedule_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function responses()
    {
        return $this->hasMany(CourseEvaluationResponse::class, 'course_evaluation_id');
    }
}

