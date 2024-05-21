<?php 

namespace App\Builder;

use Illuminate\Http\Request;
use App\Domain\BanckTransactionDomain;
use App\Exceptions\BankTransactionException;
use App\Http\Controllers\Controller;

class BankTransactionBilder extends Builder{
	
	
    /**
	* Load a bank transaction list
	*
	* @return array The result of the processing.
	*/
    public function index():array
    {        
        $stCod = 200;

        try {

            \DB::beginTransaction();
            
            $tripDomainObj	= new BanckTransactionDomain();
            $response 		= $tripDomainObj->index();

            \DB::commit();

            if(!$response){
                $stCod 		= 404;
                $response 	= [];
            }

            $this->setHttpResponseData($response);
            $this->setHttpResponseState(true);

        } catch (BankTransactionException $e) {

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
	* Create a new bank transaction
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
            $tripDomainObj = new BanckTransactionDomain();
            $response = $tripDomainObj->create($data);
            
            \DB::commit();

            $this->setHttpResponseData($response);
            $this->setHttpResponseState(true);
            $stCod = 201;

        } catch (BankTransactionException $e) {

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
	* Load the specified bank transaction
	*
	* @param string $id The bank transaction ID.
	* @return array The result of the processing.
	*/
    public function show(string $id):array
    {
        $stCod = 200;

        try {

            \DB::beginTransaction();

            $tripDomainObj = new BanckTransactionDomain();
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

        } catch (BankTransactionException $e) {

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
	* Update the specified bank transaction
	*
	* @param Request $request An instance of the HTTP request class.
	* @param string $id The bank transaction ID.
	* @return array The result of the processing.
	*/
    public function update(Request $request, string $id):array
    {
        $stCod = 200;

        try {

            \DB::beginTransaction();

            $data 			= $request->all(); 
            $tripDomainObj  = new BanckTransactionDomain();
            $response       = $tripDomainObj->update($id, $data);

            \DB::commit();

            $this->setHttpResponseData($response);
            $this->setHttpResponseState(true);

        } catch (BankTransactionException $e) {

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
	* Delete the specified bank transaction
	*
	* @param string $id The bank transaction ID.
	* @return array The result of the processing.
	*/
    public function destroy(string $id):array
    {        
        $stCod = 200;

        try {

            \DB::beginTransaction();
            
            $tripDomainObj  = new BanckTransactionDomain();
            $response       = $tripDomainObj->destroy($id);

            \DB::commit();

            $msg   = 'Bank transaction removed successfully';
            $this->setHttpResponseData($msg);
            $this->setHttpResponseState(true);

        } catch (BankTransactionException $e) {

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
