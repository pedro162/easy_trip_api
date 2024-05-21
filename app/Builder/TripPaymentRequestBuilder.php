<?php 

namespace App\Builder;

use Illuminate\Http\Request;
use App\Domain\TripPaymentRequestDomain;
use App\Domain\BanckAccountDomain;
use App\Exceptions\BookException;
use App\Http\Controllers\Controller;

class TripPaymentRequestBuilder extends Builder{
	
	/**
     * Display a listing of the resource.
     */
    public function index()
    {        
        $stCod = 200;

        try {

            \DB::beginTransaction();
            
            $storeDomainObj = new TripPaymentRequestDomain();
            $response = $storeDomainObj->index();

            \DB::commit();

            if(!$response){
                $stCod 		= 404;
                $response 	= [];
            }

            $dataToReturn['data']   = $response;
            $dataToReturn['state']  = true;


        } catch (BookException $e) {

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

        return $dataToReturn;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $stCod = 500;

        try {

            \DB::beginTransaction();

            $data = $request->all();            
            
            $storeDomainObj = new TripPaymentRequestDomain();
            $response = $storeDomainObj->create($data);

            \DB::commit();


            $dataToReturn['data']   = $response;
            $dataToReturn['state']  = true;

            $stCod = 201;

        } catch (BookException $e) {

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
            //$msg  = $e->getMessage().' - '.$e->getLine().' - '.$e->getFile();
            $this->setHttpResponseData($msg);
            $this->setHttpResponseState(false);

            $stCod = 500;
            
        }

        $this->setHttpResponseCode($stCod);
        return $this->getHttpDataResponseRequest();
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $stCod = 200;

        try {

            \DB::beginTransaction();

            $storeDomainObj = new TripPaymentRequestDomain();
            $response = $storeDomainObj->show($id);

            \DB::commit();

            if(!$response){
                $stCod 		= 404;
                $response 	= [];
            }

            if($response){
            	$response->store;
            }

            $dataToReturn['data']   = $response;
            $dataToReturn['state']  = true;

        } catch (BookException $e) {

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
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $stCod = 200;

        try {

            \DB::beginTransaction();

            $data = $request->all();            
            
            $storeDomainObj = new TripPaymentRequestDomain();
            $response       = $storeDomainObj->update($id, $data);

            \DB::commit();

            $this->setHttpResponseData($response);
            $this->setHttpResponseState(true);

        } catch (BookException $e) {

            \DB::rollback();

            $msg  = $e->getMessage();
            
            $this->setHttpResponseData($msg);
            $this->setHttpResponseState(false);
            $stCod 					= 400;

        }catch (\Exception $e) {

            \DB::rollback();

            $msg  = $e->getMessage();

            $this->setHttpResponseData($msg);
            $this->setHttpResponseState(false);
            $stCod 					= 500;

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
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {        
        $stCod = 200;

        try {

            \DB::beginTransaction();
            
            $storeDomainObj = new TripPaymentRequestDomain();
            $response       = $storeDomainObj->destroy($id);


            \DB::commit();

            $msg   = 'Book removed successfully';
            $this->setHttpResponseData($msg);
            $this->setHttpResponseState(true);

        } catch (BookException $e) {

            \DB::rollback();

            $msg  = $e->getMessage();
            
            $this->setHttpResponseData($msg);
            $this->setHttpResponseState(false);
            $stCod 					= 400;

        }catch (\Exception $e) {

            \DB::rollback();

            $msg  = $e->getMessage();

            $this->setHttpResponseData($msg);
            $this->setHttpResponseState(false);
            $stCod 					= 500;

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
     * Create a new boook and add it within the specified store in storage.
     */
    public function authorizePaymentRequest(Request $request, string $id)
    {
        $stCod = 200;

        try {

            \DB::beginTransaction();

            $data = $request->all();            
            
            $storeDomainObj 		= new TripPaymentRequestDomain();
            $tripRequestObj 		= $storeDomainObj->authorizePaymentRequest($id);
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


            /*
				Incrase driver user account


            */

            \DB::commit();

            $this->setHttpResponseData($response);
            $this->setHttpResponseState(true);

        } catch (BookException $e) {

            \DB::rollback();

            $msg  = $e->getMessage();
            
            $this->setHttpResponseData($msg);
            $this->setHttpResponseState(false);
            $stCod 					= 400;

        }catch (\Exception $e) {

            \DB::rollback();

            $msg  = $e->getMessage();

            $this->setHttpResponseData($msg);
            $this->setHttpResponseState(false);
            $stCod 					= 500;

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
    
}
