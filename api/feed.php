<?php
include "../assets/functions.php";

$response = array();

$getPosts = $conn->query("SELECT p.*
    FROM posts p
    WHERE p.user = $uid OR p.user IN (
        SELECT f.friend_id
        FROM friendship f
        WHERE f.user_id = $uid AND f.status = 'accepted'
    )
    ORDER BY p.created DESC
    LIMIT 3
");

while ($post = $getPosts->fetch_assoc()) {
    $post_id = $post['id'];
    $post_user = $post['user'];
    $post_content = $post['content'];
    $post_created = date("d M. y H:i:s", $post['created']);
    $post_links = $post['urls'];

    $getComments = $conn->query("SELECT * FROM `comments` WHERE `post`='$post_id'");
    $comments = array();

    while($commentData = $getComments->fetch_assoc()) {
        $commentID = $commentData['id'];
        $commentUser = $commentData['user'];
        $commentUsername = getUser("id", $commentData['user'], "username");
        $commentAvatar = getUser("id", $commentData['user'], "avatar");
        if ($commentAvatar == "") {
            $commentAvatar = "./assets/images/logo_faded_clean.png";
        }
        $commentText = fixEmojis(nl2br(cleanUrls($commentData['content'])), 1);

        $comments[] = array(
            'id' => $commentID,
            'user' => $commentUser,
            'username' => $commentUsername,
            'avatar' => $commentAvatar,
            'content' => $commentText
        );
    }

    $getPostUser = $conn->query("SELECT * FROM `users` WHERE `id`='$post_user'");
    $postUser = $getPostUser->fetch_assoc();
    $post_user_name = $postUser['username'];
    $post_user_avatar = "./" . $postUser['avatar'];
    if ($post_user_avatar == "./") {
        $post_user_avatar = "./assets/images/logo_faded_clean.png";
    }

    $post_video = convertVideo($post_content);
    $post_content_res = fixEmojis(nl2br(cleanUrls($post_content)), 1);

    $response[] = array(
        'id' => $post_id,
        'user' => array(
            'id' => $post_user,
            'username' => $post_user_name,
            'avatar' => $post_user_avatar
        ),
        'content' => $post_content_res,
        'created' => $post_created,
        'urls' => $post_links,
        'video' => $post_video,
        'comments' => $comments
    );
}

header('Content-Type: application/json');
echo json_encode($response);
?>
