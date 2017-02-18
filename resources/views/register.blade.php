<?php

use Illuminate\Support\Facades\Input;
use App\User;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuthExceptions\JWTException;

$credentials = Input::only('email', 'password', 'name');
$credentialsForToken = Input::only('email', 'password');
$credentials['password'] = Hash::make($credentials['password']);
try {
	$user = User::create($credentials);
} catch (\Exception $e) {
	$error['error'] = 'User already exists.'; $error = json_encode($error);
	echo $error; return;
}
//Get a token for the newly registered user
try {
	// attempt to verify the credentials and create a token for the user
	if (! $token = JWTAuth::attempt($credentialsForToken)) {
		return response()->json(['error' => 'invalid_credentials'], 401);
	}
} catch (JWTException $e) {
// something went wrong whilst attempting to encode the token
	return response()->json(['error' => 'could_not_create_token'], 500);
}
// all good so return the token
$token = JWTAuth::attempt($credentialsForToken);
//$user = \Auth::user();
$userUNJSON = json_decode($user, true);
$userUNJSON = array_flip($userUNJSON); $userUNJSON = array_diff($userUNJSON, ["created_at", "updated_at"]);	 $userUNJSON = array_flip($userUNJSON);
$userFinal = array("data" => $userUNJSON, "token" => $token); $userFinal = json_encode($userFinal);
//return (response($content) -> header('Authorization', 'Bearer '.$token));
//return Response::json(compact('token'));
echo $userFinal;
return;