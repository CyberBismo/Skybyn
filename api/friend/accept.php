<?php include_once(__DIR__.'/../../config.php');
$fid = $_POST['friendID'];
$uid = $_POST['userID'];

$q = "SELECT * 
    FROM `users` 
    WHERE `id`='$fid'";
$cLookup = mysqli_query($conn, $q);
$user = mysqli_fetch_assoc($cLookup);
$fun = $user['username'];
$fnn = $user['nickname'];
if ($fnn == "") {
    $fnn = $fun;
}

$fq = "SELECT * 
    FROM `friendship` 
    WHERE `user_id`='$uid' 
    AND `friend_id`='$fid'";
$cFriend = mysqli_query($conn, $fq);
$countf = mysqli_num_rows($cFriend);

if ($countf > 0) {
    $freindr = mysqli_fetch_assoc($cFriend);
    $cstatus = $freindr['status'];
    if ($cstatus == "sent") {
        $json = array(
            "responseCode"=>"0",
            "message"=>"You have already sent $fnn a friend request"
        );
        echo json_encode($json);
    } else {
        $rq = "UPDATE `friendship`
            SET `status`='friends',
            `since`= UNIX_TIMESTAMP()
            WHERE `user_id`='$uid'
            AND `friend_id`='$fid'";
        $rreslut = mysqli_query($conn, $rq);
        $rq = "UPDATE friendship
            SET `status`='friends',
            `since`= UNIX_TIMESTAMP()
            WHERE `user_id`='$fid'
            AND `friend_id`='$uid'";
        $rreslut = mysqli_query($conn, $rq);
        $nq = "INSERT INTO `notifications` (
                `to`,
                `from`,
                `title`,
                `content`,
                `created`,
                `type`
            )
            VALUES (
                '$fid',
                '$uid',
                'Friend request accepted',
                '$unn accepted your friend request.',
                UNIX_TIMESTAMP(),
                'friend_request'
            )";
        mysqli_query($conn, $nq);
        $json = array(
            "responseCode"=>"1",
            "message"=>"You are now friends with $fnn"
        );
        echo json_encode($json);
    }
} else {
    $json = array(
        "responseCode"=>"0",
        "message"=>"Something went wrong!"
    );
    echo json_encode($json);
}
?>