<?php  include_once "./functions.php";

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET");
header("Access-Control-Allow-Headers: Content-Type");

$email = $_POST['email'];
$password = hash("sha512", $_POST['password']);
$remember = $_POST['remember'];
$currentIP = getIP();
$new_token = rand(100000, 999999);

$checkEmail = $conn->query("SELECT * FROM `users` WHERE `email`='$email'");
if ($checkEmail->num_rows == 1) {
    $UserRow = $checkEmail->fetch_assoc();
    $uid = $UserRow['id'];
    $salt = $UserRow['salt'];
    $pw = hash("sha512", $salt."_".$password);
    $qCheckPassword = $conn->query("SELECT * FROM `users` WHERE `id`='$uid' AND `password`='$pw'");
    if ($qCheckPassword->num_rows == 1) {
        $UserRow = $qCheckPassword->fetch_assoc();
        $username = $UserRow['username'];
        $token = $UserRow['token'];
        $lastIP = $UserRow['ip'];
        
        if ($token == "0" || $token == "") {
            $conn->query("UPDATE `users` SET `token`='$new_token' WHERE `id`='$uid'");
            $token = $new_token;
        }

        //if ($lastIP != $currentIP) {
        //    $checkIPLog = $conn->query("SELECT * FROM `ip_logs` WHERE `user`='$uid' AND `ip`='$currentIP'");
        //    $checkIPData = $checkIPLog->fetch_assoc();
        //    $ip_code = $checkIPData['code'];
        //    
        //    if ($checkIPLog->num_rows == 0) {
        //        $conn->query("INSERT INTO `ip_logs` (`user_id`,`date`,`ip`) VALUES ('$uid','$now','$currentIP')");
        //    
        //        $to = $email;
        //        $subject = "Skybyn - Are you logging in now?";
        //        $headers = 'From: noreply@skybyn.no' . "\r\n" .
        //            'Reply-To: noreply@skybyn.no' . "\r\n" .
        //            'Content-Type: text/html; charset=UTF-8' . "\r\n";
        //    
        //        $message = '
        //        <!DOCTYPE html>
        //        <html>
        //            <head>
        //                <meta charset="utf-8">
        //                <meta name="viewport" content="width=device-width, initial-scale=1">
        //                <style>
        //                    :root {
        //                        --lightblue: rgba(183,231,236,1);
        //                        --blue: rgba(35,176,255,1);
        //                        --greyblue: rgba(42,106,133,1);
        //                    }
        //    
        //                    body {
        //                        margin: 0;
        //                        padding: 20px;
        //                        font-family: Arial, sans-serif;
        //                        color: white;
        //                        background: rgb(42,106,133);
        //                        background-size: cover;
        //                        background-position-y: bottom;
        //                        background-position-x: center;
        //                        background-attachment: fixed;
        //                        font-size: 16px;
        //                        line-height: 1.5;
        //                    }
        //    
        //                    .container {
        //                        max-width: 600px;
        //                        margin: 0 auto;
        //                        color: white;
        //                        text-align: center;
        //                        background: rgba(0,0,0,0.1);
        //                        padding: 30px;
        //                        border-radius: 5px;
        //                        box-shadow: 0px 0px 10px rgba(0,0,0,0.1);
        //                    }
        //    
        //                    .logo {
        //                        display: block;
        //                        width: 100px;
        //                        height: auto;
        //                        margin: 0 auto;
        //                        padding: 0;
        //                    }
        //    
        //                    .logo img {
        //                        display: block;
        //                        width: 100%;
        //                        height: auto;
        //                        margin: 0;
        //                        padding: 0;
        //                    }
        //    
        //                    h1 {
        //                        font-size: 24px;
        //                        margin-top: 20px;
        //                        margin-bottom: 20px;
        //                        text-align: center;
        //                    }
        //    
        //                    p {
        //                        margin-top: 0;
        //                        margin-bottom: 20px;
        //                    }
        //    
        //                    button {
        //                        background-color: #4CAF50;
        //                        color: #fff;
        //                        border: none;
        //                        padding: 10px 20px;
        //                        border-radius: 5px;
        //                        font-size: 16px;
        //                        cursor: pointer;
        //                        transition: background-color 0.3s ease;
        //                    }
        //    
        //                    button:hover {
        //                        background-color: #3e8e41;
        //                    }
        //    
        //                    .code-box {
        //                        margin: 40px 0;
        //                    }
        //                    .code-box code {
        //                        width: auto;
        //                        background-color: rgba(0,0,0,0.1);
        //                        border: 1px solid rgba(0,0,0,0.1);
        //                        padding: 10px 20px;
        //                        overflow: auto;
        //                        font-size: 24px;
        //                        line-height: 1.5;
        //                        letter-spacing: 10px;
        //                        border-radius: 10px;
        //                    }
        //                </style>
        //            </head>
        //            <body>
        //                <div class="container">
        //                    <img src="https://skybyn.no/assets/images/logo_clean.png" alt="Skybyn logo" class="logo">
        //                    <h1>New IP</h1>
        //                    <p>We detected a login from a new IP address, is this you?<br><br> If this is you, enter the code below.</p>
        //                    <div class="code-box">
        //                        <code>'.$token.'</code>
        //                    </div>
        //                </div>
        //            </body>
        //        </html>
        //        ';
        //    
        //        mail($to, $subject, $message, $headers);
        //        $msg = "Please check your email.";
        //        createCookie("msg", $msg, "10", null);
        //        createCookie("currentIP", $token, "10", null);
        //        createCookie("new_IP", $currentIP, "10", null);
        //        echo "new_ip";
        //    } else
        //    if ($ip_trusted == '0') {
        //        createCookie("currentIP", $ip_code, "10", null);
        //        createCookie("new_IP", $currentIP, "10", null);
        //        $_SESSION['user'] = $uid;
        //        echo "new_ip";
        //    } else {
        //        $_SESSION['user'] = $uid;
        //        if ($remember == "true") {
        //            createCookie("login_token",$uid,"1","6");
        //        }
        //        $data = array(
        //            "responseCode" => "ok",
        //            "user" => "$uid"
        //        );
        //        header('Content-Type: application/json; charset=utf-8');
        //        echo json_encode($data);
        //    }
        //} else {
            $checkWallet = $conn->query("SELECT * FROM `wallets` WHERE `user`='$uid'");
            if ($checkWallet->num_rows == 0) {
                $conn->query("INSERT INTO `wallets` (`user`) VALUES ('$uid')");
            }
            
            $conn->query("UPDATE `ip_logs` SET `date`='$now' WHERE `ip`='$currentIP' AND `user`='$uid'");

            if ($remember == "true") {
                createCookie("login_token",$token,"1","6");
            }
            $data = array(
                "responseCode" => "ok",
                "message" => "Welcome back $username"
            );
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode($data);
            $_SESSION['user'] = $uid;
        //}
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
    $msg = "Unknown e-mail address.";
    createCookie("msg", $msg, "10", null);
    $data = array(
        "responseCode" => "error",
        "message" => "$msg"
    );
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data);
}
?>