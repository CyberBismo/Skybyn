<?php include_once "../functions.php";

$pid = $_POST['id'];

$getPosts = $conn->query("SELECT * FROM `posts` WHERE `user` = $uid AND `id` = '$pid'");
if ($getPosts->num_rows == 1) {
    $post = $getPosts->fetch_assoc();
    $post_id = $post['id'];
    $post_content = html_entity_decode($post['content'], ENT_QUOTES | ENT_HTML5, 'UTF-8');

    $data = array(
        "status" => "success",
        "id" => $post_id,
        "content" => decrypt($post_content)
    );
} else {
    $data = array(
        "status" => "error",
        "message" => "Post not found"
    );
}
header("Content-type: application/json");
echo json_encode($data);