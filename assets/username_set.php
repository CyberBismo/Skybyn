<?php include "functions.php";
$username = $_POST['username'];

$uid = $_COOKIE['username'];
$conn->query("UPDATE `users` SET `username`='$username' WHERE `id`='$uid'");
$_SESSION['user'] = $uid;

createCookie("username",null,null,'7');
?>