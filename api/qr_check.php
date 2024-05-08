<?php include_once("../assets/db.php");
$code = $_POST['code'];
$user = $_POST['user'];

$check = $conn->query("SELECT * FROM `qr_sessions` WHERE `code`='$code'");
if ($check->num_rows == 1) {
    $row = $check->fetch_assoc();
    $conn->query("UPDATE `qr_sessions` SET `user`='$user' WHERE `code`='$code'");
    unlink("../qr/temp/".$code.".png");
    $json = array("responseCode"=>"1","message"=>"You logged in");
    echo json_encode($json);
} else {
    $json = array("responseCode"=>"0","message"=>"Invalid QR code");
    echo json_encode($json);
}
?>