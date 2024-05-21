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


            $dataToReturn['data']   = $response;
            $dataToReturn['state']  = true;
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
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $dataToReturn = [
            'data'  => [],
            'state'=> false
        ];

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
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $dataToReturn = [
            'data'      => [],
            'status'    => false
        ];

        $stCod = 200;

        try {

            \DB::beginTransaction();

            $data = $request->all();            
            
            $storeDomainObj = new UserDomain();
            $response = $storeDomainObj->update($id, $data);

            \DB::commit();

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
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $dataToReturn = [
            'data'      => [],
            'status'    => false
        ];

        $stCod = 200;
        
        try {

            \DB::beginTransaction();
                 
            $storeDomainObj = new UserDomain();
            $response = $storeDomainObj->destroy($id);

            \DB::commit();

            $dataToReturn['data']   = 'Store removed successfully';
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


    public function login(Request $request){
        $dataToReturn = [
            'token'     => null,
            'data'      => null,
            'status'    => false
        ];
        $stCod = 200;

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $token = Auth::user()->createToken('Token Name')->accessToken;
            $dataToReturn['token']  = $token;
            $dataToReturn['data']   = 'Token created';
            $dataToReturn['status'] = true;

            $stCod = 200;

        } else {

            $dataToReturn['token'] = '';
            $dataToReturn['data']  = 'Unauthorized';

            $stCod = 401;
        }

        $this->setHttpResponseCode($stCod);

        return $dataToReturn;
    }

    public function logout(Request $request){
       

        $request->user()->token()->revoke();

         $dataToReturn = [
            'token'     => null,
            'data'      => 'Successfully logged out',
            'status'    => false
        ];
        $stCod = 200;

        $this->setHttpResponseCode($stCod);

        return $dataToReturn;
    }
}
