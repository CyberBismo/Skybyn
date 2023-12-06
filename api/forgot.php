<?php include_once("../db_conn.php");
$email = $_POST['email'];

$q = "SELECT *
    FROM `users`
    WHERE `email`='$email'";
$check = mysqli_query($conn, $q);
$count = mysqli_num_rows($check);

if ($count == 1) {
    $code = substr(hash("md5", rand()), 0, 5);
    $q = "UPDATE `reset_codes`
        SET `code`='$code'
        WHERE `userid`='$uid'";
    mysqli_query($conn, $q);
    $to = $email;
    $subject = "[WeSocial] Password reset";

    $message = "
    <html>
        <head>
            <title>Did you forget your password?</title>
            <style>
                * {
                    font-family: arial;
                    background: black;
                }
                center {
                    width: 500px;
                    margin: 0 auto;
                    padding-top: 50px;
                    background: white;
                }
                p {
                    margin-top: 10%;
                    background: white;
                }
                button {
                    margin-bottom: 100px;
                    padding: 20px;
                    color: white;
                    font-size: 24px;
                    font-weight: bold;
                    text-transform: uppercase;
                    background: rgb(33, 137, 255);
                    border: none;
                    border-radius: 20px;
                }
            </style>
        </head>
        <body>
            <center>
                <p>We sent you this email after your request.<br><br>If you did NOT request this, please ignore/delete this email.<br><br>If you DID request this, please click the button/link below.</p><br>
                <a href='https://wesocial.space/reset?key=$key'><button>Reset</button></a><br>
                <a href='https://wesocial.space/reset?key=$key'>https://wesocial.space/reset?key=$key</a>
            </center>
        </body>
    </html>
    ";

    // Always set content-type when sending HTML email
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

    // More headers
    $headers .= 'From: No Reply  <noreply@wesocial.space>' . "\r\n";
    $headers .= 'Cc: support@wesocial.space' . "\r\n";

    $sent = mail($to,$subject,$message,$headers);
    if ($sent) {
        $json = array("responseCode"=>"1","message"=>"Check your email.");
        echo json_encode($json);
    }
} else {
    $json = array("responseCode"=>"0","message"=>"Email does not exist.");
    echo json_encode($json);
}
?>