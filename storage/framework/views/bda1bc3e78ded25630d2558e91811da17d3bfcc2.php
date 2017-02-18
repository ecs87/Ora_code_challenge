<?php
use Illuminate\Support\Facades\Input;
use App\User;
use Illuminate\Http\Response as HttpResponse;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuthExceptions\JWTException;

JWTAuth::invalidate(JWTAuth::getToken());