<?php include "./functions.php";

$nid = $_POST['noti'];

if ($nid == "all") {
    $conn->query("DELETE FROM `notifications` WHERE `to`='$uid'");
} else {
    $conn->query("DELETE FROM `notifications` WHERE `id`='$nid'");
}
?>