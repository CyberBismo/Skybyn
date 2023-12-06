<?php include_once(__DIR__.'/../../config.php');
$id = $_POST['userID'];
$pid = $_POST['postID'];

$cid = rand();
$content = nl2br(htmlspecialchars(utf8_encode($_POST['content']), ENT_QUOTES));

if (!empty($content)) {
    $q = "INSERT INTO `comments` (
            `id`,
            `post_id`,
            `content`,
            `user`,
            `created`
        )
        VALUES (
            '$cid',
            '$pid',
            '$content',
            '$id',
            UNIX_TIMESTAMP()
        )";
    $do = mysqli_query($conn, $q);

    $_POST['type'] = "comment";
    
    $fuserq = "SELECT *
        FROM `users`
        WHERE `id`='$fid'";
    $fucheck = mysqli_query($conn, $fuserq);
    $furow = mysqli_fetch_assoc($fucheck);
    $fusername = $furow['username'];
    $_POST['token'] = strtolower($fusername);
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
    
    $json = array(
        "responseCode"=>"1",
        "message"=>"Commented",
        "postID"=>"$pid",
        "commentID"=>"$cid",
        "userID"=>"$id"
    );
    echo json_encode($json);
} else {
    $json = array(
        "responseCode"=>"0",
        "message"=>"Something went wrong adding comment."
    );
    echo json_encode($json);
}