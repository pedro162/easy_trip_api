<?php 

namespace App\Validators;

use Illuminate\Support\Facades\Validator;

class TripValidator{
	/**
     * Validate the basic informations to create a new book and return an array with the errors, if they exist
     */

	public static function validateDataToCreateTrip(array $data = []):array{



		$errors = [];
		$validator = Validator::make($data, [
			'starting_address'=>'required|min:1|max:255',
			'end_address'=>'required|min:1|max:255',
		],
		[
			'starting_address.required'=>'The trip starting address field is required',
			'starting_address.required'=>'The trip starting address field needs to have at least 1 characters and a maximum 255 characters',
			'starting_address.required'=>'The trip starting address field needs to have at least 1 characters and a maximum 255 characters',

			'end_address.required'=>'The trip end address field is required',
			'end_address.required'=>'The trip end address field needs to have at least 1 characters and a maximum 255 characters',
			'end_address.required'=>'The trip end address field needs to have at least 1 characters and a maximum 255 characters',
		]);

		if($validator->fails()){
			$errorsObject = $validator->errors();
			$errors = $errorsObject->all();
		}

		return $errors;
	}
}
