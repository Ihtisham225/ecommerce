<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContactInquiry extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'subject',
        'message',
        'status',
        'admin_notes'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * The possible status values for an inquiry.
     *
     * @var array
     */
    public static $statuses = [
        'unread' => 'Unread',
        'read' => 'Read',
        'in_progress' => 'In Progress',
        'replied' => 'Replied',
        'resolved' => 'Resolved'
    ];

    /**
     * Get the status label for the inquiry.
     *
     * @return string
     */
    public function getStatusLabelAttribute()
    {
        return self::$statuses[$this->status] ?? $this->status;
    }

    /**
     * Get the status badge class for the inquiry.
     *
     * @return string
     */
    public function getStatusBadgeClassAttribute()
    {
        $classes = [
            'unread' => 'bg-blue-100 text-blue-800',
            'read' => 'bg-gray-100 text-gray-800',
            'in_progress' => 'bg-yellow-100 text-yellow-800',
            'replied' => 'bg-green-100 text-green-800',
            'resolved' => 'bg-purple-100 text-purple-800'
        ];

        return $classes[$this->status] ?? 'bg-gray-100 text-gray-800';
    }
}