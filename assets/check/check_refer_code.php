<?php
include_once "../functions.php";

$refer = $_POST['code'];

if ($refer >= 6) {
    $stmt = $conn->prepare("SELECT * FROM `referral_code` WHERE `referral_code` = ?");
    $stmt->bind_param("s", $refer);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $codeData = $result->fetch_assoc();
        $created = $codeData['created'];
        $fiveMinutesAgo = strtotime('- 5 minutes');
        
        if ($created <= $fiveMinutesAgo) {
            $stmt = $conn->prepare("SELECT * FROM `referral_code` WHERE `user` = ?");
            $stmt->bind_param("i", $uid);
            $stmt->execute();
            $result = $stmt->get_result();
            $codeData = $result->fetch_assoc();
            return $codeData['referral_code'];
        }
    }
}
?>