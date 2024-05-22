<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User; // Importe o modelo de usuário

class TripPaymentRequestTest extends TestCase
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
    	$data 		= $data['data'] ?? [];

    	$token = $response->json('token');
    	$this->token = $token; 
    	$this->dataDriveUserLogged = $data;
    	
    	// Test TripPaymentRequest Endpoints
        $this->testTripPaymentRequestEndpointsToUserDriver();
        $this->logout();
    }

    private function logout(){
        $response = $this->postJson($this->baseURL.'/logout', $this->getHttpRequestHeader());
        $response->assertStatus(200);
        $content    = $response->getContent(); // Get the content as a JSON string
        $data       = json_decode($content, true); // Turn JSON String into an array
        $data       = $data['data_user'] ?? [];
    	$this->token = ''; 
        $this->dataDriveUserLogged = $data;
    }
    

    /**
    * Test Store Endpoints.
    *
    * @param  string  $token
    * @return void
    */
    private function testTripPaymentRequestEndpointsToUserDriver(){

    	$response = $this->getJson($this->baseURL.'/trip/payment/request/index',$this->getHttpRequestHeader());
    	//$this->showRequestResponse($response);
    	$response->assertStatus(200);

    	//Teste creating a new trip
    	$data 	  = $this->testCreateTrip();
    	$trip_id  = $data['id'] ?? null;

    	//Test starting the trip
    	$data = $this->testStartTrip($trip_id);

    	// Test completing a specific trip
        $data = $this->testCompliteTrip($trip_id);

    	//Teste creating a new trip payment request
    	$dataTripRequest = $this->testCreateTripPaymentRequest($trip_id);
		$trip_pay_request_id  = $dataTripRequest['id'] ?? null;

		// Test showing a specific trip payment request
        $dataTripRequest = $this->testShowTripPaymentRequest($trip_pay_request_id);

        $requestState  = $dataTripRequest['request_state'] ?? null;
        //waiting', 'canceled', 'approved
        switch (trim($requestState)) {
        	case 'waiting':
		        // Test updating a specific trip payment request
		        $this->testUpdateTripPaymentRequest($trip_pay_request_id);  
		        $this->testDeleteTripPaymentRequest($trip_pay_request_id);
        		break;
        	case 'canceled':
        		// Test updating a specific trip payment request
		        $this->testUpdateTripPaymentRequestThatIsNotWaiting($trip_pay_request_id);  
        		$this->testDeleteTripPaymentRequest($trip_pay_request_id);
        		break;
        	case 'approved':
        		// Test updating a specific trip payment request
		        $this->testUpdateTripPaymentRequestThatIsNotWaiting($trip_pay_request_id);  
        		$this->testDeleteTripPaymentRequestAlreadyApproved($trip_pay_request_id);
        		break;
        	default:
        		// Test updating a specific trip payment request
		        $this->testUpdateTripPaymentRequestThatIsNotWaiting($trip_pay_request_id);  
        		$this->testDeleteTripPaymentRequest($trip_pay_request_id);
        		break;
        }   

    }
   
   /**
    * Teste load the informed trip.
    *
    * @param string $id The trip ID.
    * @return array
    */
    private function testShowTripPaymentRequest($id){
    	$response = $this->getJson($this->baseURL.'/trip/payment/request/show/' . $id, $this->getHttpRequestHeader());
        $response->assertStatus(200);

    	$content 	= $response->getContent(); // Get the content as a JSON string
    	$data 		= json_decode($content, true); // Turn JSON String into an array
    	$data 		= $data['data'] ?? [];
    	$id 		= $data['id'] ?? 0;
    	$this->assertTrue($id > 0);

    	return $data;
    }

    /**
    * Teste creating a new trip
    *
    * @param string $id The trip ID.
    * @return array
    */
    private function testCreateTrip(){
    	//'waiting', 'canceled', 'started', 'finished'
    	$response = $this->postJson($this->baseURL.'/trip/store', [
    		'driver_id'=> null,
			'client_id'=> $this->dataClientUser->id,
			'starting_address'=> 'Starting addrees example',
			'end_address'=> 'Edding addrees example',
    	], $this->getHttpRequestHeader());

    	$response->assertStatus(201);

    	$content 	= $response->getContent(); // Get the content as a JSON string
    	$data 		= json_decode($content, true); // Turn JSON String into an array
    	$data 		= $data['data'] ?? [];
    	$id 		= $data['id'] 	?? 0;
    	$this->assertTrue($id > 0);

    	return $data;
    }

    /**
    * Teste creating a new trip payment request
    *
    * @param string $id The trip ID.
    * @return array
    */
    private function testCreateTripPaymentRequest($trip_id){
    	//Teste creating a new trip
    	$response = $this->postJson($this->baseURL.'/trip/payment/request/store/'.$trip_id, [
    		//---add any field
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
    * Teste updating the informed trip payment request
    *
    * @param string $id The trip ID.
    * @return array
    */
    private function testUpdateTripPaymentRequest($id){
    	$response = $this->putJson($this->baseURL.'/trip/payment/request/update/'. $id, [
			'reques_description'=> 'Travel payment request description for update',
    	], $this->getHttpRequestHeader());
    	//$this->showRequestResponse($response);
    	$response->assertStatus(200);
    	$content	= $response->getContent(); // Get the content as a JSON string
    	$data 		= json_decode($content, true); // Turn JSON String into an array
    	$data 		= $data['data'] ?? [];
    	$id 		= $data['id'] ?? 0;
    	$this->assertTrue($id > 0);

    	return $data;
    }

    /**
    * Teste updating the informed trip payment request
    *
    * @param string $id The trip ID.
    * @return array
    */
    private function testUpdateTripPaymentRequestThatIsNotWaiting($id){
    	$response = $this->putJson($this->baseURL.'/trip/payment/request/update/'. $id, [
			'reques_description'=> 'Travel payment request description for update',
    	], $this->getHttpRequestHeader());

    	$response->assertStatus(400);
    	$content	= $response->getContent(); // Get the content as a JSON string
    	$data 		= json_decode($content, true); // Turn JSON String into an array
    	$data 		= $data['data'] ?? [];
    	$id 		= $data['id'] ?? 0;
    	$this->assertTrue($id <= 0);

    	return $data;
    }

    private function testDeleteTripPaymentRequest($id){
    	// Test deleting a specific trip
        $response = $this->deleteJson($this->baseURL.'/trip/payment/request/destroy/' . $id, $this->getHttpRequestHeader());
        $response->assertStatus(200);
    	$this->assertSoftDeleted('trip_payment_requests', ['id' => $id]);
    }

    private function testDeleteTripPaymentRequestAlreadyApproved($id){
    	// Test deleting a specific trip
        $response = $this->deleteJson($this->baseURL.'/trip/payment/request/destroy/' . $id, $this->getHttpRequestHeader());
        $response->assertStatus(400);
    	$this->assertDatabaseHas('trip_payment_requests', ['id' => $id]);
    }

    /**
    * Teste start the informed trip.
    *
    * @param string $id The trip ID.
    * @return array
    */
    private function testStartTrip($id){

    	$response = $this->putJson($this->baseURL.'/trip/start/'. $id, [
    		//add any field to update
    	], $this->getHttpRequestHeader());

    	$response->assertStatus(200);
    	$content	= $response->getContent(); // Get the content as a JSON string
    	$data 		= json_decode($content, true); // Turn JSON String into an array
    	$data 		= $data['data'] ?? [];
    	$id 		= $data['id'] ?? 0;
    	$this->assertTrue($id > 0);

    	return $data;
    }

    /**
    * Teste complite the informed trip.
    *
    * @param string $id The trip ID.
    * @return array
    */
    private function testCompliteTrip($id){

    	$response = $this->putJson($this->baseURL.'/trip/complite/'. $id, [
    		//add any field to update
    	], $this->getHttpRequestHeader());

    	$response->assertStatus(200);
    	$content	= $response->getContent(); // Get the content as a JSON string
    	$data 		= json_decode($content, true); // Turn JSON String into an array
    	$data 		= $data['data'] ?? [];
    	$id 		= $data['id'] ?? 0;
    	$this->assertTrue($id > 0);

    	return $data;
    }

    /**
    * Teste cancel the informed trip.
    *
    * @param string $id The trip ID.
    * @return array
    */
    private function testCancelTripPaymentRequest($id){

    	$response = $this->putJson($this->baseURL.'/trip/payment/request/cancel/'. $id, [
			//add any field to update
    	], $this->getHttpRequestHeader());

    	$response->assertStatus(200);
    	$content	= $response->getContent(); // Get the content as a JSON string
    	$data 		= json_decode($content, true); // Turn JSON String into an array
    	$data 		= $data['data'] ?? [];
    	$id 		= $data['id'] ?? 0;
    	$this->assertTrue($id > 0);

    	return $data;
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

		/*echo 'Response data: ';
		echo '<pre>';
		print_r($data);
		echo '</pre>';*/
    }
}
