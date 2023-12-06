<?php require_once "./functions.php";

$group = $_POST['group'];
$msg = $_POST['text'];

$conn->query("INSERT INTO `group_messages` (`group`,`user`,`content`,`date`) VALUES ('$group','$uid','$msg','$now')");
?>