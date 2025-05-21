<?php include_once "../functions.php";
header("Content-type: application/json");

$pid = intval($_POST['id']);
$plain = htmlentities(nl2br($_POST['content'], 1), ENT_QUOTES | ENT_HTML5, 'UTF-8');

$text = encrypt($plain);

$stmt = $conn->prepare("SELECT * FROM `posts` WHERE `user` = ? AND `id` = ?");
$stmt->bind_param("si", $uid, $pid);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $updateStmt = $conn->prepare("UPDATE `posts` SET `content` = ? WHERE `id` = ?");
    $updateStmt->bind_param("si", $text, $pid);
    $updateSuccess = $updateStmt->execute();

    if ($updateSuccess) {
        echo json_encode([
            "status" => "success",
            "id" => $pid,
            "content" => fixEmojis(nl2br(cleanUrls(html_entity_decode($plain, ENT_QUOTES | ENT_HTML5, 'UTF-8'))), 1)
        ]);
    } else {
        echo json_encode(["status" => "error", "message" => "Update failed"]);
    }

    $updateStmt->close();
} else {
    echo json_encode(["status" => "error", "message" => "Post not found"]);
}

$stmt->close();
$conn->close();