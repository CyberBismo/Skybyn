<?php include_once("../assets/conn..php");

header('Content-Type: application/json');

if (isset($_POST['email']) && isset($_POST['reset'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $reset = mysqli_real_escape_string($conn, $_POST['reset']);

    $checkCode = $conn->query("SELECT * FROM `reset_codes` WHERE `code`='$reset'");
    if ($checkCode->num_rows == 1) {
        $checkUser = $conn->query("SELECT * FROM `users` WHERE `email`='$email'");
        if ($checkUser->num_rows == 1) {
            $resetData = $checkCode->fetch_assoc();
            $userid = $userData['userid'];
            $userData = $checkUser->fetch_assoc();
            $uid = $userData['id'];

            if ($userid == $uid) {
                $json = array(
                    "responseCode"=>"1",
                    "message"=>"Reset code verified"
                );
                echo json_encode($json);
            } else {
                $json = array(
                    "responseCode"=>"0",
                    "message"=>"Incorrect code"
                );
                echo json_encode($json);
            }
        } else {
            $json = array(
                "responseCode"=>"0",
                "message"=>"Unknown email"
            );
            echo json_encode($json);
        }
    } else {
        $json = array(
            "responseCode"=>"0",
            "message"=>"Invalid code"
        );
        echo json_encode($json);
    }
} else {
    $json = array(
        "responseCode"=>"0",
        "message"=>"Required information not provided"
    );
    echo json_encode($json);
}
?>
