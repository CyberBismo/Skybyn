<?php include_once(__DIR__.'/../../config.php');
$id = $_POST['userID'];
$pid = $_POST['postID'];

$text = $_POST['content'];
$content = nl2br(htmlspecialchars(utf8_encode($_POST['content']), ENT_QUOTES));

if (!empty($content)) {
    $q = "UPDATE `posts`
        SET `content`='$content'
        WHERE `id`='$pid'";
    $do = mysqli_query($conn, $q);
    
    $json = array(
        "responseCode"=>"1",
        "message"=>"Updated",
        "content"=>"$text",
        "postID"=>"$pid",
        "userID"=>"$id"
    );
    echo json_encode($json);
} else {
    $json = array(
        "responseCode"=>"0",
        "message"=>"Something went wrong updating post."
    );
    echo json_encode($json);
}