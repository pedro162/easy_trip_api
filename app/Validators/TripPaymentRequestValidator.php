<?php 

namespace App\Validators;

use Illuminate\Support\Facades\Validator;

class TripPaymentRequestValidator{
	/**
     * Validate the basic informations to create a new book and return an array with the errors, if they exist
     */

	public static function validateDataToCreateTripPaymentRequest(array $data = []):array{
		
		$errors = [];
		$validator = Validator::make($data, [
			'reques_description'=>'required|min:1|max:255',
			'trip_amout'=>['required', 'regex:/^\d+(\.\d{1,6})?$/'],
			'trip_tax'=>['required', 'regex:/^\d+(\.\d{1,6})?$/'],
			'net_trip_amout'=>['required', 'regex:/^\d+(\.\d{1,6})?$/'],
			'trip_id'=>['required','regex:/^\d+$/'],
			'user_benefic_id'=>['required','regex:/^\d+$/'],
			'user_equest_id'=>['required','regex:/^\d+$/'],
		],
		[
			'reques_description.required'=>'The trip payment desacription field is required',
			'reques_description.required'=>'The trip payment desacription field needs to have at least 1 characters and a maximum 255 characters',
			'reques_description.required'=>'The trip payment desacription field needs to have at least 1 characters and a maximum 255 characters',

			'trip_amout.required'=>'The trip amout field is required',
			'trip_amout.regex'=>'The trip amout needs to be in a decimal format',

			'trip_tax.required'=>'The net trip taxt field is required',
			'trip_tax.regex'=>'The net trip taxt needs to be in a decimal format',

			'net_trip_amout.required'=>'The net trip amout field is required',
			'net_trip_amout.regex'=>'The net trip amout needs to be in a decimal format',

			'trip_id.required'=>'The trip ID field is required',
			'trip_id.regex'=>'The trip ID field just acept numeric values',

			'user_benefic_id.required'=>'The user beneficiary ID field is required',
			'user_benefic_id.regex'=>'The user beneficiary ID field just acept numeric values',

			'user_equest_id.required'=>'The requesting user ID field is required',
			'user_equest_id.regex'=>'The requesting user ID field just acept numeric values',
		]);

		if($validator->fails()){
			$errorsObject = $validator->errors();
			$errors = $errorsObject->all();
		}

		return $errors;
	}

	/**
     * Validate the basic informations to update a new book and return an array with the errors, if they exist
     */

	public static function validateDataToUpdateTripPaymentRequest(array $data = []):array{
		
		$errors = [];
		$validator = Validator::make($data, [
			'reques_description'=>'required|min:1|max:255',
		],
		[
			'reques_description.required'=>'The trip payment desacription field is required',
			'reques_description.required'=>'The trip payment desacription field needs to have at least 1 characters and a maximum 255 characters',
			'reques_description.required'=>'The trip payment desacription field needs to have at least 1 characters and a maximum 255 characters',
		]);

		if($validator->fails()){
			$errorsObject = $validator->errors();
			$errors = $errorsObject->all();
		}

		return $errors;
	}
}
