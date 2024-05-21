<?php 

namespace App\Builder;

abstract class Builder{
	protected string $httpStateCode;
	protected array $httpDataResponseRequest;


	public function getHttpResponseCode(){
		return $this->httpStateCode;
	}

	public function setHttpResponseCode(string $httpStateCode){
		$this->httpStateCode = 	$httpStateCode;
	}

	public function setHttpResponseData($data){
		$this->httpDataResponseRequest['data'] = $data;
	}

	public function setHttpResponseState($state){
		$this->httpDataResponseRequest['state'] = $state;
	}

	public function getHttpDataResponseRequest():array{
		return $this->httpDataResponseRequest;
	}

	public function setHttpResponseDataRequest(string $key, $data){
		$this->httpDataResponseRequest[$key] = $data;
	}

}

