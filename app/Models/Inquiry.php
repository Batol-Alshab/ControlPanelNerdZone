<?php

namespace App\Models;

use App\Models\User;
use App\Models\Answer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Inquiry extends Model
{
    use HasFactory, Notifiable;

    protected $fillable =[
        'inquiry',
        'user_id',
        'inquiryable_id',
        'inquiryable_type',
    ];
    public function inquiryable()
    {
        return $this->morphTo();
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function answer()
    {
        return $this->hasOne(Answer::class);
    }
}
