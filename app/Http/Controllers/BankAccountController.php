<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Builder\BankAccountBuilder;

class BankAccountController extends Controller
{
    public function __construct(){
        header('Access-Control-Allow-Origin: *');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $builderObj       = new BankAccountBuilder();
        $dataToReturn    = $builderObj->index();
        $httpResposeCode = $builderObj->getHttpResponseCode();
        return response()->json($dataToReturn, $httpResposeCode);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function storeOwnerAccount(Request $request, string $owner_id)
    {
        $builderObj       = new BankAccountBuilder();
        $dataToReturn    = $builderObj->storeOwnerAccount($request, $owner_id);
        $httpResposeCode = $builderObj->getHttpResponseCode();
        return response()->json($dataToReturn, $httpResposeCode);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $builderObj       = new BankAccountBuilder();
        $dataToReturn    = $builderObj->show($id);
        $httpResposeCode = $builderObj->getHttpResponseCode();
        return response()->json($dataToReturn, $httpResposeCode);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $builderObj       = new BankAccountBuilder();
        $dataToReturn    = $builderObj->update($request, $id);
        $httpResposeCode = $builderObj->getHttpResponseCode();
        return response()->json($dataToReturn, $httpResposeCode);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $builderObj       = new BankAccountBuilder();
        $dataToReturn    = $builderObj->destroy($id);
        $httpResposeCode = $builderObj->getHttpResponseCode();
        return response()->json($dataToReturn, $httpResposeCode);
    }
}
