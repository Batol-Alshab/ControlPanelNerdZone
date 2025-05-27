<?php

namespace App\Models;

use App\Models\Lesson;
use App\Models\Section;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Material extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'image',
        'section_id'
    ];
    public function section()
    {
        return $this->belongsTo(Section::class);
    }
    public function lessons(){
        return $this->hasMany(Lesson::class);
    }
    protected static function booted()
    {
        $keys=['materialSection','stat','statmaterialForTeacher'];
        foreach ($keys as $key) {
            static::created(fn () => Cache::forget($key));
            static::updated(fn () => Cache::forget($key));
            static::deleted(fn () => Cache::forget($key));
        }
    }
}
