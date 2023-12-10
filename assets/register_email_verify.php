<?php include "functions.php";
$code = $_POST['code'];

$checkCode = $conn->query("SELECT * FROM `email_check` WHERE `code`='$code'");
if ($checkCode->num_rows == 1) {
    $checkCode->query("UPDATE `email_check` SET `verified`='1' WHERE `code`='$code'");
    echo "ok";
}
?>