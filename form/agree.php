<?php include_once "../assets/functions.php";

$name = $_POST['name'];
$email = $_POST['email'];

$conn->query("INSERT INTO `agreements` (`name`,`email`,`form`,`date`) VALUES ('$name','$email','agreement','$now')");

header("location: ./");
?>