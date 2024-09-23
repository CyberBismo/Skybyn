<?php
include_once "functions.php";
header('Content-Type: application/json; charset=utf-8');

$last_id = $_POST['last'];
$post_id = $_POST['post_id'];

if ($last_id == "-Infinity") {
    $last_id = 0;
}

if ($post_id != 0) {
    $getPosts = $conn->query("SELECT * FROM `posts` WHERE `id` = '$post_id'");
    if ($getPosts->num_rows > 0) {
        $data = [
            'responseCode' => 1,
            'message' => "Post found.",
            'post_id' => $post_id
        ];
    } else {
        $data = [
            'responseCode' => 0,
            'message' => "Post not found."
        ];
    }
} else {
    $getPosts = $conn->query("SELECT p.*
        FROM posts p
        WHERE p.user = $uid OR p.user IN (
            SELECT f.friend_id
            FROM friendship f
            WHERE f.user_id = $uid AND f.status = 'accepted'
        )
        AND p.id > $last_id
        ORDER BY p.created DESC
    ");
    if ($getPosts->num_rows > 0) {
        $posts = [];
        while ($post = $getPosts->fetch_assoc()) {
            $post_id = $post['id'];
            $posts[] = $post_id;
        }
        $data = [
            'responseCode' => 1,
            'message' => "Post(s) found.",
            'posts' => $posts
        ];
    } else {
        $data = [
            'responseCode' => 0,
            'message' => "No more posts."
        ];
    }
}
echo json_encode($data);
?>