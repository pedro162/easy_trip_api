<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Builder\UserBuilder;

class UserController extends Controller
{
    public function __construct(){
        header('Access-Control-Allow-Origin: *');
    }
    

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
        $objBuilder         = new UserBuilder();
        $dataToReturn       = $objBuilder->index();
        $httpResposeCode    = $objBuilder->getHttpResponseCode();
        if(!$httpResposeCode){
            $httpResposeCode = 200;
        }

        return response()->json($dataToReturn, $httpResposeCode);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
        $objBuilder         = new UserBuilder();
        $dataToReturn       = $objBuilder->store($request);
        $httpResposeCode    = $objBuilder->getHttpResponseCode();
        if(!$httpResposeCode){
            $httpResposeCode = 200;
        }

        return response()->json($dataToReturn, $httpResposeCode);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function storeDriver(Request $request)
    {
        
        $objBuilder         = new UserBuilder();
        $dataToReturn       = $objBuilder->storeDriver($request);

        $httpResposeCode = $objBuilder->getHttpResponseCode();
        if(!$httpResposeCode){
            $httpResposeCode = 200;
        }

        return response()->json($dataToReturn, $httpResposeCode);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $objBuilder         = new UserBuilder();
        $dataToReturn       = $objBuilder->show($id);

        $httpResposeCode = $objBuilder->getHttpResponseCode();
        if(!$httpResposeCode){
            $httpResposeCode = 200;
        }

        return response()->json($dataToReturn, $httpResposeCode);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
       
        $objBuilder         = new UserBuilder();
        $dataToReturn       = $objBuilder->update($request, $id);

        $httpResposeCode = $objBuilder->getHttpResponseCode();
        if(!$httpResposeCode){
            $httpResposeCode = 200;
        }

        return response()->json($dataToReturn, $httpResposeCode);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        
        $objBuilder         = new UserBuilder();
        $dataToReturn       = $objBuilder->destroy($id);

        $httpResposeCode = $objBuilder->getHttpResponseCode();
        if(!$httpResposeCode){
            $httpResposeCode = 200;
        }

        return response()->json($dataToReturn, $httpResposeCode);
    }
}
