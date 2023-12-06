<?php include_once(__DIR__."/../../config.php");
$id = $_POST['id'];

$pq = "DELETE FROM `private_messages`
    WHERE `id`='$id'";
mysqli_query($conn, $pq);

$json = array(
    "responseCode"=>"1",
    "message"=>"Message deleted"
);
echo json_encode($json);
?>