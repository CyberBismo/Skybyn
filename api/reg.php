<?php include_once("../assets/conn..php");

header('Content-Type: application/json');

if (isset($_POST['email']) && isset($_POST['reset'])) {
    $dob = mysqli_real_escape_string($conn, $_POST['date_of_birth']);
    $fname = mysqli_real_escape_string($conn, $_POST['first_name']);
    $mname = mysqli_real_escape_string($conn, $_POST['middle_name']);
    $lname = mysqli_real_escape_string($conn, $_POST['last_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $email_c = mysqli_real_escape_string($conn, $_POST['email_c']);
    $user = mysqli_real_escape_string($conn, $_POST['username']);
    $pass = mysqli_real_escape_string($conn, $_POST['password']);
    $pass_C = mysqli_real_escape_string($conn, $_POST['password_c']);
    $refer = $_POST['refer'];
    $ip = getIP();
    $country = geoData("countryName");
    $lang = geoData("countryCode");
    $pack = $_POST['pack'];
    $error = false;

    // Check username
    if (!empty($user)) {
        $uq = $conn->prepare("SELECT * FROM `users` WHERE `username`=?");
        $uq->bind_param("s", $user);
        $uq->execute();
        $ucheck = $uq->get_result();
        if ($ucheck->num_rows == 0) {
            $error = false;
        } else {
            $error = "Username already exists.";
        }
    } else {
        $error = "Username not pwovided";
    }

    // Check password
    if (!empty($pass)) {
        if ($pass == $pass_c) {
            $error = false;
        } else {
            $error = "Paswords dont match";
        }
    } else {
        $error = "Password not provided";
    }

    if ($error == false) {
        $hpw = hash("sha512", $pass);
        $salt = hash("sha512", rand());
        $pw = hash("sha512", $salt."_".$hpw);
        $q = $conn->prepare("INSERT INTO `users` (`username`, `password`, `salt`, `email`) VALUES (?, ?, ?, ?)");
        $q->bind_param("ssss", $user, $pw, $salt, $email);
        $q->execute();

        $json = array("responseCode"=>"1","message"=>"Registration successful!");
        echo json_encode($json);
    } else {
        $json = array("responseCode"=>"0","message"=>"$error");
        echo json_encode($json);
    }
}
?>
