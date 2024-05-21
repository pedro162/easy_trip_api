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
        /*$credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $token = Auth::user()->createToken('Token Name')->accessToken;
            return response()->json(['token' => $token], 200);
        } else {
            return response()->json(['error' => 'Unauthorized'], 401);
        }*/

        $objBuilder         = new UserBuilder();
        $dataToReturn       = $objBuilder->login($request);

        $httpResposeCode = $objBuilder->getHttpResponseCode();
        if(!$httpResposeCode){
            $httpResposeCode = 200;
        }

        return response()->json($dataToReturn, $httpResposeCode);
    }

    public function logout(Request $request){
       /* $request->user()->token()->revoke();
        return response()->json(['message' => 'Successfully logged out'], 200);*/

        $objBuilder         = new UserBuilder();
        $dataToReturn       = $objBuilder->logout($request);

        $httpResposeCode = $objBuilder->getHttpResponseCode();
        if(!$httpResposeCode){
            $httpResposeCode = 200;
        }

        return response()->json($dataToReturn, $httpResposeCode);
    }

}
