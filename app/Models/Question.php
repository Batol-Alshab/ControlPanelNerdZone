<?php

namespace App\Models;

use App\Models\Test;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Question extends Model
{
    use HasFactory, Notifiable;

    protected $fillable =[
        'content',
        'image',
        'test_id',

        'option_1',
        'option_2',
        'option_3',
        'option_4',

        'correct_option'
    ];

    public function test()
    {
        return $this->belongsTo(Test::class);
    }
}
