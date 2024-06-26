<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Builder\TripBilder;

class TripController extends Controller
{
    public function __construct(){
        header('Access-Control-Allow-Origin: *');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {       
        $bilderObj       = new TripBilder();
        $dataToReturn    = $bilderObj->index();
        $httpResposeCode = $bilderObj->getHttpResponseCode();
        return response()->json($dataToReturn, $httpResposeCode);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {        
        $bilderObj       = new TripBilder();
        $dataToReturn    = $bilderObj->store($request);
        $httpResposeCode = $bilderObj->getHttpResponseCode();
        return response()->json($dataToReturn, $httpResposeCode);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {        
        $bilderObj       = new TripBilder();
        $dataToReturn    = $bilderObj->show($id);
        $httpResposeCode = $bilderObj->getHttpResponseCode();
        return response()->json($dataToReturn, $httpResposeCode);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {      
        //return response()->json(['id'=>$id], 400); 
        $bilderObj       = new TripBilder();
        $dataToReturn    = $bilderObj->update($request, $id);
        $httpResposeCode = $bilderObj->getHttpResponseCode();        
        return response()->json($dataToReturn, $httpResposeCode);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {        
        $bilderObj       = new TripBilder();
        $dataToReturn    = $bilderObj->destroy($id);
        $httpResposeCode = $bilderObj->getHttpResponseCode();
        return response()->json($dataToReturn, $httpResposeCode);
    }

    /**
     * Complite the specified trip in storage.
     */
    public function startTrip(Request $request, string $id)
    {       
        $bilderObj       = new TripBilder();
        $dataToReturn    = $bilderObj->startTrip($request, $id);
        $httpResposeCode = $bilderObj->getHttpResponseCode();
        return response()->json($dataToReturn, $httpResposeCode);
    }/**
     * Complite the specified trip in storage.
     */
    public function compliteTrip(Request $request, string $id)
    {       
        $bilderObj       = new TripBilder();
        $dataToReturn    = $bilderObj->compliteTrip($request, $id);
        $httpResposeCode = $bilderObj->getHttpResponseCode();
        return response()->json($dataToReturn, $httpResposeCode);
    }

    /**
     * Cancel the specified trip in storage.
     */
    public function cancelTrip(Request $request, string $id)
    {       
        $bilderObj       = new TripBilder();
        $dataToReturn    = $bilderObj->cancelTrip($request, $id);
        $httpResposeCode = $bilderObj->getHttpResponseCode();
        return response()->json($dataToReturn, $httpResposeCode);
    }
}
