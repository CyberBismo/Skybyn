<?php include_once("../db_conn.php");
$id = $_POST['userID'];

$old_pw = $_POST['old_pw'];
$new_pw = $_POST['new_pw'];
$cnew_pw = $_POST['cnew_pw'];

$checkpw = "SELECT *
    FROM `users`
    WHERE `id`='$id'";
$getData = mysqli_query($conn, $checkpw);
$userData = mysqli_fetch_assoc($getData);
$csalt = $userData['salt'];

$ohpw = hash("sha512", $old_pw);
$cpw = hash("sha512", "$csalt-$ohpw");

$checkOldPw =  "SELECT *
    FROM `users`
    WHERE `password`='$cpw'";
$udQuery = mysqli_query($conn, $checkOldPw);
$countPw = mysqli_num_rows($udQuery);

if ($countPw == 1) {
    if (strlen($new_pw) < 8) {
        $rc = "01";
        $pwu = "Password too short!";
    } else
    if (!preg_match("#[0-9]+#", $new_pw)) {
        $rc = "02";
        $pwu = "Password must include at least one number!";
    } else
    if (!preg_match("#[a-zA-Z]+#", $new_pw)) {
        $rc = "03";
        $pwu = "Password must include at least one letter!";
    } else
    if ($new_pw == $cnew_pw) {
        $hpw = hash("sha512", $new_pw);
        $salt = hash("sha512", rand());
        $pw = hash("sha512", "$salt-$hpw");

        $pwq = "UPDATE `users`
            SET `password`='$pw', 
                `salt`='$salt'
            WHERE `id`='$id'";
        mysqli_query($conn, $pwq);

        $rc = "1";
        $pwu = "Password updated!";
    } else {
        $rc = "04";
        $pwu = "New password dont match!";
    }
} else {
    $rc = "05";
    $pwu = "Old password is incorrect!";
}

$json = array(
    "responseCode"=>"$rc",
    "message"=>"$pwu"
);
echo json_encode($json);