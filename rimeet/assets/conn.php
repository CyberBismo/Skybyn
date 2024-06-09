<?php
ob_start();

$db_host = "localhost";
$db_user = "elitesys_rimeet_admin";
$db_pass = "r1m337@dm1n";
$db_name = "elitesys_rimeet";

$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>