<?php include_once("../db_conn.php");

$qr = rand();

$type = $_POST['type'];

if (isset($_POST['login'])) {
    $user = $_POST['login'];
} else {
    $user = "";
}
if (isset($_POST['other'])) {
    $other = $_POST['other'];
} else {
    $other = "";
}

$code = "$type$qr";

$loginCode = "INSERT INTO `qr_codes` (`code`,`login`,`other`) VALUES ('$code','$user','$other')";
mysqli_query($conn, $loginCode);

echo $code;
?>