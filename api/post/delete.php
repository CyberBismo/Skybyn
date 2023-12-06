<?php include_once(__DIR__."/../../config.php");
$id = $_POST['userID'];
$pid = $_POST['postID'];

$pq = "DELETE FROM `posts`
    WHERE `id`='$pid'";
mysqli_query($conn, $pq);
$cq = "DELETE FROM `comments`
    WHERE `post_id`='$pid'";
mysqli_query($conn, $cq);
$fq = "DELETE FROM `page_uploads`
    WHERE `post`='$pid'";
mysqli_query($conn, $fq);
$ffq = "SELECT *
    FROM `page_uploads`
    WHERE `post`='$pid'";
$ffr = mysqli_query($conn, $ffq);
$ffcount = mysqli_num_row($ffr);
if ($ffcount > 0) {
    $file = mysqli_fetch_assoc($ffr);
    unlink($file['file']);
}

$json = array(
    "responseCode"=>"1",
    "message"=>"Post deleted"
);
echo json_encode($json);
?>