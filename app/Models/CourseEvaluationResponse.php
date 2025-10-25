<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CourseEvaluationResponse extends Model
{
    use SoftDeletes;
    
    protected $fillable = ['course_evaluation_id', 'question_id', 'answer'];

    public function evaluation()
    {
        return $this->belongsTo(CourseEvaluation::class, 'course_evaluation_id', 'id');
    }

    public function question()
    {
        return $this->belongsTo(CourseEvaluationQuestion::class, 'question_id');
    }
}
