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
    public function compliteTheTrip(Request $request, string $id)
    {       
        $bilderObj       = new TripBilder();
        $dataToReturn    = $bilderObj->compliteTheTrip($request, $id);
        $httpResposeCode = $bilderObj->getHttpResponseCode();
        return response()->json($dataToReturn, $httpResposeCode);
    }

    /**
     * Cancel the specified trip in storage.
     */
    public function cancelTheTrip(Request $request, string $id)
    {       
        $bilderObj       = new TripBilder();
        $dataToReturn    = $bilderObj->cancelTheTrip($request, $id);
        $httpResposeCode = $bilderObj->getHttpResponseCode();
        return response()->json($dataToReturn, $httpResposeCode);
    }
}
