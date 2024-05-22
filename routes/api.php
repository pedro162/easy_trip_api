<?php

use App\Http\Controllers\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->get('/user', function (Request $request) {
	$user = $request->user();
	if($user){
		$user->pessoa;
	}
	return $user;
});





Route::post('/user/store', 'App\Http\Controllers\UserController@store')->withoutMiddleware(['check_auth']);
Route::post('/user/driver/store', 'App\Http\Controllers\UserController@storeDriver')->withoutMiddleware(['check_auth']);
Route::post('/login', 'App\Http\Controllers\AuthController@login')->withoutMiddleware(['check_auth']);
//Route::post('/logout', 'App\Http\Controllers\AuthController@logout')->middleware('auth:api');


Route::group(['middleware' => ['check_auth','auth:api']], function () {

	//----Trip routes------------------------------------------------------------------------------------------------------------------------
	Route::get('/trip/index', ['as' => 'trip.index', 'uses' => 'App\Http\Controllers\TripController@index']);
	Route::post('/trip/store', ['as' => 'trip.store', 'uses' => 'App\Http\Controllers\TripController@store']);
	Route::put('/trip/update/{id}', ['as' => 'trip.update', 'uses' => 'App\Http\Controllers\TripController@update']);
	Route::get('/trip/show/{id}', ['as' => 'trip.show', 'uses' => 'App\Http\Controllers\TripController@show']);
	Route::delete('/trip/destroy/{id}', ['as' => 'trip.destroy', 'uses' => 'App\Http\Controllers\TripController@destroy']);
	Route::put('/trip/start/{id}', ['as' => 'trip.start', 'uses' => 'App\Http\Controllers\TripController@startTrip']);
	Route::put('/trip/complite/{id}', ['as' => 'trip.complite', 'uses' => 'App\Http\Controllers\TripController@compliteTrip']);
	Route::put('/trip/cancel/{id}', ['as' => 'trip.cancel', 'uses' => 'App\Http\Controllers\TripController@cancelTrip']);

	//----Trip payment request routes------------------------------------------------------------------------------------------------------------------------
	Route::get('/trip/payment/request/index', ['as' => 'trip.payment.request.index', 'uses' => 'App\Http\Controllers\TripPaymentRequestController@index']);
	Route::post('/trip/payment/request/store/{trip_id}', ['as' => 'trip.payment.request.store', 'uses' => 'App\Http\Controllers\TripPaymentRequestController@storePaymentRequestByTrip']);
	Route::put('/trip/payment/request/update/{id}', ['as' => 'trip.payment.request.update', 'uses' => 'App\Http\Controllers\TripPaymentRequestController@update']);
	Route::get('/trip/payment/request/show/{id}', ['as' => 'trip.payment.request.show', 'uses' => 'App\Http\Controllers\TripPaymentRequestController@show']);
	Route::delete('/trip/payment/request/destroy/{id}', ['as' => 'trip.payment.request.destroy', 'uses' => 'App\Http\Controllers\TripPaymentRequestController@destroy']);

	//----Bank Account routes------------------------------------------------------------------------------------------------------------------------
	Route::get('/bank/account/index', ['as' => 'bank.account.index', 'uses' => 'App\Http\Controllers\BankAccountController@index']);
	Route::post('/bank/account/store/{owner_id}', ['as' => 'bank.account.store', 'uses' => 'App\Http\Controllers\BankAccountController@storeOwnerAccount']);
	Route::put('/bank/account/update/{id}', ['as' => 'bank.account.update', 'uses' => 'App\Http\Controllers\BankAccountController@update']);
	Route::get('/bank/account/show/{id}', ['as' => 'bank.account.show', 'uses' => 'App\Http\Controllers\BankAccountController@show']);
	Route::delete('/bank/account/destroy/{id}', ['as' => 'bank.account.destroy', 'uses' => 'App\Http\Controllers\BankAccountController@destroy']);

	//----Bank Transaction routes------------------------------------------------------------------------------------------------------------------------
	Route::get('/bank/transaction/index', ['as' => 'bank.transaction.index', 'uses' => 'App\Http\Controllers\BankTransactionController@index']);
	Route::post('/bank/transaction/store', ['as' => 'bank.transaction.store', 'uses' => 'App\Http\Controllers\BankTransactionController@store']);
	Route::put('/bank/transaction/update/{id}', ['as' => 'bank.transaction.update', 'uses' => 'App\Http\Controllers\BankTransactionController@update']);
	Route::get('/bank/transaction/show/{id}', ['as' => 'bank.transaction.show', 'uses' => 'App\Http\Controllers\BankTransactionController@show']);
	Route::delete('/bank/transaction/destroy/{id}', ['as' => 'bank.transaction.destroy', 'uses' => 'App\Http\Controllers\BankTransactionController@destroy']);
	
	//----User routes------------------------------------------------------------------------------------------------------------------------
	Route::get('/user/index', ['as' => 'user.index', 'uses' => 'App\Http\Controllers\UserController@index']);
	Route::put('/user/update/{id}', ['as' => 'user.update', 'uses' => 'App\Http\Controllers\UserController@update']);
	Route::get('/user/show/{id}', ['as' => 'user.show', 'uses' => 'App\Http\Controllers\UserController@show']);
	Route::delete('/user/destroy/{id}', ['as' => 'user.destroy', 'uses' => 'App\Http\Controllers\UserController@destroy']);

	//---Extra routes------------------------------------------------------------------------------------------------------------------------
	Route::post('/logout', ['as' => 'logout', 'uses' => 'App\Http\Controllers\AuthController@logout']);

});