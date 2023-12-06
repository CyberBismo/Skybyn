<?php include_once(__DIR__.'/../../config.php');
$fid = $_POST['friendID'];
$uid = $_POST['userID'];

$q = "SELECT * 
    FROM `users` 
    WHERE `id`='$uid'";
$cLookup = mysqli_query($conn, $q);
$user = mysqli_fetch_assoc($cLookup);
$uun = $user['username'];
$unn = $user['nickname'];
if ($unn == "") {
    $unn = $uun;
}
$mq = "SELECT * 
    FROM `users` 
    WHERE `id`='$fid'";
$mcLookup = mysqli_query($conn, $mq);
$muser = mysqli_fetch_assoc($mcLookup);
$muun = $muser['username'];
$munn = $muser['nickname'];
if ($munn == "") {
    $munn = $muun;
}

$fq = "SELECT * 
    FROM `friendship` 
    WHERE `user_id`='$uid' 
    AND `friend_id`='$fid'";
$cFriend = mysqli_query($conn, $fq);
$countf = mysqli_num_rows($cFriend);
if ($countf == 0) {
    $sq = "INSERT INTO `friendship` (
            `user_id`,
            `friend_id`,
            `status`
        )
        VALUES (
            '$uid',
            '$fid',
            'sent'
        )";
    $sreslut = mysqli_query($conn, $sq);
    $rq = "INSERT INTO `friendship` (
            `user_id`,
            `friend_id`,
            `status`
        )
        VALUES (
            '$fid',
            '$uid',
            'received'
        )";
    $rreslut = mysqli_query($conn, $rq);
    $cfrn = "SELECT *
        FROM `notifications`
        WHERE `to`='$fid'
        AND `from`='$uid'
        AND `content`='$unn sent you a friend request.'";
    $cfrnres = mysqli_query($conn, $cfrn);
    $countcfrn = mysqli_num_rows($cfrnres);
    if ($countcfrn == 0) {
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
                'Incoming friend request',
                '$unn sent you a friend request.',
                UNIX_TIMESTAMP(),
                'friend_request'
            )";
        $nreslut = mysqli_query($conn, $nq);
    }
    $json = array(
        "responseCode"=>"1",
        "message"=>"You sent a friend request to $munn"
    );
    echo json_encode($json);
} else {
    $freindr = mysqli_fetch_assoc($cFriend);
    $cstatus = $freindr['status'];
    if ($cstatus == "blocked") {
        $json = array(
            "responseCode"=>"0",
            "message"=>"You have been blocked by $unn"
        );
        echo json_encode($json);
    } else
    if ($cstatus == "unblock") {
        $json = array(
            "responseCode"=>"0",
            "message"=>"You have to unblock $unn first to add them as friend"
        );
        echo json_encode($json);
    } else
    if ($cstatus == "sent") {
        $json = array(
            "responseCode"=>"0",
            "message"=>"You have already sent $unn a friend request"
        );
        echo json_encode($json);
    } else {
        $aq = "UPDATE `friendship`
            SET `status`='friends'
            WHERE `user_id`='$uid'
            AND `friend_id`='$fid'
            AND `since`='$today'";
        mysqli_query($conn, $aq);
        $bq = "UPDATE friendship
            SET `status`='friends'
            WHERE `user_id`='$fid'
            AND `friend_id`='$uid'
            AND `since`='$today'";
        mysqli_query($conn, $bq);
        
        $json = array(
            "responseCode"=>"1",
            "message"=>"You are now friends with $unn"
        );
        echo json_encode($json);
    }
}
?>