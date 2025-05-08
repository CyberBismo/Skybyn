<?php include_once("../functions.php");

$postIds = $_POST['ids'];
$postIdsList = implode(',', $postIds);

$result = $conn->query("SELECT * FROM `messages` WHERE `id` IN ($postIdsList)");
$existingPostIds = [];
while ($row = $result->fetch_assoc()) {
  $existingPostIds[] = $row['id'];
}

$nonExistingPostIds = array_diff($postIds, $existingPostIds);

if (!empty($nonExistingPostIds)) {
    echo implode(',', $nonExistingPostIds);
}
?>