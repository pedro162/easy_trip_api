<?php 

namespace App\Validators;

use Illuminate\Support\Facades\Validator;

class BankAccountValidator{
	/**
     * Validate the basic informations to create a new book and return an array with the errors, if they exist
     */

	public static function validateDataToCreateBankAccount(array $data = []):array{

		$errors = [];
		$validator = Validator::make($data, [
			'bank_branch'=>['required', 'regex:/^\d+$/'],
			'bank_account_number'=>['required', 'regex:/^\d+$/'],
			'bank_account_digit'=>['required', 'regex:/^\d+$/'],
			'user_id'=>['required', 'regex:/^\d+$/'],
			'bank_account_balance'=>['required', 'regex:/^\d+(\.\d{1,6})?$/'],
		],
		[
			'bank_branch.required'=>'The bank branch field is required',
			'bank_branch.regex'=>'The bank branch field only accepts numeric values',

			'bank_account_number.required'=>'The bank account number field is required.',
			'bank_account_number.regex'=>'The bank account number field only accepts numeric values.',

			'bank_account_digit.required'=>'The bank account digit field is required.',
			'bank_account_digit.regex'=>'The bank account digit field only accepts numeric values.',

			'user_id.required'=>'The account owner code field is required',
			'user_id.regex'=>'The account owner code field only accepts numeric values',

			'bank_account_balance.required'=>'The account balance field is required',
			'bank_account_balance.regex'=>'The account balance field to be in a decimal format',
		]);

		if($validator->fails()){
			$errorsObject = $validator->errors();
			$errors = $errorsObject->all();
		}

		return $errors;
	}

	/**
     * Validate the basic informations to create a new book and return an array with the errors, if they exist
     */

	public static function validateDataToUpdateBankAccount(array $data = []):array{

		$errors = [];
		$validator = Validator::make($data, [
			'bank_branch'=>['required', 'regex:/^\d+$/'],
			'bank_account_number'=>['required', 'regex:/^\d+$/'],
			'bank_account_digit'=>['required', 'regex:/^\d+$/'],
		],
		[
			'bank_branch.required'=>'The bank branch field is required',
			'bank_branch.regex'=>'The bank branch field only accepts numeric values',

			'bank_account_number.required'=>'The bank account number field is required.',
			'bank_account_number.regex'=>'The bank account number field only accepts numeric values.',

			'bank_account_digit.required'=>'The bank account digit field is required.',
			'bank_account_digit.regex'=>'The bank account digit field only accepts numeric values.',
		]);

		if($validator->fails()){
			$errorsObject = $validator->errors();
			$errors = $errorsObject->all();
		}

		return $errors;
	}

	
}
