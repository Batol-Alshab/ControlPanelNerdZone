<?php

namespace App\Models;

use App\Models\Test;
use App\Models\Video;
use App\Models\Course;
use App\Models\Summery;
use App\Models\Material;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Lesson extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'material_id',
        'cost'

    ];
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_lesson');
    }
    public function material()
    {
        return $this->belongsTo(Material::class);
    }

    public function videos()
    {
        return $this->hasMany(Video::class);
    }
    public function summeries()
    {
        return $this->hasMany(Summery::class);
    }
    public function tests()
    {
        return $this->hasMany(Test::class);
    }
    public function courses()
    {
        return $this->hasMany(Course::class);
    }
    protected static function booted()
    {
        $basekeys = ['contentLesson', 'stat'];
        $locales = ['en', 'ar'];

        foreach ($basekeys as $key) {
            foreach ($locales as $locale) {
                $keys[] = "{$key}_{$locale}";
            }
        }
        foreach ($keys as $key) {
            static::created(fn() => Cache::forget($key));
            static::updated(fn() => Cache::forget($key));
            static::deleted(fn() => Cache::forget($key));
        }
    }
}
