<?php include_once "functions.php";

$refer = $_POST['code'];

if ($refer >= 6) {
    $checkCode = $conn->query("SELECT * FROM `referral_code` WHERE `referral_code`='$code'");
    if ($checkCode->num_rows > 0) {
        $codeData = $checkCode->fetch_assoc();
        $user = $codeData['id'];
        $users = $conn->query("SELECT * FROM `users` WHERE `id`= '$user'");
        $userData = $users->fetch_assoc();
        $username = $userData['username'];
        $username = $userData['username'];
        echo '';
    }
}
?>