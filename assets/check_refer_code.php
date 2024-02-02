<?php include_once "functions.php";

$refer = $_POST['code'];

if ($refer >= 6) {
    $checkCode = $conn->query("SELECT * FROM `referral_code` WHERE `referral_code`='$refer'");
    if ($checkCode->num_rows > 0) {
        $codeData = $checkCode->fetch_assoc();
        $user = $codeData['user'];
        $users = $conn->query("SELECT * FROM `users` WHERE `id`='$user'");
        $userData = $users->fetch_assoc();
        $ref_a = $userData['avatar'];
        $ref_un = $userData['username'];
        echo '<div class="ref_u_avatar"><img src="'.$ref_a.'"></div>
        <div class="ref_u_name">'.$ref_un.'</div>';
    }
}
?>