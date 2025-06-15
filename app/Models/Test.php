<?php

namespace App\Models;

use App\Models\Lesson;
use App\Models\Question;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Test extends Model
{
    use HasFactory, Notifiable;
    protected $fillable=[
        'name',
        'numQuestions',
        'is_complete',
        'lesson_id',
    ];
    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }
    public function questions()
    {
        return $this->hasMany(Question::class);
    }
    public function inquiries(){
        return $this->morphMany(Inquiry::class,'inquiryable');
    }
    protected static function booted()
    {
        $basekeys = ['contentLesson','statLesson',
                'countTestCourceSummeryVideoForTeacher'
            ];
        $locales = ['en', 'ar'];

        foreach ($basekeys as $key)
        {
            foreach ($locales as $locale)
            {
                $keys[] = "{$key}_{$locale}";
            }
        }
        foreach ($keys as $key)
        {
            static::created(fn () => Cache::forget($key));
            static::updated(fn () => Cache::forget($key));
            static::deleted(fn () => Cache::forget($key));
        }
    }
}
