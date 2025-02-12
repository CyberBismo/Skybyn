<?php include_once("../assets/conn.php");

header('Content-Type: application/json');

if (isset($_POST['user']) && isset($_POST['password'])) {
    $user = mysqli_real_escape_string($conn, $_POST['user']);
    $pass = mysqli_real_escape_string($conn, $_POST['password']);

    $check = $conn->query("SELECT * FROM `users` WHERE `username`='$user'");
    if ($check->num_rows == 1) {
        $row = $check->fetch_assoc();
        $id = $row['id'];
        $hpw = hash("sha512", $pass);
        $salt = $row['salt'];
        $pw = hash("sha512", $salt."_".$hpw);
        $vcheck = $conn->prepare("SELECT * FROM `users` WHERE `id`=? AND `password`=?");
        $vcheck->bind_param("is", $id, $pw);
        $vcheck->execute();
        $vresult = $vcheck->get_result();
        
        if ($vresult->num_rows == 1) {
            $json = array("responseCode"=>"1","message"=>"Welcome!","userID"=>"$id");
            echo json_encode($json);
        } else {
            $json = array("responseCode"=>"0","message"=>"Wrong password");
            echo json_encode($json);
        }
    } else {
        $json = array("responseCode"=>"0","message"=>"Unknown email");
        echo json_encode($json);
    }
} else {
    $json = array("responseCode"=>"0","message"=>"No details provided");
    echo json_encode($json);
}
?>