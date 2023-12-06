<?php include_once(__DIR__."/../../config.php");

$id = $_POST['notiID'];

$nq = "UPDATE `notifications`
    SET `seen`='1'
    WHERE `id`='$id'";
mysqli_query($conn, $nq);

$json = array(
    "responseCode"=>"1",
    "message"=>"Notification read"
);
echo json_encode($json);
?>