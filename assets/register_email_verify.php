<?php include "functions.php";
$code = $_POST['code'];

$checkCode = mysqli_query($conn, "SELECT * FROM `email_check` WHERE `code`='$code'");
$count = mysqli_num_rows($checkCode);
if ($count == 1) {
    echo "ok";
}
?>