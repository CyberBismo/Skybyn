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

$countf = mysqli_num_rows($cFriend);
$fq = "SELECT * 
    FROM `friendship` 
    WHERE `user_id`='$uid' 
    AND `friend_id`='$fid'";
$cFriend = mysqli_query($conn, $fq);
$countf = mysqli_num_rows($cFriend);
if ($countf == 0) {
    $json = array(
        "responseCode"=>"0",
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
        "responseCode"=>"1",
        "message"=>"You are no longer friends with $unn"
    );
    echo json_encode($json);
}
?>