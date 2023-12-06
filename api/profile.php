<?php include_once("../assets/db.php");
$id = $_GET['userID'];

$check = $conn->query("SELECT * FROM `users` WHERE `id`='$id'");
if ($check->num_rows == 1) {
    $row = $check->fetch_assoc();
    $username = $row['username'];
    $sec_q_one = $row['sec_q_one'];
    $sec_a_one = $row['sec_a_one'];
    $pin_v = $row['pin_v'];
    $pin = $row['pin'];
    $email = $row['email'];
    $nickname = $row['nickname'];
    $avatar = $row['avatar'];
    $fname = $row['first_name'];
    $mname = $row['middle_name'];
    $lname = $row['last_name'];
    $title = $row['title'];
    $rank = $row['rank'];
    $bio = $row['bio'];
    $color = $row['color'];
    $deactivated = $row['deactivated'];
    $deactivated_reason = $row['deactivated_reason'];
    $banned = $row['banned'];
    $banned_reason = $row['banned_reason'];
    $visible = $row['visible'];
    $registered = $row['registered'];
    $token = $row['token'];
    $reset = $row['reset'];
    $online = $row['online_status'];
    $relationship = $row['relationship_status'];
    
    if ($avatar == "") {
        $avatar = "https://skybyn.com/assets/images/avatar.jpg";
    } else {
        $avatar = "https://skybyn.com/uploads/users/avatars/$id/$avatar";
    }
    
    $json = array(
        "responseCode"=>"1",
        "username"=>"$username",
        "sec_q_one"=>"$sec_q_one",
        "sec_a_one"=>"$sec_a_one",
        "pin_v"=>"$pin_v",
        "pin"=>"$pin",
        "email"=>"$email",
        "fname"=>"$fname",
        "mname"=>"$mname",
        "lname"=>"$lname",
        "title"=>"$title",
        "nickname"=>"$nickname",
        "avatar"=>"$avatar",
        "bio"=>"$bio",
        "color"=>"$color",
        "rank"=>"$rank",
        "deactivated"=>"$deactivated",
        "deactivated_reason"=>"$deactivated_reason",
        "banned"=>"$banned",
        "banned_reason"=>"$banned_reason",
        "visible"=>"$visible",
        "registered"=>"$registered",
        "token"=>"$token",
        "reset"=>"$reset",
        "online"=>"$online",
        "relationship"=>"$relationship"
    );
    echo json_encode($json);
} else {
    $json = array("responseCode"=>"0","message"=>"Something went wrong loading profile data. ($id)");
    echo json_encode($json);
}
?>