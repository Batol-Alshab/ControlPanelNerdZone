<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserTest extends Pivot
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'user_id',
        'test_id',
        'passing_rate',
    ];
}
