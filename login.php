<?php include_once "./functions.php";

session_destroy();

if (isset($_GET['t'])) {
    $token = $_GET['t'];
    $checkToken = $conn->query("SELECT * FROM `users` WHERE `token`='$token'");
    if ($checkToken->num_rows == 1) {
        $UserRow = $checkToken->fetch_assoc();
        $uid = $UserRow['id'];
        $now = time();
        $currentIP = getIP();
        $conn->query("UPDATE `users` SET `ip`='$currentIP', `last_login`='$now' WHERE `id`='$uid'");
        session_start();
        $_SESSION['user'] = $uid;
        header("Location: ./");
    } else {
        header("Location: ./");
    }
} else {
    header("Location: ./");
}