<?php require_once "./functions.php";

// Create error log file if it doesn't exist and log errors
if (!file_exists('error_log.txt')) {
    file_put_contents('error_log.txt', '');
}
ini_set('log_errors', 1);
ini_set('error_log', 'error_log.txt');
error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT & ~E_WARNING);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET");
header("Access-Control-Allow-Headers: Content-Type");
header('Content-Type: application/json; charset=utf-8');

$dob = isset($_POST['dob']) ? $_POST['dob'] : '';
$fname = isset($_POST['fname']) ? encrypt($_POST['fname']) : '';
$mname = isset($_POST['mname']) ? encrypt($_POST['mname']) : '';
$lname = isset($_POST['lname']) ? encrypt($_POST['lname']) : '';
$email = isset($_POST['email']) ? $_POST['email'] : '';
$email_c = isset($_POST['email_c']) ? $_POST['email_c'] : '';
$username = isset($_POST['username']) ? $_POST['username'] : '';
$refer = isset($_POST['refer']) ? $_POST['refer'] : '';

$password = isset($_POST['password']) ? hash("sha512", $_POST['password']) : '';
$salt = hash("sha512", rand());
$pw = hash("sha512", $salt . "_" . $password);

$ip = encrypt(getIP());
$country = "";#function_exists('geoData') ? geoData("countryName") : '';
$lang = "";#function_exists('geoData') ? geoData("countryCode") : '';
$pack = isset($_POST['pack']) ? $_POST['pack'] : '';

$token = rand();
$now = time();

$e_email = encrypt($email);

if (isset($_POST['private'])) {
    $private = "1";
} else {
    $private = "0";
}

if (isset($_POST['visible'])) {
    $visible = "1";
} else {
    $visible = "0";
}

$cleanEmailCheck = $conn->query("DELETE FROM `email_check` WHERE `email`='$email'");
if ($email_c == "" && $dob != "" && $email != "" && $username != "" && $password != "") {
    $conn->query("INSERT INTO `users` (`username`,`email`,`birth_date`,`password`,`salt`,`registration_date`,`token`,`first_name`,`middle_name`,`last_name`,`private`,`visible`,`country`) VALUES ('$username','$e_email','$dob','$pw','$salt',$now,'$token','$fname','$mname','$lname','$private','$visible','$country')");

    $id = $conn->insert_id;
    
    if (isset($beta) && !empty($beta)) {
        $updateBetaAccess = $conn->query("UPDATE `beta_access` SET `user_id`='$id' WHERE `key`='$beta'");
    }

    if (!empty($refer)) {
        $checkRefer = $conn->query("SELECT * FROM `referral_code` WHERE `referral_code`='$refer'");
        if ($checkRefer->num_rows == 1) {
            $referralData = $checkRefer->fetch_assoc();
            $user = $referralData['user'];
            friendship($id, $user, "referral");
            notify($id,"system", "referral");
            notify($user,"system", "new_friend");
        }
    }

    if ($pack == "op") {
        $conn->query("UPDATE `users` SET `private`='0' AND `visible`='1' WHERE `id`='$id'");
    } else
    if ($pack == "pp") {
        $conn->query("UPDATE `users` SET `private`='1' AND `visible`='0' WHERE `id`='$id'");
    } else {
        $conn->query("UPDATE `users` SET `private`='$private' AND `visible`='$visible' WHERE `id`='$id'");
    }

    $data = array(
        "responseCode" => "ok",
        "token" => $token,
        "user" => $id
    );
    echo json_encode($data);
} else {
    $data = array(
        "responseCode" => "error",
        "message" => "Please fill in all required fields"
    );
    echo json_encode($data);
}


function sendWelcomeEmail($to) {
    $subject = "Welcome to Skybyn";
    $headers = 'From: noreply@skybyn.com' . "\r\n" .
        'Reply-To: noreply@skybyn.com' . "\r\n" .
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

            button {
                background-color: #4CAF50;
                color: #fff;
                border: none;
                padding: 10px 20px;
                border-radius: 5px;
                font-size: 16px;
                cursor: pointer;
                transition: background-color 0.3s ease;
            }

            button:hover {
                background-color: #3e8e41;
            }
            </style>
        </head>
        <body>
            <div class="container">
            <img src="https://skybyn.no/assets/images/logo_clean.png" alt="Skybyn logo" class="logo">
            <h1>Welcome to Skybyn</h1>
            <hr>
            <p>
            It\'s a pleasure having you omboard!<br>
            <br>
            We want you to feel safe, so do not hesitate to report any unconfortable activities you encounter.
            </p>
            </div>
        </body>
        </html>
        ';

    mail($to, $subject, $message, $headers);
}