<?php include_once("../assets/conn.php");
$code = $_POST['code'];
$username = $_POST['user'];

$checkUser = $conn->query("SELECT * FROM `users` WHERE `username`='$username'");
if ($checkUser->num_rows == 0) {
    $json = array("responseCode"=>0,"message"=>"User not found");
    echo json_encode($json);
    exit();
} else {
    $row = $checkUser->fetch_assoc();
    $user = $row['id'];
}

$check = $conn->query("SELECT * FROM `qr_sessions` WHERE `code`='$code'");
if ($check->num_rows == 1) {
    $row = $check->fetch_assoc();
    $conn->query("UPDATE `qr_sessions` SET `user`='$user' WHERE `code`='$code'");
    if (file_exists("https://skybyn.com/qr/temp/".$code.".png")) {
        unlink("https://skybyn.com/qr/temp/".$code.".png");
    }
    $json = array("responseCode"=>1,"message"=>"You logged in successfully");
    echo json_encode($json);
    exit();
} else {
    $json = array("responseCode"=>0,"message"=>"Invalid QR code");
    echo json_encode($json);
    exit();
}
?>