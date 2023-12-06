<?php include "./functions.php";

$commentIds = $_Comment['ids'];
$commentIdsList = implode(',', $commentIds);

$sql = "SELECT * FROM `Comments` WHERE `id` IN ($commentIdsList)";
$result = $conn->query($sql);

$existingCommentIds = [];
while ($row = $result->fetch_assoc()) {
  $existingCommentIds[] = $row['id'];
}

$nonExistingCommentIds = array_diff($CommentIds, $existingCommentIds);

if (!empty($nonExistingCommentIds)) {
    echo implode(',', $nonExistingCommentIds);
}
?>