<?php include "./functions.php";

$cid = $_POST['comment_id'];

$checkComment = $conn->query("SELECT * FROM `comments` WHERE `id`='$cid'");
$commentData = $checkComment->fetch_assoc();
$pid = $commentData['post'];

$conn->query("DELETE FROM `comments` WHERE `id`='$cid'");

$data = [
    'comment_id' => $cid,
    'post_id' => $pid,
];
echo json_encode($data);
?>