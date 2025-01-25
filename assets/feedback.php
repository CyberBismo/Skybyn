<?php include_once('conn.php');

// Store the feedback in the database
if (isset($_POST['feedback'])) {
    $feedback = $_POST['feedback'];
    $user = $_SESSION['user'];
    $date = date("Y-m-d H:i:s");
    $conn->query("INSERT INTO `feedback` (`user`,`date`,`content`) VALUES ('$user','$date','$feedback')");
}