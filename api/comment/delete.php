<?php include_once(__DIR__.'/../../config.php');
$id = $_POST['userID'];
$cid = $_POST['commentID'];

$cq = "DELETE FROM `comments`
    WHERE `id`='$cid'";
mysqli_query($conn, $cq);

$json = array(
    "responseCode"=>"1",
    "message"=>"Comment deleted"
);
echo json_encode($json);
?>