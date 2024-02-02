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
        echo "Friend request sent.";
    } else {
        echo "Error sending";
    }
} else {
    $status = $friendshipStatus['status'];

    if ($action == "accept") {
        if ($status == $statusR) {
            $conn->query("UPDATE `friendship` SET `status`='$statusA' WHERE `user_id`='$friend' AND `friend_id`='$uid'");
            $conn->query("UPDATE `friendship` SET `status`='$statusA' WHERE `user_id`='$uid' AND `friend_id`='$friend'");
            $conn->query("INSERT INTO `notifications` (`to`,`from`,`date`,`type`) VALUES ('$friend','$uid','$now','friend_accepted')");
            echo "Friend request accepted.";
        } else {
            echo "Error accepting";
        }
    } elseif ($action == "ignore") {
        if ($status == $statusR) {
            $conn->query("UPDATE `friendship` SET `status`='sent' WHERE `user_id`='$friend' AND `friend_id`='$uid'");
            $conn->query("DELETE FROM `friendship` WHERE `user_id`='$uid' AND `friend_id`='$friend'");
            echo "Friend request ignored.";
        } else {
            echo "Error ignoring";
        }
    } elseif ($action == "cancel") {
        if ($status == $statusS) {
            $conn->query("DELETE FROM `friendship` WHERE (`user_id`='$uid' AND `friend_id`='$friend') OR (`user_id`='$friend' AND `friend_id`='$uid')");
            echo "Friend request canceled.";
        } else {
            echo "Error canceling";
        }
    } elseif ($action == "unfriend") {
        if ($status == $statusA) {
            $conn->query("DELETE FROM `friendship` WHERE (`user_id`='$uid' AND `friend_id`='$friend') OR (`user_id`='$friend' AND `friend_id`='$uid')");
            echo "Unfriended.";
        } else {
            echo "Error unfriending";
        }
    } elseif ($action == "block") {
        if ($status == $statusA) {
            $conn->query("UPDATE `friendship` SET `status`='$statusB' WHERE (`user_id`='$uid' AND `friend_id`='$friend') OR (`user_id`='$friend' AND `friend_id`='$uid')");
            echo "Blocked.";
        } else {
            echo "Error blocking";
        }
    } elseif ($action == "unblock") {
        if ($status == $statusB) {
            $conn->query("UPDATE `friendship` SET `status`='$statusUnb' WHERE (`user_id`='$uid' AND `friend_id`='$friend') OR (`user_id`='$friend' AND `friend_id`='$uid')");
            echo "Unblocked.";
        } else {
            echo "Error unblocking";
        }
    } else {
        echo "Invalid action.";
    }
}
?>
