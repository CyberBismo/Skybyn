<?php include "./functions.php";

$pid = $_POST['post_id'];
$text = cleanUrls(nl2br(htmlspecialchars($_POST['comment'], ENT_QUOTES, 'UTF-8')));

if (!empty($_POST['comment'])) {
    $conn->query("INSERT INTO `comments` (`post`,`user`,`content`,`date`) VALUES ('$pid','$uid','$text','$now')");
}

$checkPostData = $conn->query("SELECT * FROM `posts` WHERE `id`='$pid'");
$postData = $checkPostData->fetch_assoc();
$postUser = $postData['user'];

if ($postUser != $uid) {
    $conn->query("INSERT INTO `notifications` (`to`,`from`,`content`,`date`,`post`,`type`) VALUES ('$postUser','$uid','$text','$now','$pid','comment')");
}

echo $text;
?>