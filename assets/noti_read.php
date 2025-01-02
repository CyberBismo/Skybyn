<?php include "./functions.php";

$nid = $_POST['noti'];

$noti = $conn->query("SELECT * FROM `notifications` WHERE `id`='$nid' AND `read`='0'");
if ($noti->num_rows > 0) {
    $conn->query("UPDATE `notifications` SET `read`='1' WHERE `id`='$nid'");
}
?>