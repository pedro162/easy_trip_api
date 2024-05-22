<?php 

namespace App\Domain;

use Illuminate\Support\Facades\Auth; 
use App\Exceptions\UserException;
use App\Models\User;
use App\Validators\UserValidator;


class UserDomain{

	protected bool $canLogin;
	protected bool $isDriver = false;

	public function setCanLogin(bool $canLogin){
		$this->canLogin = $canLogin;
	}


	public function getCanLogin(){
		return $this->canLogin;
	}

	public function setIsDriver(bool $isDriver){
		$this->isDriver = $isDriver;
	}


	public function getIsDriver(){
		return $this->isDriver;
	}

	public function index(){
		//----- Select active records ---------------------------------------
		$result = User::all();

		return $result;
	}

	public function create(array $data){
		$dataToStore = [
			'name'			=>$data['name'],
			'email'			=>$data['email'],
			'password'		=>bcrypt($data['password']),
	        'license_plate'	=>$data['license_plate'] 	?? null,
	        'driver_rate'	=>$data['driver_rate'] 		?? null,
	        'customer_rate'	=>$data['customer_rate'] 	?? null,
		];

		if($this->getIsDriver()){
			$dataToStore['user_type'] = 'driver';
		}else{
			$dataToStore['user_type'] = 'customer';
		}

		//----- Validate infomations ----------------------------------------------
		$erros = UserValidator::validateDataToCreateUser($dataToStore);
		if(is_array($erros) && count($erros) > 0){
			
			$strErros = implode(', ', $erros);
			throw new UserException($strErros);
		}

		$userOfEmail = User::where('email', '=',trim($dataToStore['email']))->first();

		if($userOfEmail){
			throw new UserException("The e-mail informed already exist");
		}

		$result = User::create($dataToStore);
		if(! $result){
			throw new UserException("Something went wrong. It was not possible to create a new user. Please try again or contact support");
		}
		
		$this->setCanLogin(true);

		return $result;
	}

    /**
     * Display the specified resource.
     */
    public function show(string $id){

    	$id = (int) $id;
    	if(!($id > 0)){
			$strErro = "The record code informaded isn't valid {$id}";
			throw new UserException($strErro);
		}

        //----- Try to load the record -------------------------------------------
		$storeObject = User::find($id);
		if(!$storeObject){
			//$strErro = "It was not possible to locale the record of code number {$id}";
			///throw new UserException($strErro);
		}

		return $storeObject;
    }


	/**
     * Update the specified resource in storage.
     */
	public function update(string $id , array $data = []){

		$id = (int) $id;
		if(!($id > 0)){
			$strErro = "The record code informaded isn't valid {$id}";
			throw new UserException($strErro);
		}

		//----- Try to load the record -------------------------------------------
		$storeObject = User::find($id);
		if(!$storeObject){
			$strErro = "It was not possible to locale the record of code number {$id}";
			throw new UserException($strErro);
		}

		//----- Select only the necessary informations ---------------------------
		$dataToStore = [
			'name'			=>$data['name'] 			?? $storeObject->name,
			'email'			=>$data['email'] 			?? $storeObject->email,
			'password'		=>$data['password'] 		?? '',
			'user_type'		=>$data['user_type'] 		?? $storeObject->user_type,
	        'license_plate'	=>$data['license_plate'] 	?? $storeObject->license_plate,
	        'driver_rate'	=>$data['driver_rate'] 		?? $storeObject->driver_rate,
	        'customer_rate'	=>$data['customer_rate'] 	?? $storeObject->customer_rate,
		];

		//----- Validate infomations ----------------------------------------------
		$erros = UserValidator::validateDataToCreateUser($dataToStore);
		if(is_array($erros) && count($erros) > 0){
			
			$strErros = implode(', ', $erros);
			throw new UserException($strErros);
		}

		$dataToStore['password'] = bcrypt($dataToStore['password']);
		//----- Update to load the record -------------------------------------------
		$storeObject->update($dataToStore);

		return $storeObject;
	}

	/**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

    	$id = (int) $id;

		if(!($id > 0)){
			$strErro = "The record code informad isn't valid {$id}";
			throw new UserException($strErro);
		}

    	//----- Try to load the record -------------------------------------------
        $storeObject = User::find($id);
		if(!$storeObject){
			$strErro = "It was not possible to locale the store of code number {$id}";
			throw new UserException($strErro);
		}
				
		//---- I'm using soft delete, that's why I can do this ------------
		$storeObject->delete();

		return true;
    }

}
