<?php include "./functions.php";
$username = $_POST['username'];

// Validate username
if (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
    echo "Invalid username";
    exit;
}

$checkUsername = mysqli_query($conn, "SELECT * FROM `users` WHERE `username`='$username'");
$count = mysqli_num_rows($checkUsername);
if ($count == 0) {
    echo "available";
}
?>