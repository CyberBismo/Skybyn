<?php include "./functions.php";

$pid = $_POST['post_id'];
$text = $_POST['comment'];
$fixedText = fixEmojis($text, null);
$escapedText = htmlspecialchars($fixedText);
$escapedText = addslashes($escapedText);
$text = nl2br($escapedText);

if (!empty($text)) {
    $addComment = $conn->prepare("INSERT INTO `comments` (`post`,`user`,`content`,`date`) VALUES (?,?,?,?)");
    $addComment->bind_param("iiss", $pid, $uid, $text, $now);
    $addComment->execute();
    $cid = $addComment->insert_id;
    $addComment->close();

    $checkPostData = $conn->query("SELECT * FROM `posts` WHERE `id`='$pid'");
    $postData = $checkPostData->fetch_assoc();
    $postUser = $postData['user'];

    if ($postUser != $uid) {
        $conn->query("INSERT INTO `notifications` (`to`,`from`,`content`,`date`,`post`,`type`) VALUES ('$postUser','$uid','$text','$now','$pid','comment')");
    }

    $data = [
        'comment_id' => $cid,
        'post_id' => $pid,
    ];

    echo json_encode($data);
}
?>