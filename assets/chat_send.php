<?php include_once "functions.php";

$to = $_POST['to_id'];
$text = $_POST['text'];

$conn->query("INSERT INTO `messages` (`user`,`friend`,`content`,`date`) VALUES ('$uid','$to','$text','$now')");

?>