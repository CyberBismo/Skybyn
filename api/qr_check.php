<?php include_once("../assets/conn.php");
$code = $_POST['code'];
$email = $_POST['user'];

// Use prepared statements to prevent SQL injection
$stmt = $conn->prepare("SELECT * FROM `users` WHERE `email` = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$checkUser = $stmt->get_result();

if ($checkUser->num_rows == 0) {
    $json = array("responseCode"=>0,"message"=>"User not found");
    echo json_encode($json);
    exit();
} else {
    $row = $checkUser->fetch_assoc();
    $user = $row['id'];
}

$stmt = $conn->prepare("SELECT * FROM `qr_sessions` WHERE `code` = ?");
$stmt->bind_param("s", $code);
$stmt->execute();
$check = $stmt->get_result();

if ($check->num_rows == 1) {
    $row = $check->fetch_assoc();
    $stmt = $conn->prepare("UPDATE `qr_sessions` SET `user` = ? WHERE `code` = ?");
    $stmt->bind_param("ss", $user, $code);
    $stmt->execute();

    $filePath = "../qr/temp/".$code.".png";
    if (file_exists($filePath)) {
        unlink($filePath);
    }
    
    $data = array('code' => $code, 'user' => $user);
    $options = array(
        'http' => array(
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($data),
        ),
    );
    $context  = stream_context_create($options);
    $result = file_get_contents('https://api.skybyn.no/qr_login.js', false, $context);

    $json = array("responseCode"=>1,"message"=>"You logged in successfully");
    echo json_encode($json);
} else {
    $json = array("responseCode"=>0,"message"=>"Invalid QR code");
    echo json_encode($json);
    exit();
}
?>