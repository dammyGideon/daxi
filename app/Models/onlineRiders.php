<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class onlineRiders extends Model
{
    use HasFactory;

    protected $fillable=[
        'rider_id',
        'distances',
        'active'
    ];
}
