<?php require_once "./functions.php";

$group = $_POST['group'];
$last = substr($_POST['last'],5);

$getMsg = $conn->query("SELECT * FROM `group_messages` WHERE `group`='$group' AND `id`>'$last' ORDER BY `date` ASC");
if ($getMsg->num_rows > 0) {
    $data = array(
        "responseCode" => "ok"
    );
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data);
}
?>