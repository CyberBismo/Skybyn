<?php 
include_once('../db_conn.php');
$reset = mysqli_real_escape_string($conn, $_POST['reset']);

$tq = $conn->prepare("SELECT * FROM `reset_codes` WHERE `code`=?");
$tq->bind_param("s", $reset);
$tq->execute();

$json = array("responseCode"=>"1","message"=>"Token stored");
echo json_encode($json);
?>
