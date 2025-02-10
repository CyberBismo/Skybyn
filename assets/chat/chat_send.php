<?php include_once("./conn.php");

$to = $_POST['to'];
$from = $_POST['from'];
$message = $conn->real_escape_string(htmlentities($_POST['message'], ENT_QUOTES | ENT_HTML5, 'UTF-8'));
$now = time(); // Current timestamp in Unix format

$conn->query("INSERT INTO `messages` (`from`,`to`,`content`,`date`) VALUES ('$from','$to','$message','$now')");

echo $conn->insert_id;

?>