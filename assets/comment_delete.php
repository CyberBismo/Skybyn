<?php include "./functions.php";

$cid = $_POST['comment_id'];

$conn->query("DELETE FROM `comments` WHERE `id`='$cid'");
?>