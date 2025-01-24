<?php require_once "../functions.php";

$group = $_POST['group'];
$last = $_POST['last'];
$length = strlen("chat_".$group."_");
$last = substr($last,$length);

$getMsg = $conn->query("SELECT * FROM `group_messages` WHERE `group`='$group' AND `id`>'$last'");
if ($getMsg->num_rows > 0) {
    $data = array(
        "responseCode" => "ok"
    );
} else {
    $data = array(
        "responseCode" => "error"
    );
}
header('Content-Type: application/json; charset=utf-8');
echo json_encode($data);
?>