<?php include_once "functions.php";

$dob = $_POST['dob'];
$fname = $_POST['fname'];
$mname = $_POST['mname'];
$lname = $_POST['lname'];
$email = $_POST['email'];
$username = $_POST['username'];
$password = hash("sha512", $_POST['password']);
$salt = hash("sha512", rand());
$pw = hash("sha512", $salt."_".$password);
$ip = getIP();
$country = geoData("countryName");
$lang = geoData("countryCode");

$qCheckEmail = $conn->query("SELECT * FROM `users` WHERE `email`='$email'");
if ($qCheckEmail->num_rows == 0) {
        $conn->query("INSERT INTO `users` 
        (
                `username`,
                `email`,
                `birth_date`,
                `password`,
                `salt`,
                `language`,
                `registration_date`,
                `country`,
                `ip`,
                `verified`
        ) VALUES (
                '$username',
                '$email',
                '$dob',
                '$pw',
                '$salt',
                '$lang',
                '$now',
                '$country',
                '$ip',
                '1'
        )");

        $to = $email;
        $subject = "Skybyn - Verify email";
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

                .code-box {
                        margin: 40px 0;
                }
                .code-box code {
                        width: auto;
                        background-color: rgba(0,0,0,0.1);
                        border: 1px solid rgba(0,0,0,0.1);
                        padding: 10px 20px;
                        overflow: auto;
                        font-size: 24px;
                        line-height: 1.5;
                        letter-spacing: 10px;
                        border-radius: 10px;
                }
                </style>
        </head>
        <body>
                <div class="container">
                <img src="https://skybyn.no/assets/images/logo_clean.png" alt="Skybyn logo" class="logo">
                <h1>Welcome to Skybyn</h1>
                <p>Enter this code to activate your account</p>
                <div class="code-box">
                        <code>'.$token.'</code>
                </div>
                </div>
        </body>
        </html>
        ';

        mail($to, $subject, $message, $headers);
        $msg = "We just sent you an email with an activation code.";
        createCookie("msg", $msg, "10", null);
        $id = $conn->inserted_id;
        $_SESSION['registration_complete'] = $id;
        echo "signup_complete";
} else {
    $msg = "This email is already in use.";
    echo $msg;
}

$conn->close();
?>