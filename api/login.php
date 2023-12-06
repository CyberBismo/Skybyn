<?php include_once("../assets/db.php");
$email = $_POST['email'];
$pass = $_POST['password'];

$check = $conn->query("SELECT * FROM `users` WHERE `email`='$email'");
if ($check->num_rows == 1) {
    $row = $check->fetch_assoc();
    $id = $row['id'];
    $hpw = hash("sha512", $pass);
    $salt = $row['salt'];
    $pw = hash("sha512", $salt."_".$hpw);
    $vcheck = $conn->query("SELECT * FROM `users` WHERE `id`='$id' AND `password`='$pw'");
    
    if ($vcheck->num_rows == 1) {
        $json = array("responseCode"=>"1","userID"=>"$id");
        echo json_encode($json);
    } else {
        $json = array("responseCode"=>"0","message"=>"Feil passord.");
        echo json_encode($json);
    }
} else {
    $json = array("responseCode"=>"0","message"=>"Ukjent epost.");
    echo json_encode($json);
}
?>