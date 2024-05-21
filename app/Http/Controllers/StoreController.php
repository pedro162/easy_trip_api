<?php

namespace App\Http\Controllers;

//use Illuminate\Http\Request;
//use App\Http\Controllers\Request;
use Illuminate\Http\Request;
use App\Domain\StoreDomain;
use App\Exceptions\StoreException;
use App\Builder\StoreBuilder;

class StoreController extends Controller
{
    public function __construct(){
        header('Access-Control-Allow-Origin: *');
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
        $objBuilder         = new StoreBuilder();
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
        
        $objBuilder         = new StoreBuilder();
        $dataToReturn       = $objBuilder->store($request);

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
        $objBuilder         = new StoreBuilder();
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
       
        $objBuilder         = new StoreBuilder();
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
        
        $objBuilder         = new StoreBuilder();
        $dataToReturn       = $objBuilder->destroy($id);

        $httpResposeCode = $objBuilder->getHttpResponseCode();
        if(!$httpResposeCode){
            $httpResposeCode = 200;
        }

        return response()->json($dataToReturn, $httpResposeCode);
    }

    public function add_boock(Request $request, string $store_id, string $book_id)
    {
        
        $objBuilder         = new StoreBuilder();
        $dataToReturn       = $objBuilder->add_boock($request, $store_id, $book_id);

        $httpResposeCode = $objBuilder->getHttpResponseCode();
        if(!$httpResposeCode){
            $httpResposeCode = 200;
        }

        return response()->json($dataToReturn, $httpResposeCode);
    }
}
