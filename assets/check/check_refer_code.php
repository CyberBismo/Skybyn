<?php
include_once "../functions.php";

$refer = $_POST['code'];

if ($refer >= 6) {
    $checkCode = $conn->query("SELECT * FROM `referral_code` WHERE `referral_code`='$refer'");
    if ($checkCode->num_rows > 0) {
        $codeData = $checkCode->fetch_assoc();
        $created = $codeData['created'];

        // Check if created date is equal to or more than 5 minutes ago
        $fiveMinutesAgo = strtotime('-5 minutes');
        $createdTimestamp = strtotime($created);

        if ($createdTimestamp >= $fiveMinutesAgo) {
            // Delete the record
            $deleteCode = $conn->query("DELETE FROM `referral_code` WHERE `referral_code`='$refer'");
            if ($deleteCode) {
                echo "expired";
            }
        }
    }
}
?>