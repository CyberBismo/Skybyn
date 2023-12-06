<?php include_once(__DIR__.'/../../config.php');
$id = $_POST['userID'];
$fid = $_POST['friendID'];

$msgq = "SELECT *
    FROM `private_messages`
    WHERE `to` IN ('$id','$fid')
    AND `from` IN ('$id','$fid')
    ORDER BY `created` ASC";
$mresult = mysqli_query($conn, $msgq);

$i = 0;

while ($mrows = mysqli_fetch_assoc($mresult)) {

    $mid = $mrows['id'];
    $mcontent = $mrows['content'];
    $muser = $mrows['from'];
    $mdate = $mrows['created'];
    $mread = $mrows['read'];
    
    $breaks = array("<br />","<br>","<br/>");
    $mcontent = str_ireplace($breaks, "\r\n", $mcontent);

    $mcontent = htmlspecialchars_decode(utf8_decode($mcontent), ENT_QUOTES);
    
    $userq = "SELECT *
        FROM `users`
        WHERE `id`='$muser'";
    $userres = mysqli_query($conn, $userq);

    while ($urow = mysqli_fetch_assoc($userres)) {
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
            "userID" => "$id",
            "friendID" => "$fid",
            "username" => "$musername",
            "avatar" => "$mavatar",
            "nickname" => "$mnickname",
            "date" => "$mdate",
            "content" => "$mcontent",
            "read" => "$mread"
        );

        $data[$i] = $messages;
        $i++;
    }
}
echo json_encode($data);
?>