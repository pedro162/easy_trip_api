<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class UserTest extends TestCase
{
    protected $baseURL = '/api';
	protected const USER_DRIVER_EMAIL = "admin@gmail.com";
	protected const USER_DRIVER_PASSWORD = "123456";
	protected const USER_CLIENT_EMAIL = "client@gmail.com";
	protected const USER_CLIENT_PASSWORD = "123456";
	protected string $token;
	protected $dataDriverUser;
	protected $dataClientUser;
	protected $dataDriveUserLogged;
    protected int $rand;

    /**
    * Set up the test environment.
    *
    * @return void
    */
    public function setUp():void{
    	
    	parent::setUp();
    	
    	//---Create a user driver----------------
    	$this->rand = rand(111111,999999);
    }

    /**
    * Test API endpoints.
    *
    * @return void
    */
    public function testEndpoints():void{
    	
    	// Test  Endpoints
        $this->testUserEndpoints();       
    }

    private function login(){
    	$response = $this->postJson($this->baseURL.'/login', [
    		'email' => $this->rand.'_'.self::USER_CLIENT_EMAIL,
            'password' => self::USER_CLIENT_PASSWORD,
    	]);

    	$response->assertStatus(200);
    	$content 	= $response->getContent(); // Get the content as a JSON string
    	$data 		= json_decode($content, true); // Turn JSON String into an array
    	$data 		= $data['data_user'] ?? [];

    	$token = $response->json('token');
    	$this->token = $token; 
    	$this->dataDriveUserLogged = $data;
    }

    private function logout(){
    	$response = $this->postJson($this->baseURL.'/logout', $this->getHttpRequestHeader());

    	$response->assertStatus(200);
    	$content 	= $response->getContent(); // Get the content as a JSON string
    	$data 		= json_decode($content, true); // Turn JSON String into an array
    	$data 		= $data['data_user'] ?? [];
    	$this->token = ''; 
    	$this->dataDriveUserLogged = $data;
    }

    /**
    * Test Store Endpoints.
    *
    * @param  string  $token
    * @return void
    */
    private function testUserEndpoints(){

    	//Teste creating a new user driver
    	$dataUser 	= $this->testCreateUser();
		$user_id  	= $dataUser['id'] ?? null;

    	$response = $this->getJson($this->baseURL.'/user/index',$this->getHttpRequestHeader());
    	//$this->showRequestResponse($response);
    	$response->assertStatus(200);

		// Test showing a specific user
        $dataUser = $this->testShow($user_id);

        // Test updating a specific bank account
        $dataUser = $this->testUpdate($user_id);  
        
        // Test deleting a specific bank account
        $this->testDelete($user_id);
        $this->logout();
    }
   
   /**
    * Teste load the informed trip.
    *
    * @param string $id The trip ID.
    * @return array
    */
    private function testShow($id){
    	$response = $this->getJson($this->baseURL.'/user/show/' . $id, $this->getHttpRequestHeader());
        $response->assertStatus(200);

    	$content 	= $response->getContent(); // Get the content as a JSON string
    	$data 		= json_decode($content, true); // Turn JSON String into an array
    	$data 		= $data['data'] ?? [];
    	$id 		= $data['id'] ?? 0;
    	$this->assertTrue($id > 0);

    	return $data;
    }

    /**
    * Teste creating a new user
    *
    * @param string $id The trip ID.
    * @return array
    */
    private function testCreateUserDriver(){
    	//Teste creating a new user

    	$response = $this->postJson($this->baseURL.'/user/driver/store', [
    		'name'=>'User driver '.$this->rand,
    		'email' => $this->rand.'_'.self::USER_DRIVER_EMAIL,
            'password' => bcrypt(self::USER_DRIVER_PASSWORD),
    	], $this->getHttpRequestHeader());
    	//$this->showRequestResponse($response);
    	$response->assertStatus(201);

    	$content 	= $response->getContent(); // Get the content as a JSON string
    	$data 		= json_decode($content, true); // Turn JSON String into an array
    	$data 		= $data['data'] ?? [];
    	$id 		= $data['id'] 	?? 0;
    	$this->assertTrue($id > 0);

    	return $data;
    }

    /**
    * Teste creating a new bank account
    *
    * @param string $id The trip ID.
    * @return array
    */
    private function testCreateUser(){
    	//Teste creating a new user

    	$response = $this->postJson($this->baseURL.'/user/store', [
    		'name'=>'User cliente '.$this->rand,
    		'email' => $this->rand.'_'.self::USER_CLIENT_EMAIL,
            'password' => bcrypt(self::USER_CLIENT_PASSWORD),
    	], []);
    	//$this->showRequestResponse($response);
    	$response->assertStatus(201);

    	$content 		= $response->getContent(); // Get the content as a JSON string
    	$data 			= json_decode($content, true); // Turn JSON String into an array
    	$this->token 	= $data['token'] ?? '';
    	$data 			= $data['data'] ?? [];
    	$dataUser		= $data['data_user'] ?? [];
    	$id 			= $data['id'] 	?? 0;
    	$this->assertTrue($id > 0);
		$this->dataDriveUserLogged = $dataUser;

    	return $data;
    }

    /**
    * Teste updating the informed bank account
    *
    * @param string $id The trip ID.
    * @return array
    */
    private function testUpdate($id){
    	$response = $this->putJson($this->baseURL.'/user/update/'. $id, [
    		'name'=>'User cliente updated '.$this->rand,
			'email' => $this->rand.'_'.self::USER_CLIENT_EMAIL,
            'password' => bcrypt(self::USER_CLIENT_PASSWORD),
    	], $this->getHttpRequestHeader());
    	//print_r($this->baseURL.'/user/update/'. $id);
    	//$this->showRequestResponse($response);
    	$response->assertStatus(200);
    	$content	= $response->getContent(); // Get the content as a JSON string
    	$data 		= json_decode($content, true); // Turn JSON String into an array
    	$data 		= $data['data'] ?? [];
    	$id 		= $data['id'] ?? 0;
    	$this->assertTrue($id > 0);

    	return $data;
    }

    private function testDelete($id){
    	// Test deleting a specific trip
        $response = $this->deleteJson($this->baseURL.'/user/destroy/' . $id, $this->getHttpRequestHeader());
        $response->assertStatus(200);
    	$this->assertSoftDeleted('users', ['id' => $id]);
    }

    private function testDeleteWithBalance($id){
    	// Test deleting a specific trip
        $response = $this->deleteJson($this->baseURL.'/user/destroy/' . $id, $this->getHttpRequestHeader());
        $response->assertStatus(400);
    	$this->assertDatabaseHas('users', ['id' => $id]);
    }

    /**
    * HTTP request header.
    *
    * @return array
    */
    private function getHttpRequestHeader():array{
    	return [
    		'Authorization'=>'Bearer '.$this->token,
    	];
    }

    /**
     * Show Endpoint response.
     *
     * @param  string  $response
     * @return void
     */
    protected function showRequestResponse($response){
    	$content = $response->getContent(); // Get the content as a JSON string
		$data = json_decode($content, true); // Turn JSON String into an array

		$message = $data['data'] ?? [];
		echo 'Response: ';
		echo '<pre>';
		print_r($message);
		echo '</pre>';

		/*echo 'Response data : ';
		echo '<pre>';
		print_r($data);
		echo '</pre>';*/
    }
}
