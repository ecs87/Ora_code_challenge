<?php
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuthExceptions\JWTException;

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

//this is for registration, reading profile, and updating profile
Route::group(['prefix' => '/users'], function() {
	//register for new account
	Route::post('/', function() {
		$data = View::make('register', ['name' => 'testview'])->render(); $dataArray = json_decode($data);
		if (array_key_exists('token', $dataArray)) {
			$data_response = '{"data":{"id": "'.$dataArray->data->id.'","name":"'.$dataArray->data->name.'","email":"'.$dataArray->data->email.'"}}';
			return response($data_response, 200) -> header('Authorization', 'Bearer '.$dataArray->token);
		}
		else { return $data; }
	});
	//read current profile info
	Route::get('/current', function() {
	  $data = view('readprofile');
		$token = JWTAuth::getToken();
	  return response($data, 200) -> header('Authorization', 'Bearer '.$token);
	});
	//update current profile info
	Route::patch('/current', function() {
	  $data = view('updateprofile');
		$token = JWTAuth::getToken();
	  return response($data, 200) -> header('Authorization', 'Bearer '.$token);
	});
});

//this is for chat listings
Route::get('/chats', function() {
	$data = view('chat_listings');
	$token = JWTAuth::getToken();
	return response($data, 200) -> header('Authorization', 'Bearer '.$token);
});

//this is for chat creation
Route::post('/chats', function() {
	$data = view('chat_create');
	$token = JWTAuth::getToken();
	return response($data, 200) -> header('Authorization', 'Bearer '.$token);
});

//this is for login
Route::group(['prefix' => '/auth'], function() {
  Route::resource('login', 'AuthenticateController', ['only' => ['index']]);
  Route::post('login', 'AuthenticateController@authenticate');
});

//this is for logout
Route::get('/auth/logout', function() {
	return view('logout');
});