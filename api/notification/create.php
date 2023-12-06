<?php include_once(__DIR__.'/../../config.php');

$id = $_POST['fromID'];
$uid = $_POST['userID'];
$title = $_POST['title'];
$content = $_POST['content'];
$type = $_POST['type'];

$nq = "INSERT INTO `notifications` (
    `to`,
    `from`,
    `title`,
    `content`,
    `type`,
    `created`
)
VALUES (
    '$uid',
    '$id',
    '$title',
    '$content',
    '$type',
    UNIX_TIMESTAMP()
)";
mysqli_query($conn, $nq);
?>