<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orderhistory extends Model
{
    use HasFactory;

    protected $filable =[
        'user_id',
        'rider_id',
        'message',
        'pickup',
        'dropOf',
        'date',
    ];
}
