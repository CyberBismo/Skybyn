<?php include "../functions.php";
$token = $_POST['code'];

$checkCode = $conn->query("SELECT * FROM `users` WHERE `token`='$token'");
if ($checkCode->num_rows == 1) {
    $userData = $checkCode->fetch_assoc();
    $uid = $userData['id'];
    $conn->query("UPDATE `users` SET `verified`='1', `visible`='1', `token`='' WHERE `id`='$uid'");
    createCookie('verify',null,'','7');
    createCookie('username',$uid,'10','2');
    echo "verified";
}
?>