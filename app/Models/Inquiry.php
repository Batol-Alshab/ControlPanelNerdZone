<?php

namespace App\Models;

use App\Models\User;
use App\Models\Answer;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Inquiry extends Model
{
    use HasFactory, Notifiable;
    use SoftDeletes;

    protected $fillable =[
        'inquiry',
        'user_id',
        'inquiryable_id',
        'inquiryable_type',
        'status',
    ];
    public function inquiryable()
    {
        return $this->morphTo();
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function answer()
    {
        return $this->hasOne(Answer::class);
    }

    protected static function booted()
    {
        $baseKeys = [
            'stat',
            // 'statmaterialForTeacher',
        ];
        $locales = ['en', 'ar']; // أو استخرجها ديناميكياً إذا كنت حافظها في config

        foreach ($baseKeys as $key)
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
