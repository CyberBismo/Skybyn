<?php include "functions.php";

$friend = $_POST['friend'];
$action = $_POST['action'];

$checkStatus = $conn->query("SELECT * FROM `friendship` WHERE `user_id`='$uid' AND `friend_id`='$friend'");
$friendshipStatus = $checkStatus->fetch_assoc();

$statusS = "sent";
$statusR = "received";
$statusA = "accepted";
$statusD = "declined";
$statusI = "ignored";
$statusB = "block";
$statusUnf = "unfriend";
$statusUnb = "unblock";

if ($checkStatus->num_rows == 0) { // IF NO RECORDS
    if ($action == "send") {
        $conn->query("INSERT INTO `friendship` (`user_id`, `friend_id`, `status`, `since`) VALUES ('$uid', '$friend', '$statusS', '$now')");
        $conn->query("INSERT INTO `friendship` (`user_id`, `friend_id`, `status`, `since`) VALUES ('$friend', '$uid', '$statusR', '$now')");
        $conn->query("INSERT INTO `notifications` (`to`,`from`,`date`,`type`) VALUES ('$friend','$uid','$now','friend_request')");
        echo json_encode(["to"=>"$friend","from"=>"$uid","message" => "Friend request sent."]);
    } else {
        echo json_encode(["error" => "Error sending"]);
    }
} else {
    $status = $friendshipStatus['status'];

    if ($action == "accept") {
        if ($status == $statusR) {
            $conn->query("UPDATE `friendship` SET `status`='$statusA' WHERE `user_id`='$friend' AND `friend_id`='$uid'");
            $conn->query("UPDATE `friendship` SET `status`='$statusA' WHERE `user_id`='$uid' AND `friend_id`='$friend'");
            $conn->query("INSERT INTO `notifications` (`to`,`from`,`date`,`type`) VALUES ('$friend','$uid','$now','friend_accepted')");
            echo json_encode(["to"=>"$friend","from"=>"$uid","message" => "Friend request accepted."]);
        } else {
            echo json_encode(["error" => "Error accepting"]);
        }
    } else
    if ($action == "ignore") {
        if ($status == $statusR) {
            $conn->query("UPDATE `friendship` SET `status`='sent' WHERE `user_id`='$friend' AND `friend_id`='$uid'");
            $conn->query("DELETE FROM `friendship` WHERE `user_id`='$uid' AND `friend_id`='$friend'");
            echo json_encode(["to"=>"$friend","from"=>"$uid","message" => "Friend request ignored."]);
        } else {
            echo json_encode(["error" => "Error ignoring"]);
        }
    } else
    if ($action == "cancel") {
        if ($status == $statusS) {
            $conn->query("DELETE FROM `friendship` WHERE (`user_id`='$uid' AND `friend_id`='$friend') OR (`user_id`='$friend' AND `friend_id`='$uid')");
            echo json_encode(["to"=>"$friend","from"=>"$uid","message" => "Friend request canceled."]);
        } else {
            echo json_encode(["error" => "Error canceling"]);
        }
    } else
    if ($action == "unfriend") {
        if ($status == $statusA) {
            $conn->query("DELETE FROM `friendship` WHERE (`user_id`='$uid' AND `friend_id`='$friend') OR (`user_id`='$friend' AND `friend_id`='$uid')");
            echo json_encode(["to"=>"$friend","from"=>"$uid","message" => "Unfriended."]);
        } else {
            echo json_encode(["error" => "Error unfriending"]);
        }
    } else
    if ($action == "block") {
        if ($status == $statusA) {
            $conn->query("UPDATE `friendship` SET `status`='$statusB' WHERE (`user_id`='$uid' AND `friend_id`='$friend') OR (`user_id`='$friend' AND `friend_id`='$uid')");
            echo json_encode(["to"=>"$friend","from"=>"$uid","message" => "Blocked."]);
        } else {
            echo json_encode(["error" => "Error blocking"]);
        }
    } else
    if ($action == "unblock") {
        if ($status == $statusB) {
            $conn->query("UPDATE `friendship` SET `status`='$statusUnb' WHERE (`user_id`='$uid' AND `friend_id`='$friend') OR (`user_id`='$friend' AND `friend_id`='$uid')");
            echo json_encode(["to"=>"$friend","from"=>"$uid","message" => "Unblocked."]);
        } else {
            echo json_encode(["error" => "Error unblocking"]);
        }
    } else {
        echo json_encode(["error" => "Invalid action"]);
    }
}
?>
