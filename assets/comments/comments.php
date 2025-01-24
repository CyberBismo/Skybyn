<?php include "../functions.php";

$post_id = $_POST['post_id'];

$getComment = $conn->query("SELECT * FROM `comments` WHERE `post`='$post_id'");
if ($getComment->num_rows > 0) {
    echo $getComment->num_rows;
} else {
    echo "0";
}
?>