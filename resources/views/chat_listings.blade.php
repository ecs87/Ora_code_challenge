<?php
//this file creates entries in the DB (db settings are in /.env

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuthExceptions\JWTException;
use Illuminate\Support\Facades\Input;
use App\User;

$user = JWTAuth::parseToken()->authenticate();
$chatItems = Input::only('page', 'limit');
$allChats = DB::table('chats')->paginate($chatItems['limit']);
$allChat_finalArray = array();
$Finalarray = array();
$Finalarray2 = array();
$i=0; $y=0;
foreach ($allChats as $allChat) {
	$Finalarray = array('id' => $allChat->id, 'name' => $allChat->name);
	$chatToUsers = DB::table('chat_messages')->where('chat_id', $allChat->id)->get();
	foreach ($chatToUsers as $chatToUser) {
		$chatToUsersFinal[] = $chatToUser->user_id;
	}
	$z = 0;
	foreach ($chatToUsersFinal as $chatToUserFinal) {
		$allChats_final = DB::table('users')->where('id', $chatToUserFinal)->get();
		$allChat_pre[$z]['id'] = $allChats_final[0]->id; $allChat_pre[$z]['name'] = $allChats_final[0]->name; $allChat_pre[$z]['email'] = $allChats_final[0]->email;
		$z++;
	}
	foreach ($allChat_pre as $allChat_final) {
		$Finalarray['users'] = $allChat_pre;
		$y++;
	}
	$last_chatmsg = DB::table('chat_messages')->where('chat_id', ($allChat->id))->orderBy('id', 'desc')->value("user_id");
	$last_chatmsg_id = DB::table('chat_messages')->where('chat_id', ($allChat->id))->orderBy('id', 'desc')->value("id");
	$last_chatmsg_id_final = DB::table('chat_messages')->where('id', $last_chatmsg_id)->first();
	$last = DB::table('users')->where('id', $last_chatmsg)->first();
	$last_chatmsg_user = array("id" => $last->id, "name" => $last->name, "email" => $last->email);
	$last_chat_message = array("id" => $last_chatmsg_id_final->id, "chat_id" => $last_chatmsg_id_final->chat_id, "user_id" => $last_chatmsg_id_final->user_id, "message" => $last_chatmsg_id_final->message, "created_at" => $last_chatmsg_id_final->created_at, "user" => $last_chatmsg_user);
	$Finalarray['last_chat_message'] = $last_chat_message;
	$Finalarray2[$i] = $Finalarray;
	$i++;
	unset($allChat_pre);
	unset($chatToUsersFinal);
}
$page_count = $allChats->lastpage();
$total_count = $allChats->total();
$current_page = $chatItems['page'];
$per_page = $chatItems['limit'];
$allChatsFinal = array("data" => ($Finalarray2),"meta" => array("pagination" => array("current_page" => $current_page, "per_page" => $per_page, "page_count" => $page_count, "total_count" => $total_count)));
$allChatsFinal = json_encode($allChatsFinal);
print_r($allChatsFinal); 
return;