<?php include_once("../db_conn.php");
$id = $_POST['userID'];
$email = $_POST['email'];
$newEmail = $_POST['new_email'];

$checkNewEmail = "SELECT *
    FROM `users`
    WHERE `email`='$newEmail'";
$resNewEmail = mysqli_query($conn, $checkNewEmail);
$countNewEmail = mysqli_num_rows($resNewEmail);
if ($countNewEmail == 0) {
    $token = rand(10000,99999);
        
    $to = $email;
    $subject = "[WeSocial] Email verification";
    
    $message = "
    <html>
        <head>
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
                .code {
                    font-size: 36px;
                    color: $color;
                }
            </style>
        </head>
        <body>
            <center>
                <div class='code'>$token</div>
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
            
    mail($to,$subject,$message,$headers);
    
    $json = array(
        "responseCode"=>"1",
        "message"=>"Email sent!",
        "token"=>"$token"
    );
    echo json_encode($json);
} else {
    $json = array(
        "responseCode"=>"0",
        "message"=>"Email already in use."
    );
    echo json_encode($json);
}