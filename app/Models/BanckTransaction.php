<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BanckTransaction extends Model
{
    use HasFactory;
    protected $table 		= 'banck_transactions';
    protected $fillable 	= [
    	'id',
    	'transacion_description',
		'trip_pay_req_id',
		'bank_account_id',
		'transaction_type',
		'bank_account_balance',
    ];


    public static function getTypeIncrease(){
    	return 'credit';
    }

    public static function getTypeDecrease(){
    	return 'debit';
    }
}
