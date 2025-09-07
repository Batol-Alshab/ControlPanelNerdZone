<?php

namespace App\Models;

use App\Models\User;
use App\Models\Material;
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
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function material()
    {
        return $this->belongsTo(Material::class);
    }

    protected static function booted()
    {
        $basekeys = ['stat', 'statmaterialForTeacher', 'CountOfStudentsAccessMaterials','countTestCourceSummeryVideoForTeacher_'];
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
