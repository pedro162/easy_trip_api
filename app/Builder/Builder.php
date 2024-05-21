<?php 

namespace App\Builder;

abstract class Builder{
	protected string $httpStateCode;
	protected $data;
	protected $state;


	public function getHttpResponseCode(){
		return $this->httpStateCode;
	}

	public function setHttpResponseCode(string $httpStateCode){
		$this->httpStateCode = 	$httpStateCode;
	}

	public function setHttpResponseData($data){
		$this->data = 	$data;
	}

	public function setHttpResponseState($state){
		$this->state = 	$state;
	}

	public function getHttpDataResponseRequest():array{
		return [
			'data'   = $this->data;
            'state'  = $this->state;
		];
	}
}

