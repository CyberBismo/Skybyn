<?php include_once "functions.php";

$to = $_POST['to_id'];
$text = $_POST['text'];

$conn->query("INSERT INTO `messages` (`user`,`friend`,`content`,`date`) VALUES ('$uid','$to','$text','$now')");

$id = rand(1000,9999).$conn->insert_id;
$userData = $conn->query("SELECT * FROM `users` WHERE `id`='$fid'");
$userRow = $userData->fetch_assoc();
$myAvatar = $userRow['avatar'];

$data = array(
    "id" => $id,
    "my_avatar" => $myAvatar
);
echo json_encode($data);

?>