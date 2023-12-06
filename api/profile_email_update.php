<?php include_once("../db_conn.php");
$id = $_POST['userID'];
$email = $_POST['new_email'];

$updateq = "UPDATE `users`
    SET `email`='$email'
    WHERE `id`='$id'";
mysqli_query($conn, $updateq);
$json = array(
    "responseCode"=>"1",
    "message"=>"Email updated!"
);
echo json_encode($json);