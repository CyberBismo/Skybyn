<?php require_once "./functions.php";

$group = $_POST['group'];
$msg = $_POST['text'];

$send = $conn->query("INSERT INTO `group_messages` (`group`,`user`,`content`,`date`) VALUES ('$group','$uid','$msg','$now')");

if ($send) {
    $msgId = $conn->insert_id;
    $data = array(
        "responseCode" => "ok",
        "messageId" => "$msgId"
    );
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data);
}
?>