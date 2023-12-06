<?php include_once(__DIR__."/../../config.php");

$id = $_POST['notiID'];

$nq = "DELETE FROM `notifications`
    WHERE `id`='$id'";
mysqli_query($conn, $nq);

$json = array(
    "responseCode"=>"1",
    "message"=>"Notification deleted"
);
echo json_encode($json);
?>