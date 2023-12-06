<?php include_once(__DIR__.'/../../config.php');
$fid = $_POST['friendID'];
$uid = $_POST['userID'];
$action = $_POST['action'];

if ($action == "add") {
    $q = "SELECT * 
        FROM `users` 
        WHERE `id`='$fid' 
        AND `deactivated`='0' 
        AND `banned`='0' 
        AND `visible`='1'";
    $cLookup = mysqli_query($conn, $q);
    $count = mysqli_num_rows($cLookup);
    if ($count == 1) {
        $user = mysqli_fetch_assoc($cLookup);
        $fid = $user['id'];
        $uun = $user['username'];
        $unn = $user['nickname'];
        if ($unn == "") {
            $unn = $uun;
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
                AND `content`='sent you a friend request.'";
            $cfrnres = mysqli_query($conn, $cfrn);
            $countcfrn = mysqli_num_rows($cfrnres);
            if ($countcfrn == 0) {
                $nq = "INSERT INTO `notifications` (
                        `to`,
                        `from`,
                        `content`
                    )
                    VALUES (
                        '$fid',
                        '$uid',
                        'sent you a friend request.'
                    )";
                $nreslut = mysqli_query($conn, $nq);
            }
            $json = array(
                "responseCode"=>"1",
                "message"=>"You sent a friend request to $unn"
            );
            echo json_encode($json);
        } else {
            $freindr = mysqli_fetch_assoc($cFriend);
            $cstatus = $freindr['status'];
            if ($cstatus == "blocked") {
                $json = array(
                    "responseCode"=>"21",
                    "message"=>"You have been blocked by $unn"
                );
                echo json_encode($json);
            } else
            if ($cstatus == "unblock") {
                $json = array(
                    "responseCode"=>"22",
                    "message"=>"You have to unblock $unn first to add them as friend"
                );
                echo json_encode($json);
            } else
            if ($cstatus == "sent") {
                $json = array(
                    "responseCode"=>"23",
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
                    "responseCode"=>"2",
                    "message"=>"You are now friends with $unn"
                );
                echo json_encode($json);
            }
        }
    }
} else
if ($action == "remove") {
    $q = "SELECT * 
        FROM `users` 
        WHERE `id`='$fid' 
        AND `deactivated`='0' 
        AND `banned`='0' 
        AND `visible`='1'";
    $cLookup = mysqli_query($conn, $q);
    $count = mysqli_num_rows($cLookup);
    if ($count == 1) {
        $user = mysqli_fetch_assoc($cLookup);
        $fid = $user['id'];
        $uun = $user['username'];
        $unn = $user['nickname'];
        if ($unn == "") {
            $unn = $uun;
        }
        
        $fq = "SELECT * 
            FROM `friendship` 
            WHERE `user_id`='$uid' 
            AND `friend_id`='$fid'";
        $cFriend = mysqli_query($conn, $fq);
        $countf = mysqli_num_rows($cFriend);
        if ($countf == 0) {
            $json = array(
                "responseCode"=>"3",
                "message"=>"Cant remove $unn as friend"
            );
            echo json_encode($json);
        } else {
            $rq = "DELETE FROM `friendship`
                WHERE `user_id`='$uid'
                AND `friend_id`='$fid'";
            $rreslut = mysqli_query($conn, $rq);
            $rq = "DELETE FROM `friendship`
                WHERE `user_id`='$fid'
                AND `friend_id`='$uid'";
            $rreslut = mysqli_query($conn, $rq);
            $nq = "DELETE FROM `notifications`
                WHERE `to`='$fid'
                AND `from`='$uid'
                AND `content`='sent you a friend request.'";
            mysqli_query($conn, $nq);

            $json = array(
                "responseCode"=>"4",
                "message"=>"You are no longer friends with $unn"
            );
            echo json_encode($json);
        }
    }
} else
if ($action == "block") {
    $q = "SELECT * 
        FROM `users` 
        WHERE `id`='$fid' 
        AND `deactivated`='0' 
        AND `banned`='0' 
        AND `visible`='1'";
    $cLookup = mysqli_query($conn, $q);
    $count = mysqli_num_rows($cLookup);
    if ($count == 1) {
        $user = mysqli_fetch_assoc($cLookup);
        $fid = $user['id'];
        $uun = $user['username'];
        $unn = $user['nickname'];
        if ($unn == "") {
            $unn = $uun;
        }
        
        $fq = "SELECT * 
            FROM `friendship` 
            WHERE `user_id`='$uid' 
            AND `friend_id`='$fid'";
        $cFriend = mysqli_query($conn, $fq);
        $countf = mysqli_num_rows($cFriend);
        if ($countf == 0) {
            $json = array(
                "responseCode"=>"5",
                "message"=>"You cant block $unn"
            );
            echo json_encode($json);
        } else {
            $freindr = mysqli_fetch_assoc($cFriend);
            $cstatus = $freindr['status'];
            if ($cstatus == "sent") {
                $json = array(
                    "responseCode"=>"5",
                    "message"=>"Friend request sent"
                );
                echo json_encode($json);
            } else {
                $rq = "UPDATE `friendship`
                    SET `status`='unblock'
                    WHERE `user_id`='$uid'
                    AND `friend_id`='$fid'";
                $rreslut = mysqli_query($conn, $rq);
                $rq = "UPDATE `friendship`
                    SET `status`='blocked'
                    WHERE `user_id`='$fid'
                    AND `friend_id`='$uid'";
                $rreslut = mysqli_query($conn, $rq);

                $json = array(
                    "responseCode"=>"6",
                    "message"=>"You blocked $unn"
                );
                echo json_encode($json);
            }
        }
    }
} else
if ($action == "unblock") {
    $q = "SELECT * 
        FROM `users` 
        WHERE `id`='$fid' 
        AND `deactivated`='0' 
        AND `banned`='0' 
        AND `visible`='1'";
    $cLookup = mysqli_query($conn, $q);
    $count = mysqli_num_rows($cLookup);
    if ($count == 1) {
        $user = mysqli_fetch_assoc($cLookup);
        $fid = $user['id'];
        $uun = $user['username'];
        $unn = $user['nickname'];
        if ($unn == "") {
            $unn = $uun;
        }
        
        $fq = "SELECT * 
            FROM `friendship` 
            WHERE `user_id`='$uid' 
            AND `friend_id`='$fid'";
        $cFriend = mysqli_query($conn, $fq);
        $countf = mysqli_num_rows($cFriend);
        if ($countf == 0) {
            $json = array(
                "responseCode"=>"7",
                "message"=>"You cant unblock $unn"
            );
            echo json_encode($json);
        } else {
            $freindr = mysqli_fetch_assoc($cFriend);
            $cstatus = $freindr['status'];
            if ($cstatus == "sent") {
                $json = array(
                    "responseCode"=>"8",
                    "message"=>"You sent $unn a friend request"
                );
                echo json_encode($json);
            } else {
                $rq = "DELETE FROM `friendship`
                    WHERE `user_id`='$uid'
                    AND `friend_id`='$fid'";
                $rreslut = mysqli_query($conn, $rq);
                $rq = "DELETE FROM `friendship`
                    WHERE `user_id`='$fid'
                    AND `friend_id`='$uid'";
                $rreslut = mysqli_query($conn, $rq);

                $json = array(
                    "responseCode"=>"8",
                    "message"=>"You unblocked $unn"
                );
                echo json_encode($json);
            }
        }
    }
} else
if ($action == "accept") {
    $q = "SELECT * 
        FROM `users` 
        WHERE `id`='$fid' 
        AND `deactivated`='0' 
        AND `banned`='0' 
        AND `visible`='1'";
    $cLookup = mysqli_query($conn, $q);
    $count = mysqli_num_rows($cLookup);
    if ($count == 1) {
        $user = mysqli_fetch_assoc($cLookup);
        $fid = $user['uid'];
        $uun = $user['username'];
        $unn = $user['nickname'];
        if ($unn == "") {
            $unn = $uun;
        }
        
        $fq = "SELECT * 
            FROM `friendship` 
            WHERE `user_id`='$uid' 
            AND `friend_id`='$fid'";
        $cFriend = mysqli_query($conn, $fq);
        $countf = mysqli_num_rows($cFriend);
        if ($countf == 0) {
            $json = array(
                "responseCode"=>"9",
                "message"=>"You cant accept $unn"
            );
            echo json_encode($json);
        } else {
            $freindr = mysqli_fetch_assoc($cFriend);
            $cstatus = $freindr['status'];
            if ($cstatus == "sent") {
                $json = array(
                    "responseCode"=>"10",
                    "message"=>"You have already sent $unn a friend request"
                );
                echo json_encode($json);
            } else {
                $rq = "UPDATE `friendship`
                    SET `status`='friends',
                    `since`='$today'
                    WHERE `user_id`='$uid'
                    AND `friend_id`='$fid'";
                $rreslut = mysqli_query($conn, $rq);
                $rq = "UPDATE friendship
                    SET `status`='friends',
                    `since`='$today'
                    WHERE `user_id`='$fid'
                    AND `friend_id`='$uid'";
                $rreslut = mysqli_query($conn, $rq);
                $nq = "INSERT INTO `notifications` (
                        `to`,
                        `from`,
                        `content`
                    )
                    VALUES (
                        '$fid',
                        '$uid',
                        'accepted your friend request.'
                    )";
                mysqli_query($conn, $nq);

                $json = array(
                    "responseCode"=>"10",
                    "message"=>"You are now friends with $unn"
                );
                echo json_encode($json);
            }
        }
    }
}
?>