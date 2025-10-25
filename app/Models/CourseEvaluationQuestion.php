<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\App;
use Illuminate\Database\Eloquent\Builder;


class CourseEvaluationQuestion extends Model
{
    use SoftDeletes;
    
    protected $fillable = ['question_text', 'answer_options','order', 'is_active'];

    protected $casts = [
        'question_text' => 'array',
        'answer_options' => 'array',
        'is_active' => 'boolean',
    ];

    public static $defaultAnswers = [
        'en' => ['Excellent', 'Very Good', 'Good', 'Fair'],
        'ar' => ['ممتاز', 'جيد جدًا', 'جيد', 'مقبول'],
    ];

    // ✅ Global scope to always order by `order`
    protected static function booted()
    {
        static::addGlobalScope('ordered', function (Builder $builder) {
            $builder->orderBy('order', 'asc');
        });
    }

    // 🔹 Accessor for localized question
    public function getQuestionTextAttribute($value)
    {
        $text = json_decode($value, true);
        return $text[App::currentLocale()] ?? $text['en'] ?? '';
    }

    // 🔹 Accessor for localized options
    public function getAnswerOptionsAttribute($value)
    {
        $options = json_decode($value, true);

        if (is_array($options) && isset($options[App::currentLocale()])) {
            return $options[App::currentLocale()];
        }

        return self::$defaultAnswers[App::currentLocale()] ?? [];
    }

    // 🔹 Method to get all translations (useful in admin forms)
    public function getAllQuestionTexts()
    {
        return isset($this->attributes['question_text']) && $this->attributes['question_text']
            ? json_decode($this->attributes['question_text'], true)
            : [];
    }

    public function getAllAnswerOptions()
    {
        return isset($this->attributes['answer_options']) && $this->attributes['answer_options']
            ? json_decode($this->attributes['answer_options'], true)
            : self::$defaultAnswers;
    }
}
