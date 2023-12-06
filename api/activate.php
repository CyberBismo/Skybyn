<?php include_once('../db_conn.php');
$code = $_POST['code'];

$q = "SELECT *
    FROM `users`
    WHERE `token`='$code'";
$check = mysqli_query($conn, $q);
$count = mysqli_num_rows($check);
if ($count == 1) {
    $qt = "UPDATE `users`
        SET `token`=''
        AND 'verified'='1'
        WHERE `token`='$code'";
    $checkt = mysqli_query($conn, $qt);

    if (isset($checkt)) {
        $result = "true";
        echo $result;
    } else {
        $result = "false";
        echo $result;
    }
}
?>