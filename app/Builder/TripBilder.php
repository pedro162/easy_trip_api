<?php 

namespace App\Builder;

use Illuminate\Http\Request;
use App\Domain\TripDomain;
use App\Domain\BanckAccountDomain;
use App\Exceptions\TripException;
use App\Http\Controllers\Controller;

class TripBilder extends Builder{
	
	
    /**
	* Load a trip list
	*
	* @return array The result of the processing.
	*/
    public function index():array
    {        
        $stCod = 200;

        try {

            \DB::beginTransaction();
            
            $tripDomainObj	= new TripDomain();
            $response 		= $tripDomainObj->index();

            \DB::commit();

            if(!$response){
                $stCod 		= 404;
                $response 	= [];
            }

            $this->setHttpResponseData($response);
            $this->setHttpResponseState(true);

        } catch (TripException $e) {

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
	* Create a new trip
	*
	* @param Request $request An instance of the HTTP request class.
	* @return array The result of the processing.
	*/
    public function store(Request $request):array
    {
        $stCod = 500;

        try {

            \DB::beginTransaction();

            $data = $request->all();
            $tripDomainObj = new TripDomain();
            $response = $tripDomainObj->create($data);
            
            \DB::commit();

            $this->setHttpResponseData($response);
            $this->setHttpResponseState(true);
            $stCod = 201;

        } catch (TripException $e) {

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
	* Load the specified trip
	*
	* @param string $id The trip ID.
	* @return array The result of the processing.
	*/
    public function show(string $id):array
    {
        $stCod = 200;

        try {

            \DB::beginTransaction();

            $tripDomainObj = new TripDomain();
            $response = $tripDomainObj->show($id);

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

        } catch (TripException $e) {

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
	* Update the specified trip
	*
	* @param Request $request An instance of the HTTP request class.
	* @param string $id The trip ID.
	* @return array The result of the processing.
	*/
    public function update(Request $request, string $id):array
    {
        $stCod = 200;

        try {

            \DB::beginTransaction();

            $data 			= $request->all(); 
            $tripDomainObj  = new TripDomain();
            $response       = $tripDomainObj->update($id, $data);

            \DB::commit();

            $this->setHttpResponseData($response);
            $this->setHttpResponseState(true);

        } catch (TripException $e) {

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
	* Delete the specified trip
	*
	* @param string $id The trip ID.
	* @return array The result of the processing.
	*/
    public function destroy(string $id):array
    {        
        $stCod = 200;

        try {

            \DB::beginTransaction();
            
            $tripDomainObj  = new TripDomain();
            $response       = $tripDomainObj->destroy($id);

            \DB::commit();

            $msg   = 'Trip removed successfully';
            $this->setHttpResponseData($msg);
            $this->setHttpResponseState(true);

        } catch (TripException $e) {

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
	* Start the specified trip
	*
	* @param Request $request An instance of the HTTP request class.
	* @param string $id The trip ID.
	* @return array The result of the processing.
	*/
    public function startTrip(Request $request, string $id):array
    {
        $stCod = 200;

        try {

            \DB::beginTransaction();

            $data = $request->all();            
            
            $tripDomainObj 	= new TripDomain();
            $tripRequestObj = $tripDomainObj->startTrip($id);

            \DB::commit();

            $this->setHttpResponseData($tripRequestObj);
            $this->setHttpResponseState(true);

        } catch (TripException $e) {

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
	* Cancel the specified trip
	*
	* @param Request $request An instance of the HTTP request class.
	* @param string $id The trip ID.
	* @return array The result of the processing.
	*/
    public function cancelTrip(Request $request, string $id):array
    {
        $stCod = 200;

        try {

            \DB::beginTransaction();

            $data = $request->all();            
            
            $tripDomainObj 	= new TripDomain();
            $tripRequestObj = $tripDomainObj->cancelTrip($id);

            \DB::commit();

            $this->setHttpResponseData($tripRequestObj);
            $this->setHttpResponseState(true);

        } catch (TripException $e) {

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
	* Complete the specified trip
	*
	* @param Request $request An instance of the HTTP request class.
	* @param string $id The trip ID.
	* @return array The result of the processing.
	*/
    public function compliteTrip(Request $request, string $id):array
    {
        $stCod = 200;

        try {

            \DB::beginTransaction();

            $data = $request->all();            
            
            $tripDomainObj 	= new TripDomain();
            $tripRequestObj = $tripDomainObj->compliteTheTrip($id);

            \DB::commit();

            $this->setHttpResponseData($tripRequestObj);
            $this->setHttpResponseState(true);

        } catch (TripException $e) {

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
