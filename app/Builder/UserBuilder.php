<?php 

namespace App\Builder;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Domain\UserDomain;
use App\Exceptions\StoreException;
use App\Builder\Builder;


class UserBuilder extends Builder{
	/**
     * Display a listing of the resource.
     */
    public function index()
    {
        $dataToReturn = [
            'data'  => [],
            'state' => false
        ];
        $stCod = 200;

        try {

            \DB::beginTransaction();
            
            $storeDomainObj = new UserDomain();
            $response = $storeDomainObj->index();

            \DB::commit();

            if(!$response){
                $stCod 		= 404;
                $response 	= [];
            }

            $dataToReturn['data']   = $response;
            $dataToReturn['state']  = true;


        } catch (StoreException $e) {

            \DB::rollback();

            $msg  = $e->getMessage();
            
            $dataToReturn['data']   = $msg;
            $dataToReturn['state']  = false;
            $stCod 					= 400;

        }catch (\Exception $e) {

            \DB::rollback();

            $msg  = $e->getMessage();

            $dataToReturn['data']   = $msg;
            $dataToReturn['state']  = false;
            $stCod 					= 500;

        }catch (\Error $e) {

            \DB::rollback();

            $msg  = $e->getMessage();
            $dataToReturn['data']   = $msg;
            $dataToReturn['state']  = false;
            $stCod 					= 500;
            
        }

        $this->setHttpResponseCode($stCod);
        return $dataToReturn;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, bool $login=true)
    {
        $dataToReturn = [
            'data'  => [],
            'state'=> false
        ];

        $stCod = 200;

        try {

            \DB::beginTransaction();

            $data = $request->all();            
            
            $storeDomainObj = new UserDomain();
            $response       = $storeDomainObj->create($data);
            
            if($login){
                $canLogin       = $storeDomainObj->getCanLogin();
                
                if($canLogin){
                    $dataToReturn   = $this->login($request);
                }
            }

            \DB::commit();

            $this->setHttpResponseData($response);
            $this->setHttpResponseState(true);
            $stCod                  = 201;

        } catch (StoreException $e) {

            \DB::rollback();

            $msg  = $e->getMessage();
            
            $dataToReturn['data']   = $msg;
            $dataToReturn['state']  = false;
            $stCod 					= 400;

        }catch (\Exception $e) {

            \DB::rollback();

            $msg  = $e->getMessage();

            $dataToReturn['data']   = $msg;
            $dataToReturn['state']  = false;
            $stCod 					= 500;

        }catch (\Error $e) {

            \DB::rollback();

            $msg  = $e->getMessage();
            //$msg  = $e->getMessage().' - '.$e->getLine().' - '.$e->getFile();
            $dataToReturn['data']   = $msg;
            $dataToReturn['state']  = false;
            $stCod 					= 500;            
        }

        $this->setHttpResponseCode($stCod);

        return $dataToReturn;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function storeDriver(Request $request, bool $login=true)
    {
        $stCod = 200;

        try {

            \DB::beginTransaction();

            $data = $request->all();           
            $storeDomainObj = new UserDomain();
            $storeDomainObj->setIsDriver(true);
            $response       = $storeDomainObj->create($data);
            
            if($login){
                $canLogin       = $storeDomainObj->getCanLogin();                
                if($canLogin){
                    $dataToReturn   = $this->login($request);
                }
            }

            \DB::commit();

            $this->setHttpResponseData($response);
            $this->setHttpResponseState(true);
            $stCod                  = 201;

        } catch (StoreException $e) {

            \DB::rollback();

            $msg = $e->getMessage();
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

            $msg = $e->getMessage();
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

            $storeDomainObj = new UserDomain();
            $response = $storeDomainObj->show($id);

            \DB::commit();
            
            if(!$response){
                $stCod 		= 404;
                $response 	= [];
            }
            
            if($response){
            	$response->book;
            }
            
           $this->setHttpResponseData($response);
            $this->setHttpResponseState(true);

        } catch (StoreException $e) {

            \DB::rollback();

            $msg = $e->getMessage();
            $this->setHttpResponseData($msg);
            $this->setHttpResponseState(false);
            $stCod 					= 400;

        }catch (\Exception $e) {

            \DB::rollback();

            $msg = $e->getMessage();
            $this->setHttpResponseData($msg);
            $this->setHttpResponseState(false);
            $stCod = 500;

        }catch (\Error $e) {

            \DB::rollback();
            
            $msg = $e->getMessage();
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

            $data   = $request->all();               
            $storeDomainObj = new UserDomain();
            $response = $storeDomainObj->update($id, $data);

            \DB::commit();

            $this->setHttpResponseData($response);
            $this->setHttpResponseState(true);

        } catch (StoreException $e) {

            \DB::rollback();

            $msg = $e->getMessage();
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

            $msg = $e->getMessage();
            $this->setHttpResponseData($msg);
            $this->setHttpResponseState(false);
            $stCod = 500;
            
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
                 
            $storeDomainObj = new UserDomain();
            $response = $storeDomainObj->destroy($id);

            \DB::commit();

            $msg   = 'User removed successfully';
            $this->setHttpResponseData($msg);
            $this->setHttpResponseState(true);

        } catch (StoreException $e) {

            \DB::rollback();

            $msg = $e->getMessage();
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

            $msg = $e->getMessage();
            $this->setHttpResponseData($msg);
            $this->setHttpResponseState(false);
            $stCod = 500;
            
        }

        $this->setHttpResponseCode($stCod);
        return $this->getHttpDataResponseRequest();
    }


    public function login(Request $request){
        
        $stCod = 200;

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $token = Auth::user()->createToken('Token Name')->accessToken;
            $this->setHttpResponseDataRequest('token', $token);
            $this->setHttpResponseDataRequest('data', 'Token created');
            $this->setHttpResponseDataRequest('status', true);

            $stCod = 200;

        } else {
        
            $this->setHttpResponseDataRequest('token', '');
            $this->setHttpResponseDataRequest('data', 'Unauthorized');
            $this->setHttpResponseDataRequest('status', false);

            $stCod = 401;
        }

        $this->setHttpResponseCode($stCod);
        return $this->getHttpDataResponseRequest();
    }

    public function logout(Request $request){
       

        $request->user()->token()->revoke();
        $this->setHttpResponseDataRequest('token', null);
        $this->setHttpResponseDataRequest('data', 'Successfully logged out');
        $this->setHttpResponseDataRequest('status', true);

        $stCod = 200;

        $this->setHttpResponseCode($stCod);

        return $this->getHttpDataResponseRequest();
    }
}
