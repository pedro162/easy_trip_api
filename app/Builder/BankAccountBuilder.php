<?php 

namespace App\Builder;

use Illuminate\Http\Request;
use App\Domain\BanckAccountDomain;
use App\Exceptions\BankAccountException;
use App\Http\Controllers\Controller;

class BankAccountBuilder extends Builder{
	
	
    /**
	* Load a bank account list
	*
	* @return array The result of the processing.
	*/
    public function index():array
    {        
        $stCod = 200;

        try {

            \DB::beginTransaction();
            
            $tripDomainObj	= new BanckAccountDomain();
            $response 		= $tripDomainObj->index();

            \DB::commit();

            if(!$response){
                $stCod 		= 404;
                $response 	= [];
            }

            $this->setHttpResponseData($response);
            $this->setHttpResponseState(true);

        } catch (BankAccountException $e) {

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
	* Create a new bank account
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
            $tripDomainObj = new BanckAccountDomain();
            $response = $tripDomainObj->create($data);
            
            \DB::commit();

            $this->setHttpResponseData($response);
            $this->setHttpResponseState(true);
            $stCod = 201;

        } catch (BankAccountException $e) {

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
	* Load the specified bank account
	*
	* @param string $id The bank account ID.
	* @return array The result of the processing.
	*/
    public function show(string $id):array
    {
        $stCod = 200;

        try {

            \DB::beginTransaction();

            $tripDomainObj = new BanckAccountDomain();
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

        } catch (BankAccountException $e) {

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
	* Update the specified bank account
	*
	* @param Request $request An instance of the HTTP request class.
	* @param string $id The bank account ID.
	* @return array The result of the processing.
	*/
    public function update(Request $request, string $id):array
    {
        $stCod = 200;

        try {

            \DB::beginTransaction();

            $data 			= $request->all(); 
            $tripDomainObj  = new BanckAccountDomain();
            $response       = $tripDomainObj->update($id, $data);

            \DB::commit();

            $this->setHttpResponseData($response);
            $this->setHttpResponseState(true);

        } catch (BankAccountException $e) {

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
	* Delete the specified bank account
	*
	* @param string $id The bank account ID.
	* @return array The result of the processing.
	*/
    public function destroy(string $id):array
    {        
        $stCod = 200;

        try {

            \DB::beginTransaction();
            
            $tripDomainObj  = new BanckAccountDomain();
            $response       = $tripDomainObj->destroy($id);

            \DB::commit();

            $msg   = 'Bank account removed successfully';
            $this->setHttpResponseData($msg);
            $this->setHttpResponseState(true);

        } catch (BankAccountException $e) {

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
