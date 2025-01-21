<?php include "./functions.php";
$username = $_POST['username'];

$checkUsername = $conn->query("SELECT * FROM `users` WHERE `username`='$username'");
if ($checkUsername->num_rows == 0) {
    echo "available";
}
?>