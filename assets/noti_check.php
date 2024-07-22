<?php include "./functions.php";

$noti = $conn->query("SELECT * FROM `notifications` WHERE `to`='$uid' AND `read`='0'");
$notiCount = $noti->num_rows;
if ($notiCount > 0) {
    echo "unread";
}

?>