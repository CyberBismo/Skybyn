<?php include "./functions.php";

$postIds = $_POST['ids'];
$postIdsList = implode(',', $postIds);

$sql = "SELECT * FROM `messages` WHERE `id` IN ($postIdsList)";
$result = $conn->query($sql);

$existingPostIds = [];
while ($row = $result->fetch_assoc()) {
  $existingPostIds[] = $row['id'];
}

$nonExistingPostIds = array_diff($postIds, $existingPostIds);

if (!empty($nonExistingPostIds)) {
    echo implode(',', $nonExistingPostIds);
}
?>