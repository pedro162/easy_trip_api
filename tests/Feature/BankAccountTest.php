<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class BankAccountTest extends TestCase
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
    	$dataToStore = [
            'email' => $this->rand.'_'.self::USER_DRIVER_EMAIL,
            'password' => bcrypt(self::USER_DRIVER_PASSWORD),
            'user_type' => 'driver',
        ];
        $userOfEmail = User::where('email', '=',trim($dataToStore['email']))->first();

		if(! $userOfEmail){
			// Create a user for testing
        	$userOfEmail = User::factory()->create($dataToStore);
		}

		$this->dataDriverUser = $userOfEmail;
		

		//---Create a user customer----------------
    	$dataToStore = [
            'email' => $this->rand.'_'.self::USER_CLIENT_EMAIL,
            'password' => bcrypt(self::USER_CLIENT_PASSWORD),
            'user_type' => 'customer',
        ];
        $userOfEmail = User::where('email', '=',trim($dataToStore['email']))->first();

		if(! $userOfEmail){
			// Create a user for testing
        	$userOfEmail = User::factory()->create($dataToStore);
		}

		$this->dataClientUser = $userOfEmail;
    }

    /**
    * Test API endpoints.
    *
    * @return void
    */
    public function testEndpoints():void{
    	$response = $this->postJson($this->baseURL.'/login', [
    		'email' => $this->rand.'_'.self::USER_DRIVER_EMAIL,
            'password' => self::USER_DRIVER_PASSWORD,
    	]);

    	$response->assertStatus(200);
    	$content 	= $response->getContent(); // Get the content as a JSON string
    	$data 		= json_decode($content, true); // Turn JSON String into an array
    	$data 		= $data['data_user'] ?? [];

    	$token = $response->json('token');
    	$this->token = $token; 
    	$this->dataDriveUserLogged = $data;
    	
    	// Test BankAccount Endpoints
        $this->testAccountEndpointsToUserDriver();
        $this->logout();
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
    private function testAccountEndpointsToUserDriver(){

    	$response = $this->getJson($this->baseURL.'/bank/account/index',$this->getHttpRequestHeader());
    	//$this->showRequestResponse($response);
    	$response->assertStatus(200);

    	//Teste creating a new bank account
    	$owner_id 		= $this->dataDriveUserLogged['id'];
    	$dataAccount 	= $this->testCreateBankAccount($owner_id);
		$account_id  	= $dataAccount['id'] ?? null;

		// Test showing a specific bank account
        $dataAccount = $this->testShowBankAccount($account_id);

        // Test updating a specific bank account
        $dataAccount = $this->testUpdateBankAccount($account_id);  
        // Test updating a specific bank account
        $bankAccountBalance  = $dataAccount['bank_account_balance'] ?? 0;
        if($bankAccountBalance > 0){
        	$this->testDeleteBankAccountWithBalance($account_id);
        }else{
        	$this->testDeleteBankAccount($account_id);
        }  

    }
   
   /**
    * Teste load the informed trip.
    *
    * @param string $id The trip ID.
    * @return array
    */
    private function testShowBankAccount($id){
    	$response = $this->getJson($this->baseURL.'/bank/account/show/' . $id, $this->getHttpRequestHeader());
        $response->assertStatus(200);

    	$content 	= $response->getContent(); // Get the content as a JSON string
    	$data 		= json_decode($content, true); // Turn JSON String into an array
    	$data 		= $data['data'] ?? [];
    	$id 		= $data['id'] ?? 0;
    	$this->assertTrue($id > 0);

    	return $data;
    }

    /**
    * Teste creating a new bank account
    *
    * @param string $id The trip ID.
    * @return array
    */
    private function testCreateBankAccount($owner_id){
    	//Teste creating a new trip
    	$response = $this->postJson($this->baseURL.'/bank/account/store/'.$owner_id, [
    		'bank_branch'=>'6500',
			'bank_account_number'=>'1234567893',
			'bank_account_digit'=>'1',
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
    * Teste updating the informed bank account
    *
    * @param string $id The trip ID.
    * @return array
    */
    private function testUpdateBankAccount($id){
    	$response = $this->putJson($this->baseURL.'/bank/account/update/'. $id, [
			'bank_branch'=>'6600',
			'bank_account_number'=>'0987654321',
			'bank_account_digit'=>'0',
    	], $this->getHttpRequestHeader());
    	print_r($this->baseURL.'/bank/account/update/'. $id);
    	//$this->showRequestResponse($response);
    	$response->assertStatus(200);
    	$content	= $response->getContent(); // Get the content as a JSON string
    	$data 		= json_decode($content, true); // Turn JSON String into an array
    	$data 		= $data['data'] ?? [];
    	$id 		= $data['id'] ?? 0;
    	$this->assertTrue($id > 0);

    	return $data;
    }

    private function testDeleteBankAccount($id){
    	// Test deleting a specific trip
        $response = $this->deleteJson($this->baseURL.'/bank/account/destroy/' . $id, $this->getHttpRequestHeader());
        $response->assertStatus(200);
    	$this->assertSoftDeleted('bank_accounts', ['id' => $id]);
    }

    private function testDeleteBankAccountWithBalance($id){
    	// Test deleting a specific trip
        $response = $this->deleteJson($this->baseURL.'/bank/account/destroy/' . $id, $this->getHttpRequestHeader());
        $response->assertStatus(400);
    	$this->assertDatabaseHas('bank_accounts', ['id' => $id]);
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
    }
}
