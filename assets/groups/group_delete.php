<?php include "../functions.php";

$gid = $_POST['group'];

$checkGroup = $conn->query("SELECT * FROM `groups` WHERE `id`='$gid'");
$gd = $checkGroup->fetch_assoc();
$gowner = $gd['owner'];

if ($gowner == $uid) {
    $conn->query("DELETE FROM `groups` WHERE `id`='$gid'");
    $conn->query("DELETE FROM `group_members` WHERE `group`='$gid'");
    $conn->query("DELETE FROM `group_messages` WHERE `group`='$gid'");
    $response = [
        "response" => "ok",
        "message" => "The group has been deleted"
    ];

    echo json_encode($response);
} else {
    $response = [
        "response" => "error",
        "message" => "You are not the owner of this group and cannot delete it"
    ];

    echo json_encode($response);
}
?>