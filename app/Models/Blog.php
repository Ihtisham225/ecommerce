<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\App;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Blog extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'published_at',
        'author_id',
        'blog_category_id',
        'featured',
        'published',
        'meta_title',
        'meta_description',
        'tags',
        'views',
        'reading_time',
    ];

    protected $casts = [
        'title' => 'array',
        'excerpt' => 'array',
        'content' => 'array',
        'meta_title' => 'array',
        'meta_description' => 'array',
        'published_at' => 'datetime',
        'published' => 'boolean',
        'featured' => 'boolean',
        'tags' => 'array',
        'deleted_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($blog) {
            if (empty($blog->slug)) {
                $blog->slug = Str::slug($blog->title);
            }
            
            if (empty($blog->excerpt)) {
                $blog->excerpt = Str::limit(strip_tags($blog->content), 150);
            }
            
            if (empty($blog->reading_time)) {
                $blog->reading_time = self::calculateReadingTime($blog->content);
            }
        });

        static::updating(function ($blog) {
            if ($blog->isDirty('title') && empty($blog->slug)) {
                $blog->slug = Str::slug($blog->title);
            }
            
            if ($blog->isDirty('content') && empty($blog->excerpt)) {
                $blog->excerpt = Str::limit(strip_tags($blog->content), 150);
            }
            
            if ($blog->isDirty('content') && empty($blog->reading_time)) {
                $blog->reading_time = self::calculateReadingTime($blog->content);
            }
        });
    }

    // Accessors for multilingual fields
    public function getTitleAttribute($value)
    {
        $title = json_decode($value, true);
        return $title[App::currentLocale()] ?? $title['en'] ?? '';
    }

    public function getExcerptAttribute($value)
    {
        if (!$value) return null;
        $excerpt = json_decode($value, true);
        return $excerpt[App::currentLocale()] ?? $excerpt['en'] ?? '';
    }

    public function getContentAttribute($value)
    {
        if (!$value) return null;
        $content = json_decode($value, true);
        return $content[App::currentLocale()] ?? $content['en'] ?? '';
    }

    public function getMetaTitleAttribute($value)
    {
        if (!$value) return null;
        $metaTitle = json_decode($value, true);
        return $metaTitle[App::currentLocale()] ?? $metaTitle['en'] ?? '';
    }
    
    public function getMetaDescriptionAttribute($value)
    {
        if (!$value) return null;
        $metaDescription = json_decode($value, true);
        return $metaDescription[App::currentLocale()] ?? $metaDescription['en'] ?? '';
    }
    
    public function getTagsAttribute($value)
    {
        if (!$value) return null;
        $tags = json_decode($value, true);
        return $tags[App::currentLocale()] ?? $tags['en'] ?? '';
    }

    // Get raw multilingual data
    public function getTitles()
    {
        return json_decode($this->attributes['title'], true);
    }

    public function getExcerpts()
    {
        return $this->attributes['excerpt'] ? json_decode($this->attributes['excerpt'], true) : [];
    }

    public function getContents()
    {
        return $this->attributes['content'] ? json_decode($this->attributes['content'], true) : [];
    }

    public function getMetaTitles()
    {
        return $this->attributes['meta_title'] ? json_decode($this->attributes['meta_title'], true) : [];
    }
    
    public function getMetaDescriptions()
    {
        return $this->attributes['meta_description'] ? json_decode($this->attributes['meta_description'], true) : [];
    }
    
    public function getTags()
    {
        return $this->attributes['tags'] ? json_decode($this->attributes['tags'], true) : [];
    }

    private static function calculateReadingTime($content, $wordsPerMinute = 200)
    {
        $wordCount = str_word_count(strip_tags($content));
        return ceil($wordCount / $wordsPerMinute);
    }

    // public function scopePublished($query)
    // {
    //     return $query->where('published', true)
    //                 ->where('published_at', '<=', now());
    // }
    
    public function scopePublished($query)
    {
        return $query->where('published', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }

    public function scopeByAuthor($query, $authorId)
    {
        return $query->where('author_id', $authorId);
    }

    public function scopeSearch($query, $searchTerm)
    {
        return $query->where(function($q) use ($searchTerm) {
            $q->where('title', 'like', "%{$searchTerm}%")
              ->orWhere('content', 'like', "%{$searchTerm}%")
              ->orWhere('excerpt', 'like', "%{$searchTerm}%");
        });
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function blogCategory(): BelongsTo
    {
        return $this->belongsTo(BlogCategory::class, 'blog_category_id');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(BlogComment::class)->whereNull('parent_id');
    }

    public function allComments(): HasMany
    {
        return $this->hasMany(BlogComment::class);
    }

    public function approvedComments()
    {
        return $this->comments()->where('approved', true);
    }

    public function isPublished()
    {
        return $this->published && $this->published_at <= now();
    }

    public function incrementViews()
    {
        $this->timestamps = false;
        $this->increment('views');
        $this->timestamps = true;
    }

    public function getReadingTimeAttribute($value)
    {
        return $value ?: self::calculateReadingTime($this->content);
    }

    public function getPublishedAtFormattedAttribute()
    {
        return $this->published_at ? $this->published_at->format('F j, Y') : 'Not published';
    }

    public function getPublishedAtShortAttribute()
    {
        return $this->published_at ? $this->published_at->format('M j, Y') : 'Draft';
    }

    public function getTimeAgoAttribute()
    {
        return $this->published_at ? $this->published_at->diffForHumans() : 'Not published';
    }

    public function getTagsStringAttribute()
    {
        return $this->tags ? implode(', ', $this->tags) : '';
    }

    public function setTagsStringAttribute($value)
    {
        $this->tags = $value ? array_map('trim', explode(',', $value)) : null;
    }

    public function blogImage()
    {
        return $this->morphOne(Document::class, 'documentable')
            ->where('document_type', 'blog_image');
    }
}