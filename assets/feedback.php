<?php include_once "./functions.php";

// Store the feedback in the database
if (isset($_POST['feedback'])) {
    $feedback = $conn->real_escape_string($_POST['feedback']);
    $url = $conn->real_escape_string($_POST['page']);
    $user = $_SESSION['user'];
    $date = time();
    $conn->query("INSERT INTO `feedback` (`user`,`date`,`content`,`url`) VALUES ('$user','$date','$feedback','$url')");
} else {
    if (isset($_POST['delete'])) {
        $id = $_POST['delete'];
        $checkFeedback = $conn->query("SELECT * FROM `feedback` WHERE `id`='$id'");
        if ($checkFeedback->num_rows == 1) {
            $feedbackData = $checkFeedback->fetch_assoc();
            $feedbackUser = $feedbackData['user'];
            if ($feedbackUser == $_SESSION['user'] || $rank > 5) {
                $conn->query("DELETE FROM `feedback` WHERE `id`='$id'");
                $json = array("status" => "success");
            } else {
                $json = array("status" => "error", "message" => "You are not allowed to delete this feedback");
            }
        } else {
            $json = array("status" => "error", "message" => "Feedback not found");
        }
    } else {
        if (isset($_POST['solve'])) {
            $id = $_POST['solve'];
            $checkFeedback = $conn->query("SELECT * FROM `feedback` WHERE `id`='$id'");
            if ($checkFeedback->num_rows == 1) {
                $feedbackData = $checkFeedback->fetch_assoc();
                $feedbackUser = $feedbackData['user'];
                if ($rank > 5) {
                    $conn->query("UPDATE `feedback` SET `solved`='1' WHERE `id`='$id'");
                    $json = array("status" => "success");
                } else {
                    $json = array("status" => "error", "message" => "You are not allowed to mark this feedback as solved");
                }
            } else {
                $json = array("status" => "error", "message" => "Feedback not found");
            }
        } else {
            $json = array("status" => "error", "message" => "Invalid request");
        }
    }
}