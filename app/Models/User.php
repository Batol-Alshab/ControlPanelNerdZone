<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Filament\Panel;
use App\Models\Lesson;
use App\Models\Inquiry;
use App\Models\Section;
use App\Models\UserMaterial;
use PhpParser\Node\Stmt\Catch_;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Cache;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;
    use HasRoles, HasApiTokens;

    public function canAccessPanel(Panel $panel): bool
    {
        //1 => admin , 2 => teacher
        if ($panel->getId() === 'admin') {
            return $this->hasRole(1); // فقط الإداريين
        }

        if ($panel->getId() === 'teacher') {
            return $this->hasRole(2); // فقط المعلمين
        }

        if ($panel->getId() === 'student') {
            return $this->hasRole('student');
        }
        return false;
        // return $this->hasRole(1) || $this->hasRole(2);
    }
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        // 'lname',
        'email',
        'password',
        'city',
        'sex',
        'section_id',
        'rate'
    ];

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function inquiries()
    {
        return $this->belongsToMany(Inquiry::class);
    }

    public function materials()
    {
        return $this->belongsToMany(Material::class, 'user_material')
            ->withPivot('rate')
            ->using(UserMaterial::class);
    }
     public function lessons()
    {
        return $this->belongsToMany( Lesson::class, 'user_lesson');
    }

    protected static function booted()
    {
        $baseKeys = [
            'userJoin',
            'userRole',
            'userSection',
            'stat',
            // 'statmaterialForTeacher',
        ];
        $locales = ['en', 'ar']; // أو استخرجها ديناميكياً إذا كنت حافظها في config

        foreach ($baseKeys as $key) {
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

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }
}
