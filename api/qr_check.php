<?php include_once("../assets/config.php");
$code = $_POST['code'];
$user = $_POST['user'];

$check = $conn->query("SELECT * FROM `qr_codes` WHERE `code`='$code' AND `used`='0'");
if ($check->num_rows == 1) {
    $row = $check->fetch_assoc();
    $id = $row['id'];
    $conn->query("UPDATE `qr_codes` SET `used`='1', `user`='$user' WHERE `id`='$id'");
    $json = array("responseCode"=>"1","message"=>"You logged in");
    echo json_encode($json);
} else {
    $json = array("responseCode"=>"0","message"=>"Invalid QR code");
    echo json_encode($json);
}
?>