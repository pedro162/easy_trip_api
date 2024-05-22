<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User; // Importe o modelo de usuário

class TripClientUserTest extends TestCase
{
    protected $baseURL = '/api';
	protected const USER_DRIVER_EMAIL = "admin@gmail.com";
	protected const USER_DRIVER_PASSWORD = "123456";
	protected const USER_CLIENT_EMAIL = "client@gmail.com";
	protected const USER_CLIENT_PASSWORD = "123456";
	protected string $token;
	protected $dataDriverUser;
	protected $dataClientUser;
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
    		'email' => $this->rand.'_'.self::USER_CLIENT_EMAIL,
            'password' => self::USER_CLIENT_PASSWORD,
    	]);
        //$this->showRequestResponse($response);

    	$response->assertStatus(200);
    	$token = $response->json('token');
    	$this->token = $token; 
    	
    	// Test Trip Endpoints
        $this->testTripEndpointsToUserClient();
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
    private function testTripEndpointsToUserClient():void{

    	$response = $this->getJson($this->baseURL.'/trip/index',$this->getHttpRequestHeader());
    	//$this->showRequestResponse($response);
    	$response->assertStatus(200);

    	//Teste creating a new trip
    	$data 	 = $this->testCreateTrip();
		$trip_id = $data['id'] 	?? null;

		// Test showing a specific trip
        $data = $this->testShowTrip($trip_id);

        // Test updating a specific trip
        $data = $this->testUpdateTripAddress($trip_id);

        // Test starting a specific trip
        $data = $this->testStartTrip($trip_id);

        // Test starting a specific trip
        $data = $this->testCompliteTrip($trip_id);

        // Test deleting a specific trip
        $tripState = $data['trip_state'] ?? null;
        
        switch (trim($tripState)) {
        	case 'waiting':
            case 'canceled':
        		$this->testDeleteTrip($trip_id);
        		break;
        	case 'finished':
        	case 'started':
        		$this->testDeleteTripAlreadyStarted($trip_id);
        		break;
        	
        	default:
        		$this->testDeleteTrip($trip_id);
        		break;
        }
        
    }
    /**
    * Test load a specific trip.
    *
    * @param string $id The trip ID.
    * @return array
    */
    private function testShowTrip($id){
    	$response = $this->getJson($this->baseURL.'/trip/show/' . $id, $this->getHttpRequestHeader());
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
    	$response = $this->postJson($this->baseURL.'/trip/store', [
    		'driver_id'=> null,
			'client_id'=> $this->dataClientUser->id,
			'starting_address'=> 'Starting addrees example',
			'end_address'=> 'Edding addrees example',
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
    * Test update a specific trip.
    *
    * @param string $id The trip ID.
    * @return array
    */
    private function testUpdateTripAddress($id){
    	$response = $this->putJson($this->baseURL.'/trip/update/'. $id, [
			'starting_address'=> 'Starting addrees example updated',
			'end_address'=> 'Edding addrees example updated',
    	], $this->getHttpRequestHeader());
        //print_r($this->baseURL.'/trip/update/'. $id);
        //$this->showRequestResponse($response);

    	$response->assertStatus(200);
    	$content	= $response->getContent(); // Get the content as a JSON string
    	$data 		= json_decode($content, true); // Turn JSON String into an array
    	$data 		= $data['data'] ?? [];
    	$id 		= $data['id'] ?? 0;
    	$this->assertTrue($id > 0);

    	return $data;
    }

    private function testDeleteTrip($id){
    	// Test deleting a specific trip
        $response = $this->deleteJson($this->baseURL.'/trip/destroy/' . $id, $this->getHttpRequestHeader());
        $response->assertStatus(200);
    	$this->assertSoftDeleted('trips', ['id' => $id]);
    }

    /**
    * Test deleting a specific trip  already started.
    *
    * @param string $id The trip ID.
    * @return void
    */
    private function testDeleteTripAlreadyStarted($id):void{
        $response = $this->deleteJson($this->baseURL.'/trip/destroy/' . $id, $this->getHttpRequestHeader());
        //$this->showRequestResponse($response);
        $response->assertStatus(400);
    	$this->assertDatabaseHas('trips', ['id' => $id]);
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
        //$this->showRequestResponse($response);
    	$response->assertStatus(400);
    	$content	= $response->getContent(); // Get the content as a JSON string
    	$data 		= json_decode($content, true); // Turn JSON String into an array
    	$data 		= $data['data'] ?? [];
    	$id 		= $data['id'] ?? 0;
    	$this->assertTrue($id <= 0);

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
        //$this->showRequestResponse($response);
    	$response->assertStatus(400);
    	$content	= $response->getContent(); // Get the content as a JSON string
    	$data 		= json_decode($content, true); // Turn JSON String into an array
    	$data 		= $data['data'] ?? [];
    	$id 		= $data['id'] ?? 0;
    	$this->assertTrue($id <= 0);

    	return $data;
    }

    /**
    * Teste cancel the informed trip.
    *
    * @param string $id The trip ID.
    * @return array
    */
    private function testCancelTrip($id){

    	$response = $this->putJson($this->baseURL.'/trip/cancel/'. $id, [
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
    protected function showRequestResponse($response):void{
    	$content = $response->getContent(); // Get the content as a JSON string
		$data = json_decode($content, true); // Turn JSON String into an array

		$message = $data['data'] ?? [];
		echo 'Response: ';
		echo '<pre>';
		print_r($message);
		echo '</pre>';

        /*echo 'data: ';
        echo '<pre>';
        print_r($data);
        echo '</pre>';*/
    }
}
