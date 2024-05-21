<?php

namespace App\Http\Controllers;

//use Illuminate\Http\Request;
use Illuminate\Http\Request;
use App\Domain\BookDomain;
use App\Exceptions\BookException;
use App\Http\Controllers\Controller;
use App\Builder\BookBuilder;

class BookController extends Controller
{
    public function __construct(){
        header('Access-Control-Allow-Origin: *');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       
        $objBuilder         = new BookBuilder();
        $dataToReturn       = $objBuilder->index();

        $httpResposeCode = $objBuilder->getHttpResponseCode();
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
        
        $objBuilder         = new BookBuilder();
        $dataToReturn       = $objBuilder->store($request);

        $httpResposeCode = $objBuilder->getHttpResponseCode();
        if(!$httpResposeCode){
            $httpResposeCode = 200;
        }

        return response()->json($dataToReturn, $httpResposeCode);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function storeBookSimple(Request $request, string $store_id)
    {
        
        $objBuilder         = new BookBuilder();
        $dataToReturn       = $objBuilder->storeBookSimple($request, $store_id);

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
        
        $objBuilder         = new BookBuilder();
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
       
        $objBuilder         = new BookBuilder();
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
        
        $objBuilder         = new BookBuilder();
        $dataToReturn       = $objBuilder->destroy($id);

        $httpResposeCode = $objBuilder->getHttpResponseCode();
        if(!$httpResposeCode){
            $httpResposeCode = 200;
        }

        return response()->json($dataToReturn, $httpResposeCode);
    }
}
