<?php 

namespace App\Domain;

use App\Validators\TripValidator;
use Illuminate\Support\Facades\Auth; 
use App\Exceptions\TripException;
use App\Models\Trip;

class TripDomain{

	public function index(){

		//----- Select active records ---------------------------------------
		$result = Trip::all();

		return $result;
	}

	public function create(array $data = []){

		//----- Select only the necessary informations ---------------------------
		
		$dataToTrip = $dataToTrip = [
			'driver_id'				=> $data['driver_id'] 			?? '',
			'client_id'				=> $data['client_id'] 			?? '',
			'starting_address'		=> $data['starting_address'] 	?? '',
			'starting_latitude'		=> $data['starting_latitude'] 	?? '',
			'starting_longitude'	=> $data['starting_longitude'] 	?? '',
			'end_address'			=> $data['end_address'] 		?? '',
			'end_latitude'			=> $data['end_latitude'] 		?? '',
			'end_longitude'			=> $data['end_longitude'] 		?? '',
			'driver_rate'			=> $data['driver_rate'] 		?? '',
			'customer_rate'			=> $data['customer_rate'] 		?? '',
			'trip_state'			=> $data['trip_state'] 			?? '',
			'trip_price'			=> $data['trip_price'] 			?? '',
		];

		//----- Validate infomations ----------------------------------------------
		$erros = TripValidator::validateDataToCreateTrip($dataToTrip, new Trip());
		if(is_array($erros) && count($erros) > 0){

			$strErros = implode(', ', $erros);
			throw new TripException($strErros);
		}
		
		$tripObject = Trip::create($dataToTrip);
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
			throw new TripException($strErro);
		}

        //----- Try to load the record -------------------------------------------

		$tripObject = Trip::find($id);
		if(!$tripObject){
			//$strErro = "It was not possible to locale the record of code number {$id}";
			///throw new TripException($strErro);
		}

		return $tripObject;
    }

    protected function buildDataToStoreTrip(array $data = [], Trip $tripObject = null){
		$dataToTrip = [
			'driver_id'				=> $data['driver_id'] 			?? $tripObject->driver_id,
			'client_id'				=> $data['client_id'] 			?? $tripObject->client_id,
			'starting_address'		=> $data['starting_address'] 	?? $tripObject->starting_address,
			'starting_latitude'		=> $data['starting_latitude'] 	?? $tripObject->starting_latitude,
			'starting_longitude'	=> $data['starting_longitude'] 	?? $tripObject->starting_longitude,
			'end_address'			=> $data['end_address'] 		?? $tripObject->end_address,
			'end_latitude'			=> $data['end_latitude'] 		?? $tripObject->end_latitude,
			'end_longitude'			=> $data['end_longitude'] 		?? $tripObject->end_longitude,
			'driver_rate'			=> $data['driver_rate'] 		?? $tripObject->driver_rate,
			'customer_rate'			=> $data['customer_rate'] 		?? $tripObject->customer_rate,
			'trip_state'			=> $data['trip_state'] 			?? $tripObject->trip_state,
			'trip_price'			=> $data['trip_price'] 			?? $tripObject->trip_price,
		];

		return $dataToTrip;
	}


	/**
     * Update the specified resource in storage.
     */
	public function update(string $id , array $data = []){
		$id = (int) $id;

		if(!($id > 0)){
			$strErro = "The record code informaded isn't valid {$id}";
			throw new TripException($strErro);
		}

		//----- Try to load the record -------------------------------------------

		$tripObject = Trip::find($id);
		if(!$tripObject){
			$strErro = "It was not possible to locale the record of code number {$id}";
			throw new TripException($strErro);
		}

		//----- Select only the necessary informations ---------------------------
		
		$dataToTrip = $this->buildDataToStoreTrip($data, $tripObject);

		//----- Validate infomations ----------------------------------------------
		$erros = TripValidator::validateDataToCreateTrip($dataToTrip);
		if(is_array($erros) && count($erros) > 0){
			
			$strErros = implode(', ', $erros);
			throw new TripException($strErros);
		}

		//----- Update to load the record -------------------------------------------
		$tripObject->update($dataToTrip);

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
			throw new TripException($strErro);
		}

    	//----- Try to load the record -------------------------------------------
        $tripObject = Trip::find($id);
		if(!$tripObject){
			$strErro = "It was not possible to locale the trip of code number {$id}";
			throw new TripException($strErro);
		}
		
		//---- I'm using soft delete, that's why I can do this ------------
		$tripObject->delete();

		return true;
    }

    public function startTrip(string $id, array $data=[]){

    	//----- Try to load the trip record -------------------------------------------
        $tripObject = Trip::find($id);
		if(!$tripObject){
			$strErro = "It was not possible to locale the trip of code number {$id}";
			throw new TripException($strErro);
		}

		//----- Update to load the record -------------------------------------------
		$dataToTrip = [
			'trip_state'=>'started'
		];
		$tripObject->update($dataToTrip);
		
		return $tripObject;
    }

    public function cancelTrip(string $id, array $data=[]){

    	//----- Try to load the trip record -------------------------------------------
        $tripObject = Trip::find($id);
		if(!$tripObject){
			$strErro = "It was not possible to locale the trip of code number {$id}";
			throw new TripException($strErro);
		}
		//----- Update to load the record -------------------------------------------
		$dataToTrip = [
			'trip_state'=>'canceled'
		];
		$tripObject->update($dataToTrip);
		
		return $tripObject;
    }

    public function compliteTrip(string $id, array $data=[]){

    	//----- Try to load the trip record -------------------------------------------
        $tripObject = Trip::find($id);
		if(!$tripObject){
			$strErro = "It was not possible to locale the trip of code number {$id}";
			throw new TripException($strErro);
		}
		//----- Update to load the record -------------------------------------------
		$dataToTrip = [
			'trip_state'=>'finished'
		];
		$tripObject->update($dataToTrip);
		
		return $tripObject;
    } 
}
