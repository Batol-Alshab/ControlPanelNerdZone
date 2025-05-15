<?php

namespace App\Models;

use App\Models\Lesson;
use App\Models\Inquiry;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Summery extends Model
{
    use HasFactory, Notifiable;
    protected $fillable=[
        'name',
        'file',
        'lesson_id'
    ];
    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }
    public function inquiries(){
        return $this->morphMany(Inquiry::class,'inquiryable');
    }
}
