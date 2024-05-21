<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ClientUser;
use App\Models\DriverUser;
use App\Models\User;
use App\Models\Intefaces\UserInterface;
use Illuminate\Database\Eloquent\SoftDeletes;

class Trip extends Model
{
    use SoftDeletes, HasFactory;

    protected $table 	= 'trips';
    protected $fillable 	= [
    	'id',
    	'driver_id',
		'client_id',
		'starting_address',
		'starting_latitude',
		'starting_longitude',
		'end_address',
		'end_latitude',
		'end_longitude',
		'driver_rate',
		'customer_rate',
		'trip_state',
		'trip_price',
    ];

    public function driver(){
    	return $this->belongsTo(User::class, 'driver_id', 'id');
    }

    public function client(){
    	return $this->belongsTo(User::class, 'client_id', 'id');
    }



}
