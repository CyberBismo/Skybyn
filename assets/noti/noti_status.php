<?php include "./functions.php";


if (isset($_POST['read'])) {
    $conn->query("UPDATE `notifications` SET `read`='1' WHERE `to`='$uid'");
} else {
    $nid = $_POST['noti'];
    $getNoti = $conn->query("SELECT * FROM `notifications` WHERE `id`='$nid'");
    $noti_status = $getNoti->fetch_assoc();
    if ($noti_status['read'] == "0") {
        $conn->query("UPDATE `notifications` SET `read`='1' WHERE `id`='$nid'");
        echo "1";
    }
}
?>