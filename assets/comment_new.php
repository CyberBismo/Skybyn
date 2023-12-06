<?php include "./functions.php";

$pid = $_POST['post_id'];
$text = $_POST['comment'];
$fixedText = fixEmojis($text, null);
$escapedText = htmlspecialchars($fixedText);
$escapedText = addslashes($escapedText);
$text = nl2br($escapedText);

if (!empty($text)) {
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