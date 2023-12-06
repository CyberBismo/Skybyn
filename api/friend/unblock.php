<?php include_once(__DIR__.'/../../config.php');
$fid = $_POST['friendID'];
$uid = $_POST['userID'];

$q = "SELECT * 
    FROM `users` 
    WHERE `id`='$fid'";
$cLookup = mysqli_query($conn, $q);
$user = mysqli_fetch_assoc($cLookup);
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
        "responseCode"=>"0",
        "message"=>"You cant block $unn"
    );
    echo json_encode($json);
} else {
    $freindr = mysqli_fetch_assoc($cFriend);
    $cstatus = $freindr['status'];
    if ($cstatus == "sent") {
        $json = array(
            "responseCode"=>"0",
            "message"=>"Friend request already sent"
        );
        echo json_encode($json);
    } else {
        $rq = "UPDATE `friendship`
            SET `status`='friends'
            WHERE `user_id`='$uid'
            AND `friend_id`='$fid'";
        $rreslut = mysqli_query($conn, $rq);
        $rq = "UPDATE `friendship`
            SET `status`='friends'
            WHERE `user_id`='$fid'
            AND `friend_id`='$uid'";
        $rreslut = mysqli_query($conn, $rq);

        $json = array(
            "responseCode"=>"1",
            "message"=>"Unblocked $unn"
        );
        echo json_encode($json);
    }
}
?>