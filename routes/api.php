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
Route::post('/login', 'App\Http\Controllers\AuthController@login')->withoutMiddleware(['check_auth']);
//Route::post('/logout', 'App\Http\Controllers\AuthController@logout')->middleware('auth:api');


Route::group(['middleware' => ['check_auth','auth:api']], function () {

	//----Store routes------------------------------------------------------------------------------------------------------------------------
	/*Route::get('/store/index', ['as' => 'store.index', 'uses' => 'App\Http\Controllers\StoreController@index']);
	Route::post('/store/store', ['as' => 'store.store', 'uses' => 'App\Http\Controllers\StoreController@store']);
	Route::put('/store/update/{id}', ['as' => 'store.update', 'uses' => 'App\Http\Controllers\StoreController@update']);
	Route::get('/store/show/{id}', ['as' => 'store.show', 'uses' => 'App\Http\Controllers\StoreController@show']);
	Route::delete('/store/destroy/{id}', ['as' => 'store.destroy', 'uses' => 'App\Http\Controllers\StoreController@destroy']);
	Route::post('/store/{store_id}/book/{book_id}', ['as' => 'store.add_boock', 'uses' => 'App\Http\Controllers\StoreController@add_boock']);
	*/
	Route::get('/user/index', ['as' => 'user.index', 'uses' => 'App\Http\Controllers\UserController@index']);
	Route::put('/user/update/{id}', ['as' => 'user.update', 'uses' => 'App\Http\Controllers\UserController@update']);
	Route::get('/user/show/{id}', ['as' => 'user.show', 'uses' => 'App\Http\Controllers\UserController@show']);
	Route::delete('/user/destroy/{id}', ['as' => 'user.destroy', 'uses' => 'App\Http\Controllers\UserController@destroy']);

	//---Extra routes------------------------------------------------------------------------------------------------------------------------
	Route::post('/logout', ['as' => 'logout', 'uses' => 'App\Http\Controllers\AuthController@logout']);

});