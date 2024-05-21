<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Builder\UserBuilder;

class AuthController extends Controller
{
	public function __construct(){
        header('Access-Control-Allow-Origin: *');
    }
    
    public function login(Request $request){
        
        $objBuilder      = new UserBuilder();
        $dataToReturn    = $objBuilder->login($request);
        $httpResposeCode = $objBuilder->getHttpResponseCode();
        return response()->json($dataToReturn, $httpResposeCode);
    }

    public function logout(Request $request){

        $objBuilder      = new UserBuilder();
        $dataToReturn    = $objBuilder->logout($request);
        $httpResposeCode = $objBuilder->getHttpResponseCode();
        return response()->json($dataToReturn, $httpResposeCode);
    }

}
