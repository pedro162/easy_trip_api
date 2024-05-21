<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use App\Models\User;

class DriverUser extends User
{
   
    public function trip(){
        return $this->hasMany(Trip::class, 'driver_id', 'id');
    }

    public function account(){
        return $this->hasMany(BankAccount::class, 'user_id', 'id');
    }
}
