<?php include_once "../functions.php";
header("Content-type: application/json");

$pid = intval($_POST['id']);
$text = encrypt(htmlentities($_POST['content'], ENT_QUOTES | ENT_HTML5, 'UTF-8'));

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
            "content" => htmlentities(decrypt($text), ENT_QUOTES | ENT_HTML5, 'UTF-8')
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