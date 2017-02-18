<?php

use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuthExceptions\JWTException;
use App\User;

$user = JWTAuth::parseToken()->authenticate();
$token = JWTAuth::getToken();
$userUNJSON = json_decode($user, true);
$userUNJSON = array_flip($userUNJSON); $userUNJSON = array_diff($userUNJSON, ["created_at", "updated_at"]);	 $userUNJSON = array_flip($userUNJSON);
$userFinal = array("data" => $userUNJSON); $userFinal = json_encode($userFinal);
echo $userFinal;
return;