<?php
//this file creates entries in the DB (db settings are in /.env

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuthExceptions\JWTException;
use Illuminate\Support\Facades\Input;
use App\User;

$user = JWTAuth::parseToken()->authenticate();
$chatItems = Input::only('name', 'message');
$chatItems['user_id'] = $user->id;
$chatRoom = array('user_id' => $chatItems['user_id'], 'name' => $chatItems['name']);
$id = DB::table('chats')->insertGetId($chatRoom);
$chatItems['chat_id'] = $id;
$mytime = Carbon\Carbon::now();
$mytime = $mytime->toRfc3339String();
$chatItems['created_at'] = $mytime;

$chatRoom2 = array('created_at' => $chatItems['created_at'], 'user_id' => $chatItems['user_id'], "message" => $chatItems['message'], 'chat_id' => $chatItems['chat_id']);
$idmessages = DB::table('chat_messages')->insertGetId($chatRoom2);
$chat_id = DB::table('chats')->where('id',$id)->first();

$last = DB::table('chat_messages')->where('chat_id', ($chat_id->id))->orderBy('id', 'desc')->value("user_id");
//$last = $last->user_id;
$last = DB::table('users')->where('id', $last)->first();

$last_chatmsg_user = array("id" => $last->id, "name" => $last->name, "email" => $last->email);
$last_chat_message = array("id" => $idmessages, "chat_id" => $chatItems['chat_id'], "user_id" => $chatItems['user_id'], "message" => $chatItems['message'], "created_at" => $mytime, "user" => $last_chatmsg_user);
$users = DB::table('chat_messages')->where('chat_id', $chatItems['chat_id'])->get();
foreach ($users as $user_single) {
	$user_final = DB::table('users')->where('id', $user_single->user_id)->first();
	unset($user_final->password); unset($user_final->created_at); unset($user_final->updated_at);
	$users_final[] = $user_final;
}
$data = array("data" => array("id" => $chat_id->id, "name" => $chat_id->name, "users" => $users_final, "last_chat_message" => $last_chat_message));
$data = json_encode($data) ;
echo $data;
return;