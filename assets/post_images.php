<?php include "./functions.php";

$pid = $_POST['post'];

$getUploads = $conn->query("SELECT * FROM `uploads` WHERE `post`='$pid'");

$response = array(); // Initialize an array to store the file URLs

if ($getUploads->num_rows > 0) {
    while ($upload = $getUploads->fetch_assoc()) {
        $file = $upload['file_url'];
        $response[] = array('file_url' => $file); // Add each file URL to the array
    }
}

// Encode the array to JSON and set the proper header
header('Content-Type: application/json');
echo json_encode($response);
?>