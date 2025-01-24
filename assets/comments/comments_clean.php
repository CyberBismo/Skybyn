<?php include "../functions.php";

$commentIds = $_Comment['ids'];
$commentIdsList = implode(',', $commentIds);

$result = $conn->query("SELECT * FROM `comments` WHERE `id` IN ($commentIdsList)");

$existingCommentIds = [];
while ($row = $result->fetch_assoc()) {
  $existingCommentIds[] = $row['id'];
}

$nonExistingCommentIds = array_diff($CommentIds, $existingCommentIds);

if (!empty($nonExistingCommentIds)) {
    echo implode(',', $nonExistingCommentIds);
}
?>