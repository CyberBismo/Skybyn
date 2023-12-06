<?php
$url = "https://fcm.googleapis.com/fcm/send";
$api_key = "AAAA9EXEwaw:APA91bE6Si-vEULq9GNuGFgZ4MHnMHpSV3r3wnaNT9U_in8lvGCBHOFBFL0sfklj4DMcEdY3Fj4oaQ5XTjntpqRHLmDZzYrgqvLOf8sQCImD58RrOPTcxdJ4Gbi1Z9iKebB7epjceqvr"; //FIREBASE KEY
$token = $_POST['token'];
$title = $_POST['title'];
$body = $_POST['body'];
$type = $_POST['type'];
$from = $_POST['from'];


#$headers = array (
#	'Authorization: key='.$api_key,
#	'Content-Type: application/json;charset=UTF-8'
#);
#
#$message = array(
#    'data' => 
#    array (
#    	'title' => $title,
#    	'body' => $body,
#    	'type' => $type,
#		'from' => $from,
#    ),
#    'to' => '/topics/'.$receiver,
#    'priority' => 'high',
#    //'restricted_package_name' => 'com.onlyoneapp.test', //IF YOU WANT SEND TO ONLY ONE APP
#); 
#
#$content = json_encode($data);
#$curl = curl_init($url);
#curl_setopt($curl, CURLOPT_HEADER, false);
#curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
#curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
#curl_setopt($curl, CURLOPT_POST, true);
#curl_setopt($curl, CURLOPT_POSTFIELDS, $content);
#curl_setopt($curl,CURLOPT_SSL_VERIFYPEER, false );
#$result = curl_exec($curl);
#curl_close($curl);
#$arr = array();
#$arr = json_decode($result,true);
#
#if ($arr === FALSE) {
#	$push = "Json invalid!"."<br>";
#} else
#if (empty($arr)) {
#	$push = "Json invalid!"."<br>";
#} else {
#	if (array_key_exists ('message_id', $arr)){
#		$push = "ok";
#	} else {
#		$push = "An error ocurred while sending the notification";
#	}
#}



define('API_ACCESS_KEY',$api_key);
$fcmUrl = $url;

$notification = [
    'title' => $title,
    'body' => $body,
    'icon' => $icon, 
    'sound' => $sound
];
$extraNotificationData = [
	"message" => $notification,
	"moredata" =>'dd'
];
$fcmNotification = [
    //'registration_ids' => $tokenList, //multple token array
    'to'        => $token, //single token
    'notification' => $notification,
    'data' => $extraNotificationData
];
$headers = [
    'Authorization: key=' . API_ACCESS_KEY,
    'Content-Type: application/json'
];
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,$fcmUrl);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmNotification));
$result = curl_exec($ch);
curl_close($ch);
?>