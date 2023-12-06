<?php include_once(__DIR__."/../../db_conn.php");

$now = time();

$logq = "SELECT *
    FROM `activity`";

$checklog = mysqli_query($conn, $logq);
while($row = mysqli_fetch_assoc($checklog)) {
    $user = $row['user'];
    $time = $row['timeofactivity'];

    $five_min = strtotime('-5 min', $now);
    $ten_min = strtotime('-10 min', $now);

    if ($time < $five_min) {
        $update = "UPDATE `users`
            SET `online_status`='1'
            WHERE `id`='$user'";
        mysqli_query($conn, $update);
    } else
    if ($time < $ten_min) {
        $update = "UPDATE `users`
            SET `online_status`='2'
            WHERE `id`='$user'";
        mysqli_query($conn, $update);
    } else {
        $update = "UPDATE `users`
            SET `online_status`='0'
            WHERE `id`='$user'";
        mysqli_query($conn, $update);
    }
}

# ONLINE ACTIVITY MONITOR
# -----------------------
#
# Online status meaning:
#   0 = Offline
#   1 = Online
#   2 = Away

?>