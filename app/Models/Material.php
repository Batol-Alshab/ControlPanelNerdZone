<?php

namespace App\Models;

use App\Models\User;
use App\Models\Lesson;
use App\Models\Section;
use App\Models\UserMaterial;
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

    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_material')
                    ->using(UserMaterial::class);
    }

    protected static function booted()
    {
        $basekeys=['materialSection','stat','CountOfStudentsAccessMaterials',];
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
