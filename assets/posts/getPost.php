<?php include_once "../functions.php";

$pid = $_POST['id'];

$getPosts = $conn->query("SELECT * FROM `posts` WHERE `user` = $uid AND `id` = '$pid'");
if ($getPosts->num_rows == 1) {
    $post = $getPosts->fetch_assoc();
    $post_content = $post['content'];

    $data = array(
        "status" => "success",
        "content" => $post_content
    );
} else {
    $data = array(
        "status" => "error",
        "message" => "Post not found"
    );
}
header("Content-type: application/json");
echo json_encode($data);