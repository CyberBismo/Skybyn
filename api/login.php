<?php 
include_once("../assets/db.php");
$email = mysqli_real_escape_string($conn, $_POST['email']);
$pass = mysqli_real_escape_string($conn, $_POST['password']);

$check = $conn->prepare("SELECT * FROM `users` WHERE `email`=?");
$check->bind_param("s", $email);
$check->execute();
$result = $check->get_result();
if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
    $id = $row['id'];
    $hpw = hash("sha512", $pass);
    $salt = $row['salt'];
    $pw = hash("sha512", $salt."_".$hpw);
    $vcheck = $conn->prepare("SELECT * FROM `users` WHERE `id`=? AND `password`=?");
    $vcheck->bind_param("is", $id, $pw);
    $vcheck->execute();
    $vresult = $vcheck->get_result();
    
    if ($vresult->num_rows == 1) {
        $json = array("responseCode"=>"1","userID"=>"$id");
        echo json_encode($json);
    } else {
        $json = array("responseCode"=>"0","message"=>"Incorrect password.");
        echo json_encode($json);
    }
} else {
    $json = array("responseCode"=>"0","message"=>"Unknown email.");
    echo json_encode($json);
}
?>
