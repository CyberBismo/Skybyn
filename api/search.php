<?php include_once("../assets/conn..php");

header('Content-Type: application/json');

if (isset($_POST['userID']) && isset($_POST['keyword'])) {
    $uid = $_POST['userID'];
    $s = $_POST['keyword'];

    $searchUsers = $conn->query("SELECT * FROM `users` WHERE `username` LIKE '%$s%' OR `first_name` LIKE '%$s%' OR `last_name` LIKE '%$s%'");
    $searchPages = $conn->query("SELECT * FROM `pages` WHERE `name` LIKE '%$s%'");
    $searchGroups = $conn->query("SELECT * FROM `groups` WHERE `name` LIKE '%$s%'");
    $searchMarkets = $conn->query("SELECT * FROM `markets` WHERE `name` LIKE '%$s%'");
    $searchGames = $conn->query("SELECT * FROM `games` WHERE `name` LIKE '%$s%'");

    $i = 0;

    if ($s == "user") {
        if ($searchUsers->num_rows > 0) {
            while ($row = $searchUsers->fetch_assoc()) {
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
    
                $friendq = $conn->query("SELECT * FROM `friendship` WHERE `user_id`='$uid' AND `friend_id`='$id'");
                if ($friendq->num_rows == 1) {
                    $frow = $friendq->fetch_assoc();
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
                "message"=>"No users found"
            );
            echo json_encode($json);
        }
    } else
    if ($s == "page") {
        if ($searchPages->num_rows > 0) {

        } else {
            $json = array(
                "responseCode"=>"0",
                "message"=>"No page found"
            );
            echo json_encode($json);
        }
    } else
    if ($s == "group") {
        if ($searchGroups->num_rows > 0) {

        } else {
            $json = array(
                "responseCode"=>"0",
                "message"=>"No group found"
            );
            echo json_encode($json);
        }
    } else
    if ($s == "markets") {
        if ($searchMarkets->num_rows > 0) {

        } else {
            $json = array(
                "responseCode"=>"0",
                "message"=>"No market found"
            );
            echo json_encode($json);
        }
    } else
    if ($s == "games") {
        if ($searchGames->num_rows > 0) {

        } else {
            $json = array(
                "responseCode"=>"0",
                "message"=>"No game found"
            );
            echo json_encode($json);
        }
    }
} else {
    $json = array(
        "responseCode"=>"0",
        "message"=>"Required data not provided"
    );
    echo json_encode($json);
}