<?php include_once('../db_conn.php');

$id = $_POST['userID'];
$token = $_POST['token'];

$tq = "UPDATE `users`
    SET `token`='$token'
    WHERE `id`='$id'";
mysqli_query($conn, $tq);

$json = array(
    "responseCode"=>"1",
    "message"=>"Token stored"
);
echo json_encode($json);
?>