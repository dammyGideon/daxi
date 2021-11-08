<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;


    protected $fillable=[
            'user_id',
            'rider_id',
            'acceptOrder',
            'declineOrder',
            'Trip',
            'amount',
            'transactions',
            'present_lat',
            'present_log',
            'destination_lat',
            'destination_log',
            'pickup',
            'dropOf'
        ];
}
