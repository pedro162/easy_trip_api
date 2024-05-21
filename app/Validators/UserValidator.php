<?php 

namespace App\Validators;

use Illuminate\Support\Facades\Validator;

class UserValidator{

	/**
     * Validate the basic information to create a new user and return an array with the errors, if they exist
     */

	public static function validateDataToCreateUser(array $data = []):array{
		
		$errors = [];
		$validator = Validator::make($data, [
			'name'=>'required|min:1|max:255',
			'email'=>'required|min:1|max:255|email',
			'password'=>'required|min:1|max:255',
		], [
			'name.required'=>'The user name is required',
			'name.required'=>'The user name needs to have at least :min characters and a maximum :max 255 characters',
			'name.required'=>'The user name needs to have at least :min characters and a maximum :max 255 characters',

			'email.required'=>'The user email is required',
			'email.required'=>'The user email needs to have at least :min characters and a maximum :max 255 characters',
			'email.required'=>'The user email needs to have at least :min characters and a maximum :max 255 characters',
			
			'password.required'=>'The user password is required',
			'password.required'=>'The user password needs to have at least :min characters and a maximum :max 255 characters',
			'password.required'=>'The user password needs to have at least :min characters and a maximum :max 255 characters',
		]);


		if($validator->fails()){
			$errorsObject = $validator->errors();
			$errors = $errorsObject->all();
		}

		return $errors;
	}

	
}
