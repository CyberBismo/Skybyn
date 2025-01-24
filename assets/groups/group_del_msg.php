<?php require_once "../functions.php";

$id = $_POST['message'];

if ($conn->query("DELETE FROM `group_messages` WHERE `id`='$id'")) {
    $data = array(
        "responseCode" => "ok",
        "message" => "Message deleted"
    );
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data);
} else {
    $data = array(
        "responseCode" => "error",
        "message" => "Unable to delete message"
    );
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data);
}
?>