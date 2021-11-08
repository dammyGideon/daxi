<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'vehicle_type',
        'otp',
        'password',
        'phone_verified_at',
        'location',
        'vehicle',
        'longitude',
        'latitude',
        'role'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'otp',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'phone_verified_at' => 'datetime',
    ];

    public function findForPassport($phone)
    {
        return $this->where('phone', $phone)->first();
    }

    public function hasRole(...$roles) {
        //$user->hasRole('admin', 'developer');

        return $this->roles()->whereIn('slug', $roles)->count();
    }

    public function roles() {
        return $this->belongsToMany(Role::class, 'users_roles');
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'users_permissions');
    }

    public function orders() {
        return $this->hasMany('App\Models\Order');
    }

    public function recommendations() {
        return $this->belongsToMany(Recommendation::class, 'recommendations');
    }

    public function ratings() {
        return $this->hasMany('App\Models\Rating');
    }

    public function banks() {
        return $this->hasMany('App\Models\Bank');
    }
}
