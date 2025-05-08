<?php include_once "../assets/conn.php";

if (isset($_POST['code'])) {
    $code = $_POST['code'];
    $checkCode = $conn->prepare("SELECT * FROM `beta_access` WHERE `key` = ?");
    $checkCode->bind_param("s", $code);
    $checkCode->execute();
    $result = $checkCode->get_result();
    if ($result->num_rows == 1) {
        echo "ok";
    }
}