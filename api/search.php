<?php include_once('../db_conn.php');
$uid = $_POST['userID'];
$s = $_POST['keyword'];

$search = "SELECT *
    FROM `users`
    WHERE `username` LIKE '%$s%'";
$searchres = mysqli_query($conn, $search);
$count = mysqli_num_rows($searchres);

$i = 0;

if ($count > 0) {
    while ($row = mysqli_fetch_assoc($searchres)) {
        $id = $row['id'];
        $username = $row['username'];
        $avatar = $row['avatar'];
        $fname = $row['first_name'];
        $mname = $row['middle_name'];
        $lname = $row['last_name'];
        $title = $row['title'];
        $nickname = $row['nickname'];
        $color = $row['color'];
        $online = $row['online_status'];
        $sex = $row['sex'];
    
        if ($avatar == "") {
            $avatar = "https://wesocial.space/sources/avatar.jpg";
        } else {
            $avatar = "https://wesocial.space/sources/users/avatars/$id/$avatar";
        }
        if ($nickname == "") {
            $nickname = $username;
        }

        $friendq = "SELECT *
            FROM `friendship`
            WHERE `user_id`='$uid'
            AND `friend_id`='$id'";
        $friendsresults = mysqli_query($conn, $friendq);
        $fcount = mysqli_num_rows($friendsresults);
        if ($fcount == 1) {
            $frow = mysqli_fetch_assoc($friendsresults);
            $friend_since = $frow['since'];
            $friend_status = $frow['status'];
            if ($friend_status == "friends") {
                $friend = "1"; # You are friends, so you can chat, view profile, block, remove friend.
            } else
            if ($friend_status == "received") {
                $friend = "2"; # You have received a friend request, so you can accept or block.
            } else
            if ($friend_status == "sent") {
                $friend = "3"; # You've sent, so you can cancel or block.
            } else
            if ($friend_status == "blocked") {
                $friend = "4"; # You've been blocked, so you can't do anything.
            } else
            if ($friend_status == "unblock") {
                $friend = "5"; # You've blocked them, so you can unblock.
            }
        } else {
            $friend = "0"; # You're not friends, so you can add as friend or block.
        }
    
        $search = array(
            "responseCode"=>"1",
            "message"=>"$count results found",
            "id"=>"$id",
            "username"=>"$username",
            "avatar"=>"$avatar",
            "fname"=>"$fname",
            "mname"=>"$mname",
            "lname"=>"$lname",
            "nickname"=>"$nickname",
            "title"=>"$title",
            "color"=>"$color",
            "friends"=>"$friend",
            "online"=>"$online",
            "sex"=>"$sex"
        );

        $data[$i] = $search;
        $i++;
    }
    echo json_encode($data);
} else {
    $json = array(
        "responseCode"=>"0",
        "message"=>"No results found"
    );
    echo json_encode($json);
}