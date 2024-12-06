<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Attendance extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        "date",
        "time",
        "status",
        "user_id"
    ];

    public function user (){
        return $this->belongsTo(User::class, "user_id");
    }
}