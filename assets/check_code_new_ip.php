<?php include_once "./functions.php";

if (isset($_POST['code'])) {
    $code = $_POST['code'];
    $newIP = $_POST['ip'];
    $checkIP = $conn->query("SELECT * FROM `ip_history` WHERE `ip`='$newIP' AND `code`='$code'");
    if ($checkIP->num_rows == 1) {
        $ipData = $checkIP->fetch_assoc();
        $user = $ipData['user_id'];
        $conn->query("UPDATE `ip_history` SET `trusted`='1', `code`='' WHERE `user_id`='$user' AND `ip`='$newIP'");
        $_SESSION['user'] = $user;
    }
}
?>