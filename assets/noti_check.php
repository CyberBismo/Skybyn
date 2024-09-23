<?php include "functions.php";

if (isset($_SESSION['user'])) {
    $uid = $_SESSION['user'];

    $noti = $conn->query("SELECT * FROM `notifications` WHERE `to`='$uid' AND `read`='0'");
    $notiCount = $noti->num_rows;
    if ($notiCount > 0) {
        echo "unread";
    }
} else {
    echo "not_logged";
}

?>