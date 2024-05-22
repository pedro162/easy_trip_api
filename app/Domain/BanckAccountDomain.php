<?php 

namespace App\Domain;

use App\Validators\BankAccountValidator;
use Illuminate\Support\Facades\Auth; 
use App\Exceptions\BankAccountException;
use App\Models\BankAccount;
use App\Models\BanckTransactionDomain;
use App\Models\BanckTransaction;
use App\Models\User;

class BanckAccountDomain{

	public function index(){

		//----- Select active records ---------------------------------------
		$result = BankAccount::all();

		return $result;
	}

	public function create(array $data = [], User $userObj){

		//----- Select only the necessary informations ---------------------------		
		$dataToBankAccount = [
			'user_id'				=> $userObj->id,
			'bank_branch'			=> $data['bank_branch'] 			?? '',
			'bank_account_number'	=> $data['bank_account_number'] 	?? '',
			'bank_account_digit'	=> $data['bank_account_digit'] 		?? '',
			'bank_account_balance'	=> 0,
		];

		//----- Validate infomations ----------------------------------------------
		$erros = BankAccountValidator::validateDataToCreateBankAccount($dataToBankAccount, new BankAccount());
		if(is_array($erros) && count($erros) > 0){

			$strErros = implode(', ', $erros);
			throw new BankAccountException($strErros);
		}
		
		$tripObject = BankAccount::create($dataToBankAccount);
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
			throw new BankAccountException($strErro);
		}

        //----- Try to load the record -------------------------------------------

		$tripObject = BankAccount::find($id);
		if(!$tripObject){
			//$strErro = "It was not possible to locale the record of code number {$id}";
			///throw new BankAccountException($strErro);
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
			throw new BankAccountException($strErro);
		}

		//----- Try to load the record -------------------------------------------

		$tripObject = BankAccount::find($id);
		if(!$tripObject){
			$strErro = "It was not possible to locale the record of code number {$id}";
			throw new BankAccountException($strErro);
		}

		//----- Select only the necessary informations ---------------------------
		
		$dataToBankAccount = [
			'bank_branch'			=> $data['bank_branch'] 			?? $tripObject->bank_branch,
			'bank_account_number'	=> $data['bank_account_number'] 	?? $tripObject->bank_account_number,
			'bank_account_digit'	=> $data['bank_account_digit'] 		?? $tripObject->bank_account_digit,
		];

		//----- Validate infomations ----------------------------------------------
		$erros = BankAccountValidator::validateDataToUpdateBankAccount($dataToBankAccount);
		if(is_array($erros) && count($erros) > 0){
			
			$strErros = implode(', ', $erros);
			throw new BankAccountException($strErros);
		}

		//----- Update to load the record -------------------------------------------
		$tripObject->update($dataToBankAccount);

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
			throw new BankAccountException($strErro);
		}

    	//----- Try to load the record -------------------------------------------
        $tripObject = BankAccount::find($id);
		if(!$tripObject){
			$strErro = "It was not possible to locale the trip of code number {$id}";
			throw new BankAccountException($strErro);
		}
		
		//---- I'm using soft delete, that's why I can do this ------------
		$tripObject->delete();

		return true;
    }

    public function makeTransaction(BankAccount $bankAccountObj, TripPaymentRequest $tripRquestObj, string $transactionType){
		
		if(!$bankAccountObj){
			$strErro = "The back account was not defined";
			throw new BankAccountException($strErro);
		}

		if(!$tripRquestObj){
			$strErro = "The request was not defined";
			throw new BankAccountException($strErro);
		}

		$bankTransactionDomainObj 	= new BanckTransactionDomain();
		$description 				= 'Trip cod nº '.$tripRquestObj->trip->id.' complited';
		$transactionTypeToStore 	= BanckTransaction::getTypeIncrease();
		$bankAccountBalanceNew 		= $bankAccountObj->bank_account_balance + $tripRquestObj->net_trip_amout;

		if($transactionType == 'debit'){
			$transactionTypeToStore = BanckTransaction::getTypeDecrease();
			$bankAccountBalanceNew 	= $bankAccountObj->bank_account_balance - $tripRquestObj->net_trip_amout;
		}

		$transactionCreatedObj = $bankTransactionDomainObj->create(
			[
				'transacion_description' => $description,
				'trip_pay_req_id'		 => $tripRquestObj->id,
				'bank_account_id'		 => $bankAccountObj->id,
				'transaction_type'		 => $transactionTypeToStore,
				'bank_account_balance'	 => $tripRquestObj->net_trip_amout,
			]
		);

		if(!$transactionCreatedObj){
			$strErro = "Something went wrong. It was not possible to create the financial transaction. Please try again or contact support.";
			throw new BankAccountException($strErro);
		}
		


		$dataToBankAccount = [
			'bank_account_balance'=>$bankAccountBalanceNew
		];
		$bankAccountObj->update($dataToBankAccount);
    }
 
}
