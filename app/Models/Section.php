<?php

namespace App\Models;

use App\Models\User;
use App\Models\Material;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Section extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name'
    ];

    public function users(){
        return $this->belongsToMany(User::class);
    }
    public function materials()
    {
        return $this->hasMany(Material::class);
    }
    protected static function booted()
    {
        $basekeys = ['materialSection', 'userSection','stat'];
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
