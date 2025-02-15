<?php  include_once "./functions.php";

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET");
header("Access-Control-Allow-Headers: Content-Type");

if (!isset($_POST['username']) || !isset($_POST['password'])) {
    $data = array(
        "responseCode" => "error",
        "message" => "Missing required fields"
    );
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data);
    exit;
}

$username = $conn->real_escape_string($_POST['username']);
$password = hash("sha512", $_POST['password']);
$remember = isset($_POST['remember']) ? $_POST['remember'] : "false";
$currentIP = getIP();
$new_token = rand(100000, 999999);

$stmt = $conn->prepare("SELECT * FROM `users` WHERE `username`=?");
$stmt->bind_param("s", $username);
$stmt->execute();
$checkUName = $stmt->get_result();
if ($checkUName->num_rows == 1) {
    $UserRow = $checkUName->fetch_assoc();
    $uid = $UserRow['id'];
    $salt = $UserRow['salt'];
    $pw = hash("sha512", $salt."_".$password);
    $qCheckPassword = $conn->prepare("SELECT * FROM `users` WHERE `id`=? AND `password`=?");
    $qCheckPassword->bind_param("is", $uid, $pw);
    $qCheckPassword->execute();
    $qCheckPassword = $qCheckPassword->get_result();
    if ($qCheckPassword->num_rows == 1) {
        $UserRow = $qCheckPassword->fetch_assoc();
        $username = $UserRow['username'];
        $email = $UserRow['email'];
        $token = $UserRow['token'];

        $checkWallet = $conn->query("SELECT * FROM `wallets` WHERE `user`='$uid'");
        if ($checkWallet->num_rows == 0) {
            $conn->query("INSERT INTO `wallets` (`user`) VALUES ('$uid')");
        }

        $encryptLastIP = encrypt($currentIP);
        $checkIPLog = $conn->query("SELECT * FROM `ip_logs` WHERE `user`='$uid'");
        while($IPData = $checkIPLog->fetch_assoc()) {
            if ($checkIPLog->num_rows > 0) {
                $ipID = $IPData['id'];
                $loggedIP = $IPData['ip'];

                if (isNotEncrypted($loggedIP)) {
                    $loggedIP = encrypt($loggedIP);
                }

                if ($loggedIP != $currentIP) {
                    $conn->query("INSERT INTO `ip_logs` (`user`,`date`,`ip`) VALUES ('$uid','$now','$encryptLastIP')");
                
                    $to = $email;
                    $subject = "[Skybyn] Did you just login?";
                    $headers = 'From: noreply@skybyn.no' . "\r\n" .
                        'Reply-To: noreply@skybyn.no' . "\r\n" .
                        'Content-Type: text/html; charset=UTF-8' . "\r\n";
                
                    $message = '
                        <!DOCTYPE html>
                        <html>
                            <head>
                                <meta charset="utf-8">
                                <meta name="viewport" content="width=device-width, initial-scale=1">
                                <style>
                                    :root {
                                        --lightblue: rgba(183,231,236,1);
                                        --blue: rgba(35,176,255,1);
                                        --greyblue: rgba(42,106,133,1);
                                    }
                    
                                    body {
                                        margin: 0;
                                        padding: 20px;
                                        font-family: Arial, sans-serif;
                                        color: white;
                                        background: rgb(42,106,133);
                                        background-size: cover;
                                        background-position-y: bottom;
                                        background-position-x: center;
                                        background-attachment: fixed;
                                        font-size: 16px;
                                        line-height: 1.5;
                                    }
                    
                                    .container {
                                        max-width: 600px;
                                        margin: 0 auto;
                                        color: white;
                                        text-align: center;
                                        background: rgba(0,0,0,0.1);
                                        padding: 30px;
                                        border-radius: 5px;
                                        box-shadow: 0px 0px 10px rgba(0,0,0,0.1);
                                    }
                    
                                    .logo {
                                        display: block;
                                        width: 100px;
                                        height: auto;
                                        margin: 0 auto;
                                        padding: 0;
                                    }
                    
                                    .logo img {
                                        display: block;
                                        width: 100%;
                                        height: auto;
                                        margin: 0;
                                        padding: 0;
                                    }
                    
                                    h1 {
                                        font-size: 24px;
                                        margin-top: 20px;
                                        margin-bottom: 20px;
                                        text-align: center;
                                    }
                    
                                    p {
                                        margin-top: 0;
                                        margin-bottom: 20px;
                                    }
                                </style>
                            </head>
                            <body>
                                <div class="container">
                                    <img src="https://skybyn.no/assets/images/logo_clean.png" alt="Skybyn logo" class="logo">
                                    <h1>New login detected!</h1>
                                    <p>We detected a login from a new IP address, was this you?<br>
                                    <br>
                                    If this was you, please ignore/delete this email.<br>
                                    If this was NOT you, please change your password <a href="https://skybyn.com/forgot" target="_blank">here</a></p><br>
                                    <br>
                                    <p></p>
                                </div>
                            </body>
                        </html>
                        ';
                    #mail($to, $subject, $message, $headers);
                }
            } else {
                $conn->query("UPDATE `ip_logs` SET `date`='$now' WHERE `ip`='$currentIP' AND `user`='$uid'");
            }
        }

        if ($remember == "true") {
            createCookie("login_token",$token,"1","6");
        } else {
            createCookie("login_token",$token,"10","2");
        }

        $data = array(
            "responseCode" => "ok",
            "message" => "Welcome back $username"
        );
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data);
        $_SESSION['user'] = $uid;
    } else {
        $msg = "Wrong/Invalid password!";
        createCookie("msg", $msg, "10", null);
        $data = array(
            "responseCode" => "error",
            "message" => "$msg"
        );
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data);
    }
} else {
    $msg = "Unknown username.";
    createCookie("msg", $msg, "10", null);
    $data = array(
        "responseCode" => "error",
        "message" => "$msg"
    );
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data);
}
?>