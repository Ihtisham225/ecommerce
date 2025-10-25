<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompanyRegistration extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'course_schedule_id', 'course_code', 'course_title', 'course_date', 'venue', 'language',
        'country', 'company_name', 'website', 'nature_of_business', 'postal_address',
        'salutation', 'full_name', 'job_title', 'email', 'telephone', 'mobile',
        'number_of_participants', 'heard_from', 'status'
    ];

    public function courseSchedule(): BelongsTo
    {
        return $this->belongsTo(CourseSchedule::class);
    }

    public function participants(): HasMany
    {
        return $this->hasMany(CompanyRegistrationParticipant::class);
    }
}
