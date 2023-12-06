<?php include_once(__DIR__.'/../../config.php');
$id = $_POST['userID'];

$friendq = "SELECT *
    FROM `friendship`
    WHERE `user_id`='$id'
    AND `status`='friends'";
$friendsresults = mysqli_query($conn, $friendq);
$friendcount = mysqli_num_rows($friendsresults);

$i = 0;

if ($friendcount > 0) {
    while ($frow = mysqli_fetch_assoc($friendsresults)) {
        $friend_id = $frow['friend_id'];
        $friend_since = $frow['since'];
        $userq = "SELECT *
            FROM `users`
            WHERE `id`='$friend_id'";
        $userresults = mysqli_query($conn, $userq);
        while ($frow = mysqli_fetch_assoc($userresults)) {
            $fusername = $frow['username'];
            $fnickname = $frow['nickname'];
            $favatar = $frow['avatar'];
            $fonline = $frow['online_status'];
            if ($fnickname == "") {
                $fnickname = $fusername;
            }
            if ($favatar == "") {
                $favatar = "https://wesocial.space/sources/avatar.jpg";
            } else {
                $favatar = "https://wesocial.space/sources/users/avatars/$friend_id/$favatar";
            }
            
            $friends = array(
                "responseCode"=>"1",
                "friend_id"=>"$friend_id",
                "username"=>"$fusername",
                "nickname"=>"$fnickname",
                "avatar"=>"$favatar",
                "online"=>"$fonline"
            );
            $data[$i] = $friends;
            $i++;
        }
    }
    echo json_encode($data);
} else {
    echo "You have no friends is your list yet.";
}
?>