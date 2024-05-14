<?php include "./functions.php";
$username = $_POST['username'];

$checkUsername = mysqli_query($conn, "SELECT * FROM `users` WHERE `username`='$username'");
$count = mysqli_num_rows($checkUsername);
if ($count == 0) {
    echo "available";
}
?>