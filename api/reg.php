<?php include_once("../db_conn.php");
$user = $_POST['username'];
$pass = $_POST['password'];
$email = $_POST['email'];
$error = false;
# Check username
if (!empty($user)) {
    $uq = "SELECT *
        FROM `users`
        WHERE `username`='$user'";
    $ucheck = mysqli_query($conn, $uq);
    $ucount = mysqli_num_rows($ucheck);
} else {
    $error = true;
}
# Check password
if (!empty($pass)) {
} else {
    $error = true;
}
if ($error == false) {
    if ($ucount == 0) {
        $hpw = hash("sha512", $pass);
        $salt = hash("sha512", rand());
        $pw = hash("sha512", "$salt-$hpw");
        $q = "INSERT INTO `users` (
                `username`,
                `password`,
                `salt`,
                `email`,
                `verified`
            ) 
            VALUES (
                '$user',
                '$pw',
                '$salt',
                '$email',
                '1'
            )";
        $reg = mysqli_query($conn, $q) or die(mysqli_error($conn));
        $uq = "SELECT *
            FROM `users`
            WHERE `email`='$email'";
        $udata = mysqli_query($conn, $uq);
        $urow = mysqli_fetch_assoc($udata);
        $id = $urow['id'];
        
        $json = array("responseCode"=>"1","message"=>"Registration successful!");
        echo json_encode($json);
    } else {
        $json = array("responseCode"=>"0","message"=>"Username already exists.");
        echo json_encode($json);
    }
} else {
    $json = array("responseCode"=>"0","message"=>"Empty details!");
    echo json_encode($json);
}
?>