<?php include "functions.php";
$token = $_POST['code'];

$checkCode = mysqli_query($conn, "SELECT * FROM `users` WHERE `token`='$token'");
$count = mysqli_num_rows($checkCode);
if ($count == 1) {
    $userData = mysqli_fetch_assoc($checkCode);
    $uid = $userData['id'];
    mysqli_query($conn, "UPDATE `users` SET `verified`='1', `visible`='1', `token`='' WHERE `id`='$uid'");
    createCookie('verify',null,'','7');
    createCookie('username',$uid,'10','2');
    echo "verified";
}
?>