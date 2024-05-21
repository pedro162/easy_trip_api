<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;

class BankAccount extends Model
{
    use SoftDeletes, HasFactory;
    
    protected $table 		= 'bank_accounts';
    protected $fillable 	= [
    	'id',
    	'user_id',
		'bank_branch',
		'bank_account_number',
		'bank_account_digit',
		'bank_account_balance',
    ];

    public function client(){
    	return $this->belongsTo(User::class, 'user_id', 'id');
    }

}
