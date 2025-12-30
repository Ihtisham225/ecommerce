<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class BlogComment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'blog_id',
        'user_id',
        'parent_id',
        'comment',
        'approved',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'approved' => 'boolean',
    ];

    // Relations
    public function blog(): BelongsTo
    {
        return $this->belongsTo(Blog::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(BlogComment::class, 'parent_id');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(BlogComment::class, 'parent_id');
    }

    // Scopes
    public function scopeApproved($query)
    {
        return $query->where('approved', true);
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    // Accessors
    public function getGravatarAttribute()
    {
        $hash = md5(strtolower(trim($this->user->email ?? '')));
        return "https://www.gravatar.com/avatar/{$hash}?s=60&d=mp";
    }

    public function getShortNameAttribute()
    {
        return strtok($this->user->name ?? 'Guest', ' ');
    }

    public function getCommentExcerptAttribute()
    {
        return Str::limit($this->comment, 100);
    }
}
