<?php include_once(__DIR__.'/../../config.php');
$id = $_POST['userID'];
$pid = $_POST['postID'];
$cid = $_POST['commentID'];

$text = $_POST['content'];
$content = nl2br(htmlspecialchars(utf8_encode($_POST['content']), ENT_QUOTES));

if (!empty($content)) {
    $q = "UPDATE `comments`
        SET `content`='$content'
        WHERE `post_id`='$pid'
        AND `id`='$cid'";
    $do = mysqli_query($conn, $q);
    
    $json = array(
        "responseCode"=>"1",
        "message"=>"Updated",
        "content"=>"$text",
        "postID"=>"$pid",
        "commentID"=>"$cid",
        "userID"=>"$id"
    );
    echo json_encode($json);
} else {
    $json = array(
        "responseCode"=>"0",
        "message"=>"Something went wrong updating comment."
    );
    echo json_encode($json);
}