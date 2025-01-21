<?php include "functions.php";
$email = $_POST['email'];
$pin = hash("sha512", $_POST['pin']);

$checkCode = $conn->query("SELECT * FROM `users` WHERE `email`='$email'");
$userData = $checkCode->fetch_assoc();
$uid = $userData['id'];
if ($checkCode->num_rows == 1) {
    $verify = $conn->query("SELECT * FROM `users` WHERE `id`='$uid' AND `pin`='$pin'");
    if ($verify->num_rows == 1) {
        echo "ok";
    }
}
?>