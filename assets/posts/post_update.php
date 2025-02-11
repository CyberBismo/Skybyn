<?php include_once "../functions.php";

$pid = $_POST['id'];

$getPosts = $conn->query("SELECT * FROM `posts` WHERE `user` = $uid AND `id` = '$pid'");
if ($getPosts->num_rows == 1) {
    $text = htmlentities(encrypt($_POST['text']), ENT_QUOTES | ENT_HTML5, 'UTF-8');

    $updatePost = $conn->query("UPDATE `posts` SET `content`='$text' WHERE `id`='$pid'");

    if ($updatePost) {
        $data = array(
            "status" => "success",
            "id" => $post_id,
            "content" => decrypt($text)
        );
    } else {
        $data = array(
            "status" => "error",
            "message" => "Update failed"
        );
    }
} else {
    $data = array(
        "status" => "error",
        "message" => "Post not found"
    );
}
header("Content-type: application/json");
echo json_encode($data);