<?php include "../functions.php";
echo "verified";
return false;

$email = $_POST['email'];
$code = rand();
if (isset($_POST['resend'])) {
    $resend = $_POST['resend'];
} else {
    $resend = "0";
}
$newTime = $now + (5 * 60);

// Prepared statement to check existing email
$stmt = $conn->prepare("SELECT * FROM `users` WHERE `email` = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$checkExistingEmail = $stmt->get_result();

if ($checkExistingEmail->num_rows == 1) {
    echo "used";
    return false;
}

// Prepared statement to check email
$stmt = $conn->prepare("SELECT * FROM `email_check` WHERE `email` = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$checkEmail = $stmt->get_result();

if ($checkEmail->num_rows == 1) {
    $emailData = $checkEmail->fetch_assoc();
    $code = $emailData['code'];
    $verified = $emailData['verified'];
    $time = $emailData['time_sent'];
    $remaining = $time - $now;

    if ($resend == "1") {
        if ($time <= $now) {
            $stmt = $conn->prepare("UPDATE `email_check` SET `time_sent` = ? WHERE `email` = ?");
            $stmt->bind_param("is", $newTime, $email);
            $stmt->execute();
            sendVerificationEmail($email, $code);
            echo $remaining;
        } else {
            echo $remaining;
        }
    } else {
        if ($verified == "1") {
            echo "verified";
        } else {
            echo "sent_before";
        }
    }
} else {
    $stmt = $conn->prepare("INSERT INTO `email_check` (`email`, `code`, `time_sent`) VALUES (?, ?, ?)");
    $stmt->bind_param("ssi", $email, $code, $newTime);
    $stmt->execute();
    sendVerificationEmail($email, $code);
    echo "sent";
}

function sendVerificationEmail($email, $code) {
    $to = $email;
    $subject = "Skybyn - Verify email";
    
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    $headers .= "From: no-reply@skybyn.no\r\n";
    $headers .= "Reply-To: no-reply@skybyn.no\r\n";
    
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
                    line-height: 1.5;
                    font-size: 24px;
                    letter-spacing: 10px;
                    border-radius: 10px;
                }
            </style>
        </head>
        <body>
            <div class="container">
                <img src="https://skybyn.no/assets/images/logo_clean.png" alt="Skybyn logo" class="logo">
                <h1>Verify your email</h1>
                <p>Enter the code below into the field displayed on the website.</p>
                <div class="code-box">
                    <code>'.$code.'</code>
                </div>
            </div>
        </body>
    </html>
    ';
    
    mail($to, $subject, $message, $headers);
}
?>