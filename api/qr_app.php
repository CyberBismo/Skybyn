<?php include_once("../db_conn.php");

$code = $_POST['code'];
$user = $_POST['login'];
$device = $_POST['device'];

$codepart = explode("_", $code);
$source = $codepart[0];

$checkCode = "SELECT *
    FROM `qr_codes`
    WHERE `code`='$code'";
$ccres = mysqli_query($conn, $checkCode);
$ccc = mysqli_num_rows($ccres);
if ($ccc > 0) {
    if ($source == "web") {
        $updatecodedata = "UPDATE `qr_codes`
            SET `login`='$user'
            WHERE `code`='$code'
        ";
        mysqli_query($conn, $updatecodedata);
        
        $json = array(
            "responseCode"=>"1",
            "message"=>"Signing you in!"
        );
        echo json_encode($json);
    } else
    if ($source == "app") {
        $ccdata = mysqli_fetch_assoc($ccres);
        $user = $ccdata['login'];

        $updatecodedata = "UPDATE `qr_codes`
            SET `used`='1'
            WHERE `code`='$code'
        ";
        mysqli_query($conn, $updatecodedata);
        
        $json = array(
            "responseCode"=>"1",
            "userID"=>"$user",
            "message"=>"QR confirmed, signing you in!"
        );
        echo json_encode($json);
    }
} else {
    $json = array(
        "responseCode"=>"0",
        "message"=>"Invalid or expired QR code!"
    );
    echo json_encode($json);
}
?>