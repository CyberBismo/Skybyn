<?php include_once("../db_conn.php");
$id = $_POST['userID'];

$fname = $_POST['first_name'];
$mname = $_POST['middle_name'];
$lname = $_POST['last_name'];
$title = $_POST['title'];
$nickname = $_POST['nickname'];
$color = $_POST['color'];
$bio = $_POST['bio'];

$updateq = "UPDATE `users`
    SET `first_name`='$fname', 
        `middle_name`='$mname', 
        `last_name`='$lname', 
        `title`='$title', 
        `nickname`='$nickname', 
        `color`='$color', 
        `bio`='$bio'
    WHERE `id`='$id'";
mysqli_query($conn, $updateq);

$json = array(
    "responseCode"=>"1",
    "message"=>"Profile updated!"
);
echo json_encode($json);