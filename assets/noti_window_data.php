<?php include "./functions.php";

$noti_id = $_POST['noti'];

$getNotiData = $conn->query("SELECT * FROM `notifications` WHERE `id`='$noti_id'");
$notiData = $getNotiData->fetch_assoc();

$noti_from = $notiData['from'];
$noti_content = htmlspecialchars(cleanUrls(nl2br($notiData['content'])), ENT_QUOTES, 'UTF-8');
$noti_date = $notiData['date'];
$noti_profile = $notiData['profile'];
$noti_post = $notiData['post'];
$noti_type = $notiData['type'];

$notiUserAvatar = getUser("id",$noti_from,"avatar");
$notiUserUsername = getUser("id",$noti_from,"username");

$data = array(
    'noti_from' => $noti_from,
    'noti_content' => $noti_content,
    'noti_date' => $noti_date,
    'noti_profile' => $noti_profile,
    'noti_post' => $noti_post,
    'noti_type' => $noti_type,
    'notiUserAvatar' => $notiUserAvatar,
    'notiUserUsername' => $notiUserUsername
);

$jsonData = json_encode($data);

header('Content-Type: application/json');
echo $jsonData;
?>