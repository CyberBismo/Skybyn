<?php include_once("./conn.php");

$to = $_POST['to'];
$from = $_POST['from'];
$message = $conn->real_escape_string($_POST['message']);
$now = time(); // Current timestamp in Unix format

$conn->query("INSERT INTO `messages` (`from`,`to`,`content`,`date`) VALUES ('$from','$to','$message','$now')");

echo $conn->insert_id;

?>