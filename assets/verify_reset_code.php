<?php include "./functions.php";
$reset = $_POST['code'];

$checkCode = mysqli_query($conn, "SELECT * FROM `reset_codes` WHERE `code`='$reset'");
$count = mysqli_num_rows($checkCode);
if ($count == 1) {
    echo "ok";
}
?>