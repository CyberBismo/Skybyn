<?php include_once(__DIR__.'/../../config.php');
$id = $_POST['userID'];

$msgq = "SELECT *
    FROM `private_messages`
    WHERE `to`='$id'
    GROUP BY `from`
    ORDER BY `created` DESC";
$mresult = mysqli_query($conn, $msgq);

$i = 0;

while ($mrows = mysqli_fetch_assoc($mresult)) {

    $mid = $mrows['id'];
    $muser = $mrows['from'];
    $mcontent = $mrows['content'];
    $mread = $mrows['read'];
    
    $breaks = array("<br />","<br>","<br/>");
    $mcontent = str_ireplace($breaks, "\r\n", $mcontent);

    $mcontent = htmlspecialchars_decode(utf8_decode($mcontent), ENT_QUOTES);
    
    $userq = "SELECT *
        FROM `users`
        WHERE `id`='$muser'";
    $userres = mysqli_query($conn, $userq);
    
    $lmsgq = "SELECT *
        FROM `private_messages`
        WHERE `to`='$muser'
        ORDER BY `created` DESC";
    $lmr = mysqli_query($conn, $lmsgq);
    while($lmrows = mysqli_fetch_assoc($lmr)) {
        $lmfrom = $lmrows['from'];
        $lmcontent = $lmrows['content'];
        $lmdate = $lmrows['created'];
        
        $lmcontent = str_ireplace($breaks, "\r\n", $lmcontent);

        $lmcontent = htmlspecialchars_decode(utf8_decode($lmcontent), ENT_QUOTES);

        if ($lmfrom == $id) {
            $lmcontent = "Me: ".$lmcontent;
        }
    
        while ($urow = mysqli_fetch_assoc($userres)) {
            $musername = $urow['username'];
            $mnickname = $urow['nickname'];
            $mavatar = $urow['avatar'];
            $online = $urow['online_status'];
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
                "friendID" => "$muser",
                "username" => "$musername",
                "avatar" => "$mavatar",
                "nickname" => "$mnickname",
                "date" => "$lmdate",
                "content" => "$lmcontent",
                "online"=>"$online",
                "read" => "$mread"
            );
    
            $data[$i] = $messages;
            $i++;
        }
    }
}
header('Content-Type: application/json; charset=utf-8');
echo json_encode($data, JSON_PRETTY_PRINT);
?>