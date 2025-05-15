<?php

namespace App\Models;

use App\Models\Test;
use App\Models\Video;
use App\Models\Course;
use App\Models\Summery;
use App\Models\Material;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Lesson extends Model
{
    use HasFactory, Notifiable;

    protected $fillable =[
        'name',
        'material_id',

    ];
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
}
