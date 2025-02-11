<?php include_once "../functions.php";
header("Content-type: application/json");

$pid = intval($_POST['id']);

$stmt = $conn->prepare("SELECT id, content FROM `posts` WHERE `user` = ? AND `id` = ?");
$stmt->bind_param("ii", $uid, $pid);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $post = $result->fetch_assoc();
    $post_id = $post['id'];
    $post_content = decrypt($post['content']);
    $post_content = html_entity_decode($post_content, ENT_QUOTES | ENT_HTML5, 'UTF-8');

    echo json_encode([
        "status" => "success",
        "id" => $post_id,
        "content" => $post_content
    ]);
} else {
    echo json_encode(["status" => "error", "message" => "Post not found"]);
}

$stmt->close();
$conn->close();