<?php 

namespace App\Domain;

use App\Validators\BanckTransactionValidator;
use Illuminate\Support\Facades\Auth; 
use App\Exceptions\BankTransactionException;
use App\Models\BanckTransaction;

class BanckTransactionDomain{

	public function index(){
		//----- Select active records ---------------------------------------
		$result = BanckTransaction::all();

		return $result;
	}

	public function create(array $data = []){
		//----- Select only the necessary informations ---------------------------
		$dataToBanckTransaction = [
			'transacion_description' => $data['transacion_description'] ?? '',
			'trip_pay_req_id'		 => $data['trip_pay_req_id'] 		?? '',
			'bank_account_id'		 => $data['bank_account_id'] 		?? '',
			'transaction_type'		 => $data['transaction_type'] 		?? '',
			'bank_account_balance'	 => $data['bank_account_balance'] 	?? '',
		];

		//----- Validate infomations ----------------------------------------------
		$erros = BanckTransactionValidator::validateDataToCreateTripPaymentRequest($dataToBanckTransaction);
		if(is_array($erros) && count($erros) > 0){
			$strErros = implode(', ', $erros);
			throw new BankTransactionException($strErros);
		}
		
		$tripObject = BanckTransaction::create($dataToBanckTransaction);
		if(! $tripObject){
			throw new UserException("Something went wrong. It was not possible to create a new trip. Please try again or contact support.");
		}

		return $tripObject;
	}


    /**
     * Display the specified resource.
     */
    public function show(string $id){
    	$id = (int) $id;
    	if(!($id > 0)){
			$strErro = "The record code informaded isn't valid {$id}";
			throw new BankTransactionException($strErro);
		}

        //----- Try to load the record -------------------------------------------

		$tripObject = BanckTransaction::find($id);
		if(!$tripObject){
			//$strErro = "It was not possible to locale the record of code number {$id}";
			///throw new BankTransactionException($strErro);
		}

		return $tripObject;
    }

	/**
     * Update the specified resource in storage.
     */
	public function update(string $id , array $data = []){
		$id = (int) $id;
		if(!($id > 0)){
			$strErro = "The record code informaded isn't valid {$id}";
			throw new BankTransactionException($strErro);
		}

		//----- Try to load the record -------------------------------------------
		$tripObject = BanckTransaction::find($id);
		if(!$tripObject){
			$strErro = "It was not possible to locale the record of code number {$id}";
			throw new BankTransactionException($strErro);
		}

		//----- Select only the necessary informations ---------------------------		
		$dataToBanckTransaction = [
			'transacion_description' => $data['transacion_description'] ?? $tripObject->transacion_description,
			'trip_pay_req_id'		 => $data['trip_pay_req_id'] 		?? $tripObject->trip_pay_req_id,
			'bank_account_id'		 => $data['bank_account_id'] 		?? $tripObject->bank_account_id,
			'transaction_type'		 => $data['transaction_type'] 		?? $tripObject->transaction_type,
			'bank_account_balance'	 => $data['bank_account_balance'] 	?? $tripObject->bank_account_balance,
		];

		//----- Validate infomations ----------------------------------------------
		$erros = BanckTransactionValidator::validateDataToCreateTripPaymentRequest($dataToBanckTransaction);
		if(is_array($erros) && count($erros) > 0){			
			$strErros = implode(', ', $erros);
			throw new BankTransactionException($strErros);
		}

		//----- Update to load the record -------------------------------------------
		$tripObject->update($dataToBanckTransaction);

		return $tripObject;
	}

	/**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
    	$id = (int) $id;
		if(!($id > 0)){
			$strErro = "The record code informad isn't valid {$id}";
			throw new BankTransactionException($strErro);
		}

    	//----- Try to load the record -------------------------------------------
        $tripObject = BanckTransaction::find($id);
		if(!$tripObject){
			$strErro = "It was not possible to locale the trip of code number {$id}";
			throw new BankTransactionException($strErro);
		}

		//---- I'm using soft delete, that's why I can do this ------------
		$tripObject->delete();

		return true;
    }

}
