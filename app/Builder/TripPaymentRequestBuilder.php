<?php 

namespace App\Builder;

use Illuminate\Http\Request;
use App\Domain\TripPaymentRequestDomain;
use App\Domain\BanckAccountDomain;
use App\Domain\TripDomain;
use App\Exceptions\TripPaymentRequestException;
use App\Http\Controllers\Controller;

class TripPaymentRequestBuilder extends Builder{
	
	/**
	* Load a list of trip payment request
	*
	* @return array The result of the processing.
	*/
    public function index():array
    {        
        $stCod = 200;

        try {

            \DB::beginTransaction();
            
            $domainObj = new TripPaymentRequestDomain();
            $response = $domainObj->index();

            \DB::commit();

            if(!$response){
                $stCod 		= 404;
                $response 	= [];
            }

            $this->setHttpResponseData($response);
            $this->setHttpResponseState(true);

        } catch (TripPaymentRequestException $e) {

            \DB::rollback();

            $msg  = $e->getMessage();
            //$msg  = $e->getMessage().' - '.$e->getFile().' - '.$e->getLine();
            $this->setHttpResponseData($msg);
            $this->setHttpResponseState(false);
            $stCod = 400;

        }catch (\Exception $e) {

            \DB::rollback();

            $msg  = $e->getMessage();
            //$msg  = $e->getMessage().' - '.$e->getFile().' - '.$e->getLine();
            $this->setHttpResponseData($msg);
            $this->setHttpResponseState(false);
            $stCod = 500;

        }catch (\Error $e) {

            \DB::rollback();

            $msg  = $e->getMessage();
            //$msg  = $e->getMessage().' - '.$e->getFile().' - '.$e->getLine();
            $this->setHttpResponseData($msg);
            $this->setHttpResponseState(false);            
            $stCod = 500;
        }
        
        $this->setHttpResponseCode($stCod);
        return $this->getHttpDataResponseRequest();
    }

    /**
	* Create a new trip payment request
	*
	* @param Request $request An instance of the HTTP request class.
	* @return array The result of the processing.
	*/
    public function storePaymentRequestByTrip(Request $request, string $trip_id):array
    {
        $stCod = 500;

        try {

            \DB::beginTransaction();

            $data 			= $request->all();
            $domainObj      = new TripPaymentRequestDomain();
            $tripDomainObj  = new TripDomain();
            $tripObj        = $tripDomainObj->show($trip_id);
            $response       = $domainObj->create($data, $tripObj);
            
            \DB::commit();

            $this->setHttpResponseData($response);
            $this->setHttpResponseState(true);
            $stCod = 201;

        } catch (TripPaymentRequestException $e) {

            \DB::rollback();

            $msg  = $e->getMessage();      
            $msg  = $e->getMessage().' - '.$e->getFile().' - '.$e->getLine();      
            $this->setHttpResponseData($msg);
            $this->setHttpResponseState(false);
            $stCod = 400;

        }catch (\Exception $e) {

            \DB::rollback();

            $msg  = $e->getMessage();
            $msg  = $e->getMessage().' - '.$e->getFile().' - '.$e->getLine();
            $this->setHttpResponseData($msg);
            $this->setHttpResponseState(false);
            $stCod = 500;

        }catch (\Error $e) {

            \DB::rollback();

            $msg  = $e->getMessage();
            $msg  = $e->getMessage().' - '.$e->getLine().' - '.$e->getFile();
            $this->setHttpResponseData($msg);
            $this->setHttpResponseState(false);
            $stCod = 500;
            
        }

        $this->setHttpResponseCode($stCod);
        return $this->getHttpDataResponseRequest();
    }


    /**
	* Load the specified trip ṕayment request
	*
	* @param string $id The trip payment request ID.
	* @return array The result of the processing.
	*/
    public function show(string $id):array
    {
        $stCod = 200;

        try {

            \DB::beginTransaction();

            $domainObj = new TripPaymentRequestDomain();
            $response = $domainObj->show($id);

            \DB::commit();

            if(!$response){
                $stCod 		= 404;
                $response 	= [];
            }

            if($response){
            	$response->store;
            }

            $this->setHttpResponseData($response);
            $this->setHttpResponseState(true);

        } catch (TripPaymentRequestException $e) {

            \DB::rollback();

            $msg  = $e->getMessage();
            
            $this->setHttpResponseData($msg);
            $this->setHttpResponseState(false);
            $stCod = 400;

        }catch (\Exception $e) {

            \DB::rollback();

            $msg  = $e->getMessage();

            $this->setHttpResponseData($msg);
            $this->setHttpResponseState(false);
            $stCod = 500;

        }catch (\Error $e) {

            \DB::rollback();

            $msg  = $e->getMessage();

            $this->setHttpResponseData($msg);
            $this->setHttpResponseState(false);
            $stCod = 500;
            
        }

        $this->setHttpResponseCode($stCod);
        return $this->getHttpDataResponseRequest();
    }



    /**
	* Update the specified trip payment request
	*
	* @param Request $request An instance of the HTTP request class.
	* @param string $id The trip payment request ID.
	* @return array The result of the processing.
	*/
    public function update(Request $request, string $id):array
    {
        $stCod = 200;

        try {

            \DB::beginTransaction();

            $data 			= $request->all(); 
            $domainObj = new TripPaymentRequestDomain();
            $response       = $domainObj->update($id, $data);

            \DB::commit();

            $this->setHttpResponseData($response);
            $this->setHttpResponseState(true);

        } catch (TripPaymentRequestException $e) {

            \DB::rollback();

            $msg  = $e->getMessage();
            
            $this->setHttpResponseData($msg);
            $this->setHttpResponseState(false);
            $stCod = 400;

        }catch (\Exception $e) {

            \DB::rollback();

            $msg  = $e->getMessage();
            $this->setHttpResponseData($msg);
            $this->setHttpResponseState(false);
            $stCod = 500;

        }catch (\Error $e) {

            \DB::rollback();

            $msg  = $e->getMessage();
            $this->setHttpResponseData($msg);
            $this->setHttpResponseState(false);
            $stCod = 500;
            
        }

        $this->setHttpResponseCode($stCod);
        return $this->getHttpDataResponseRequest();
    }



    /**
	* Delete the specified trip payment request
	*
	* @param string $id The trip payment request ID.
	* @return array The result of the processing.
	*/
    public function destroy(string $id):array
    {        
        $stCod = 200;

        try {

            \DB::beginTransaction();
            
            $domainObj = new TripPaymentRequestDomain();
            $response       = $domainObj->destroy($id);

            \DB::commit();

            $msg   = 'Trip payment request removed successfully';
            $this->setHttpResponseData($msg);
            $this->setHttpResponseState(true);

        } catch (TripPaymentRequestException $e) {

            \DB::rollback();

            $msg  = $e->getMessage();            
            $this->setHttpResponseData($msg);
            $this->setHttpResponseState(false);
            $stCod = 400;

        }catch (\Exception $e) {

            \DB::rollback();

            $msg = $e->getMessage();
            $this->setHttpResponseData($msg);
            $this->setHttpResponseState(false);
            $stCod = 500;

        }catch (\Error $e) {

            \DB::rollback();

            $msg  = $e->getMessage();
            $this->setHttpResponseData($msg);
            $this->setHttpResponseState(false);
            $stCod 					= 500;
            
        }

        $this->setHttpResponseCode($stCod);
        return $this->getHttpDataResponseRequest();
    }


    /**
	* Authorize the specified trip payment request
	*
	* @param Request $request An instance of the HTTP request class.
	* @param string $id The trip payment request ID.
	* @return array The result of the processing.
	*/
    public function authorizePaymentRequest(Request $request, string $id):array
    {
        $stCod = 200;

        try {

            \DB::beginTransaction();

            $data = $request->all();            
            
            $domainObj 		= new TripPaymentRequestDomain();
            $tripRequestObj 		= $domainObj->authorizePaymentRequest($id);
            $tripObj 				= $tripRequestObj->trip();
            $userBenefitObj 		= $tripRequestObj->beneficUser();
            $tripDriverObj			= $tripObj->driver();
            $tripClientObj			= $tripObj->client();

            if($userBenefitObj->id == $tripDriverObj->id){
            	//'driver', 'customer'
            	$driverAccountObj 	= $tripDriverObj->account();
            	$bkAccountDomain 	= new BanckAccountDomain();
            	$bkAccountDomain->makeTransaction($driverAccountObj, $tripRequestObj, 'credit');

            }elseif($userBenefitObj->id == $tripClientObj->id){
            	//Cliente Bonification
            }

            \DB::commit();

            $this->setHttpResponseData($tripRequestObj);
            $this->setHttpResponseState(true);

        } catch (TripPaymentRequestException $e) {

            \DB::rollback();

            $msg  = $e->getMessage();            
            $this->setHttpResponseData($msg);
            $this->setHttpResponseState(false);
            $stCod = 400;

        }catch (\Exception $e) {

            \DB::rollback();

            $msg  = $e->getMessage();
            $this->setHttpResponseData($msg);
            $this->setHttpResponseState(false);
            $stCod = 500;

        }catch (\Error $e) {

            \DB::rollback();

            $msg  = $e->getMessage();
            $this->setHttpResponseData($msg);
            $this->setHttpResponseState(false);
            $stCod = 500;
            
        }

        $this->setHttpResponseCode($stCod);
        return $this->getHttpDataResponseRequest();
    }
    
}
