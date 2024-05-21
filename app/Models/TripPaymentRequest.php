<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Trip;
use App\Models\User;
use App\Models\ClientUser;
use App\Models\ClientUser;

class TripPaymentRequest extends Model
{
    use HasFactory;
    protected $table 		= 'trip_payment_requests';
    protected $fillable 	= [
    	'id',
    	'reques_description',
		'trip_id',
		'user_benefic_id',
		'user_equest_id',
		'user_canceled_id',
		'user_approved_id',
		'trip_amout',
		'trip_tax',
		'net_trip_amout',
		'request_state',
		'approved_at',
		'canceld_at',
    ];

    public function trip(){
    	return $this->belongsTo(Trip::class, 'trip_id', 'id');
    }

    public function beneficUser(){
    	return $this->belongsTo(User::class, 'user_benefic_id', 'id');
    }

    public function requestUser(){
    	return $this->belongsTo(User::class, 'user_equest_id', 'id');
    }


    public function canceledUser(){
    	return $this->belongsTo(User::class, 'user_canceled_id', 'id');
    }

    public function approvedUser(){
    	return $this->belongsTo(User::class, 'user_approved_id', 'id');
    }
}
