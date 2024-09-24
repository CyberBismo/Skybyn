<?php include_once "functions.php";

if (isset($_SESSION['user'])) {
    $uid = $_SESSION['user'];
    $code = rand(10000000, 99999999);
    $in_five_min = strtotime(' +5 minutes ');
    $checkCode = $conn->query("SELECT * FROM `referral_code` WHERE `user`='$uid'");
    if ($checkCode->num_rows == 1) {
        $codeData = $checkCode->fetch_assoc();
        $date = $codeData['created'];
        if ($date < time()) {
            $conn->query("DELETE FROM `referral_code` WHERE `user`='$uid'");
            $stmt = $conn->prepare("INSERT INTO `referral_code` (`referral_code`,`created`,`user`) VALUES (?, ?, ?)");
            $stmt->bind_param("iii", $code, $in_five_min, $uid);
            $stmt->execute();
            echo $code;
        }
    } else {
        $stmt = $conn->prepare("INSERT INTO `referral_code` (`referral_code`,`created`,`user`) VALUES (?, ?, ?)");
        $stmt->bind_param("iii", $code, $in_five_min, $uid);
        $stmt->execute();
        echo $code;
    }
}
?>