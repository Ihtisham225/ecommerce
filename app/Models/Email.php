<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Email extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'from', 'to', 'cc', 'subject', 'body', 'attachments', 'is_read', 'uid'
    ];

    protected $casts = [
        'to'          => 'array',
        'cc'          => 'array',
        'attachments' => 'array',
        'is_read'     => 'boolean',
    ];

    public function getToStringAttribute() {
        return is_array($this->to) ? implode(', ', $this->to) : $this->to;
    }

    public function getCcStringAttribute() {
        return is_array($this->cc) ? implode(', ', $this->cc) : $this->cc;
    }
}
