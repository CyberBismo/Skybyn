<?php 
include_once("../db_conn.php");
$user = mysqli_real_escape_string($conn, $_POST['username']);
$pass = mysqli_real_escape_string($conn, $_POST['password']);
$email = mysqli_real_escape_string($conn, $_POST['email']);
$error = false;

// Check username
if (!empty($user)) {
    $uq = $conn->prepare("SELECT * FROM `users` WHERE `username`=?");
    $uq->bind_param("s", $user);
    $uq->execute();
    $ucheck = $uq->get_result();
    $ucount = $ucheck->num_rows;
} else {
    $error = true;
}

// Check password
if (empty($pass)) {
    $error = true;
}

if (!$error) {
    if ($ucount == 0) {
        $hpw = hash("sha512", $pass);
        $salt = hash("sha512", rand());
        $pw = hash("sha512", "$salt-$hpw");
        $q = $conn->prepare("INSERT INTO `users` (`username`, `password`, `salt`, `email`, `verified`) VALUES (?, ?, ?, ?, '1')");
        $q->bind_param("ssss", $user, $pw, $salt, $email);
        $q->execute();

        $json = array("responseCode"=>"1","message"=>"Registration successful!");
        echo json_encode($json);
    } else {
        $json = array("responseCode"=>"0","message"=>"Username already exists.");
        echo json_encode($json);
    }
} else {
    $json = array("responseCode"=>"0","message"=>"Empty details!");
    echo json_encode($json);
}
?>
