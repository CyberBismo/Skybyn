<?php include "./functions.php";
$reset = $_POST['code'];

$checkCode = $conn->query("SELECT * FROM `reset_codes` WHERE `code`='$reset'");
if ($checkCode->num_rows == 1) {
    $code = $checkCode->fetch_assoc();
    $five_min = time() - 300;
    if ($code['expiration_date'] > $five_min) {
        echo "ok";
    }
}
?>