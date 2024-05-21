
<?php 

namespace App\Domain;

use App\Validators\TripPaymentRequestValidator;
use Illuminate\Support\Facades\Auth; 
use App\Exceptions\TripPaymentRequestException;
use App\Models\Trip;
use App\Models\TripPaymentRequest;

class TripPaymentRequestDomain{

	public function index(){

		//----- Select active records ---------------------------------------
		$result = TripPaymentRequest::all();

		return $result;
	}

	public function create(array $data = []){

		//----- Select only the necessary informations ---------------------------
		
		$dataToTrip = $this->buildDataToStoreTripPaymentRequest($data);

		//----- Validate infomations ----------------------------------------------
		$erros = TripPaymentRequestValidator::validateDataToCreateTripPaymentRequest($dataToTrip, new TripPaymentRequest());
		if(is_array($erros) && count($erros) > 0){

			$strErros = implode(', ', $erros);
			throw new TripPaymentRequestException($strErros);
		}
		
		$tripPayRequesObject = TripPaymentRequest::create($dataToTrip);
		if(! $tripPayRequesObject){
			throw new UserException("Something went wrong. It was not possible to create a new payment request. Please try again or contact support.");
		}

		return $tripPayRequesObject;
	}


    /**
     * Display the specified resource.
     */
    public function show(string $id){

    	$id = (int) $id;
    	if(!($id > 0)){
			$strErro = "The record code informaded isn't valid {$id}";
			throw new TripPaymentRequestException($strErro);
		}

        //----- Try to load the record -------------------------------------------

		$tripPayRequesObject = TripPaymentRequest::find($id);
		if(!$tripPayRequesObject){
			//$strErro = "It was not possible to locale the record of code number {$id}";
			///throw new TripPaymentRequestException($strErro);
		}

		return $tripPayRequesObject;
    }

    protected function buildDataToStoreTripPaymentRequest(array $data = [], TripPaymentRequest $tripPayRequesObject = null){
		$dataToTrip = [
			'reques_description' 	=>$data['reques_description'] 	?? $tripPayRequesObject->reques_description,
			'trip_id' 				=>$data['trip_id'] 				?? $tripPayRequesObject->trip_id,
			'user_benefic_id' 		=>$data['user_benefic_id'] 		?? $tripPayRequesObject->user_benefic_id,
			'user_equest_id' 		=>$data['user_equest_id'] 		?? $tripPayRequesObject->user_equest_id,
			'user_canceled_id' 		=>$data['user_canceled_id'] 	?? $tripPayRequesObject->user_canceled_id,
			'user_approved_id' 		=>$data['user_approved_id'] 	?? $tripPayRequesObject->user_approved_id,
			'trip_amout' 			=>$data['trip_amout'] 			?? $tripPayRequesObject->trip_amout,
			'trip_tax' 				=>$data['trip_tax'] 			?? $tripPayRequesObject->trip_tax,
			'net_trip_amout' 		=>$data['net_trip_amout'] 		?? $tripPayRequesObject->net_trip_amout,
			'request_state' 		=>$data['request_state'] 		?? $tripPayRequesObject->request_state,
			'approved_at' 			=>$data['approved_at'] 			?? $tripPayRequesObject->approved_at,
			'canceld_at' 			=>$data['canceld_at'] 			?? $tripPayRequesObject->canceld_at,
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
			throw new TripPaymentRequestException($strErro);
		}

		//----- Try to load the record -------------------------------------------

		$tripPayRequesObject = TripPaymentRequest::find($id);
		if(!$tripPayRequesObject){
			$strErro = "It was not possible to locale the record of code number {$id}";
			throw new TripPaymentRequestException($strErro);
		}

		//----- Select only the necessary informations ---------------------------
		
		$dataToTrip = $this->buildDataToStoreTripPaymentRequest($data, $tripPayRequesObject);

		//----- Validate infomations ----------------------------------------------
		$erros = TripPaymentRequestValidator::validateDataToCreateTripPaymentRequest($dataToTrip);
		if(is_array($erros) && count($erros) > 0){
			
			$strErros = implode(', ', $erros);
			throw new TripPaymentRequestException($strErros);
		}

		//----- Update to load the record -------------------------------------------
		$tripPayRequesObject->update($dataToTrip);

		return $tripPayRequesObject;
	}

	/**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

    	$id = (int) $id;

		if(!($id > 0)){
			$strErro = "The record code informad isn't valid {$id}";
			throw new TripPaymentRequestException($strErro);
		}

    	//----- Try to load the record -------------------------------------------
        $tripPayRequesObject = TripPaymentRequest::find($id);
		if(!$tripPayRequesObject){
			$strErro = "It was not possible to locale the payment request payment request of code number {$id}";
			throw new TripPaymentRequestException($strErro);
		}
		
		//---- I'm using soft delete, that's why I can do this ------------
		$tripPayRequesObject->delete();

		return true;
    }

    public function cancelPaymentRequest(string $id, array $data=[]){

    	//----- Try to load the payment request record -------------------------------------------
        $tripPayRequesObject = TripPaymentRequest::find($id);
		if(!$tripPayRequesObject){
			$strErro = "It was not possible to locale the payment request of code number {$id}";
			throw new TripPaymentRequestException($strErro);
		}
		//----- Update to load the record -------------------------------------------
		$dataToTrip = [
			'request_state'		=>'canceled',
			'user_canceled_id'	=>\Auth::User()->id	?? null,
			'canceld_at'		=>date('Y-m-d H:i:s');
		];
		

		$tripPayRequesObject->update($dataToTrip);
		
		return $tripPayRequesObject;
    }

    public function authorizePaymentRequest(string $id, array $data=[]){


    	//----- Try to load the payment request record -------------------------------------------
        $tripPayRequesObject = TripPaymentRequest::find($id);
		if(!$tripPayRequesObject){
			$strErro = "It was not possible to locale the payment request of code number {$id}";
			throw new TripPaymentRequestException($strErro);
		}

		//----- Update to load the record -------------------------------------------
		$dataToTrip = [
			'request_state'		=>'approved',
			'user_approved_id'	=>\Auth::User()->id	?? null,
			'approved_at'		=>date('Y-m-d H:i:s');
		];
		
		$tripPayRequesObject->update($dataToTrip);
		
		return $tripPayRequesObject;
    } 
}
