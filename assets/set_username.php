<?php include "functions.php";
$username = $_POST['username'];
unset($_SESSION['username']);

$uid = $username;
$conn->query("UPDATE `users` SET `username`='$username' WHERE `id`='$uid'");
$_SESSION['user'] = $uid;
?>