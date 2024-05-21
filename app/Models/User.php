<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use App\Models\Trip;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use SoftDeletes, HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'user_type',
        'license_plate',
        'driver_rate',
        'customer_rate',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }


    public function canceldTripPayMentRequest(){
        return $this->hasMany(Trip::class, 'user_canceled_id', 'id');
    }

    public function approvedTripPayMentRequest(){
        return $this->hasMany(Trip::class, 'user_approved_id', 'id');
    }

    public function requiredTripPayMentRequest(){
        return $this->hasMany(Trip::class, 'user_equest_id', 'id');
    }

    public function beneficTripPayMentRequest(){
        return $this->hasMany(Trip::class, 'user_benefic_id', 'id');
    }
}
