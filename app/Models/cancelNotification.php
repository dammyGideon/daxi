<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class cancelNotification extends Model
{
    use HasFactory;

    protected $fillable=[
        'user_id',
        'message',
        'date',
    ];
}
