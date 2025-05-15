<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\Inquiry;
use App\Models\Section;
use PhpParser\Node\Stmt\Catch_;
use Illuminate\Support\Facades\Cache;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

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

    public function section(){
        return $this->belongsTo(Section::class);
    }

    public function inquiries()
    {
        return $this->belongsToMany(Inquiry::class);
    }

    protected static function booted()
    {
        $keys=['Userjoin'];
        foreach ($keys as $key) {
            static::created(fn () => Cache::forget($keys));
            static::updated(fn () => Cache::forget($keys));
            static::deleted(fn () => Cache::forget($keys));
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

}
