<?php

namespace App\Models;

use App\Models\Inquiry;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Answer extends Model
{
    use HasFactory, Notifiable;
    protected $fillable=[
        'answer_content',
        'inquiry_id',
    ];
    public function inquiry()
    {
        return $this->belongsTo(Inquiry::class);
    }

}
