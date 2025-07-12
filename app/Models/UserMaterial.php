<?php

namespace App\Models;

use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserMaterial extends Pivot
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'user_id',
        'material_id',
        'rate'
    ];

    protected static function booted()
    {
        $basekeys = ['stat', 'statmaterialForTeacher', 'CountOfStudentsAccessMaterials'];
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
