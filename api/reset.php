<?php include_once('../db_conn.php');

$reset = $_POST['reset'];

$tq = "SELECT *
    FROM `reset_codes`
    WHERE `code`='$reset'";
mysqli_query($conn, $tq);

$json = array(
    "responseCode"=>"1",
    "message"=>"Token stored"
);
echo json_encode($json);
?>