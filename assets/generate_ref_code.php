<?php include_once "functions.php";

if (isset($_SESSION['user'])) {
    $uid = $_SESSION['user'];
    $code = mt_rand(10000000, 99999999);
    $checkCode = $conn->query("SELECT * FROM `referral_code` WHERE `user`='$uid'");
    if ($checkCode->num_rows > 0) {
        $stmt = $conn->prepare("UPDATE `referral_code` SET `referral_code`= ?, `created`= ? WHERE `user`= ?");
        $stmt->bind_param("iii", $code, $now, $uid);
        $stmt->execute();
        echo $code;
    } else {
        $stmt = $conn->prepare("INSERT INTO `referral_code` (`user`,`referral_code`,`created`) VALUES (?, ?, ?)");
        $stmt->bind_param("isi", $uid, $code, $now);
        $stmt->execute();
        echo $code;
    }
}
?>