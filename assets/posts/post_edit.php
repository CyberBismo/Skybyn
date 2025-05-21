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
    $text = decrypt($post['content']);
    // Convert line breaks to \n for textarea readability
    $plain = str_replace(["\r\n", "\r", "<br>", "<br/>", "<br />"], "\n", html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8'));
    $text = $plain;

    echo json_encode([
        "status" => "success",
        "id" => $post_id,
        "content" => $text
    ]);
} else {
    echo json_encode(["status" => "error", "message" => "Post not found"]);
}

$stmt->close();
$conn->close();