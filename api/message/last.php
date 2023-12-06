<?php include_once(__DIR__.'/../../config.php');
$uid = $_POST['userID'];
$fid = $_POST['friendID'];

$msgq = "SELECT *
    FROM `private_messages`
    WHERE `to` IN ('$uid','$fid')
    AND `from` IN ('$uid','$fid')
    ORDER BY `created` DESC
    LIMIT 1";
$mresult = mysqli_query($conn, $msgq);
$mrows = mysqli_fetch_assoc($presult);

$mid = $mrows['id'];
$mcontent = htmlspecialchars($mrows['content'], ENT_QUOTES);
$muser = $mrows['from'];
$mdate = $mrows['created'];

$breaks = array("<br />","<br>","<br/>");
$mcontent = str_ireplace($breaks, "\r\n", $mcontent);

$userq = "SELECT *
    FROM `users`
    WHERE `id`='$muser'";
$userres = mysqli_query($conn, $userq);
$urow = mysqli_fetch_assoc($userres);
$musername = $urow['username'];
$mnickname = $urow['nickname'];
$mavatar = $urow['avatar'];
if ($mnickname == "") {
    $mnickname = $musername;
}
if ($mavatar == "") {
    $mavatar = "https://wesocial.space/sources/avatar.jpg";
} else {
    $mavatar = "https://wesocial.space/sources/users/avatars/$muser/$mavatar";
}
$messages = array(
    "msgID" => "$mid",
    "username" => "$musername",
    "avatar" => "$mavatar",
    "nickname" => "$mnickname",
    "date" => "$mdate",
    "content" => utf8_decode($mcontent)
);

echo json_encode($messages);
?>