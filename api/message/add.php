<?php include_once(__DIR__."/../../config.php");
$id = $_POST['userID'];
$fid = $_POST['friendID'];

$content = nl2br(htmlspecialchars(utf8_encode($_POST['content']), ENT_QUOTES));

$q = "INSERT INTO `private_messages` (
        `from`,
        `to`,
        `content`,
        `created`
    )
    VALUES (
        '$id',
        '$fid',
        '$content',
        UNIX_TIMESTAMP()
    )";
$post = mysqli_query($conn, $q);

$_POST['type'] = "chat";

$fuserq = "SELECT *
    FROM `users`
    WHERE `id`='$fid'";
$fucheck = mysqli_query($conn, $fuserq);
$furow = mysqli_fetch_assoc($fucheck);
$ftoken = $furow['token'];
$_POST['token'] = $ftoken;

$userq = "SELECT *
    FROM `users`
    WHERE `id`='$id'";
$ucheck = mysqli_query($conn, $userq);
$urow = mysqli_fetch_assoc($ucheck);
$username = $urow['username'];

$_POST['title'] = $username;
$_POST['body'] = $content;
$_POST['from'] = $id;

include_once("../notification/firebase.php");

if ($post) {
    $json = array(
        "responseCode"=>"1",
        "message"=>"Sent"
    );
    echo json_encode($json);
} else {
    $json = array("responseCode"=>"0","message"=>"Something went wrong sending message.");
    echo json_encode($json);
}