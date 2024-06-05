<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if all required fields are present
    if (isset($_FILES['file']) && isset($_POST['action']) && isset($_POST['meet_id'])) {
        $file = $_FILES['file'];
        $action = $_POST['action'];
        $id = $_POST['id'];

        if ($action === 'video') {
            $path = '/uploads/videos/'.$id;
        } else
        if ($action === 'avatar') {
            $path = '/uploads/avatars/'.$id;
        } else
        if ($action === 'car') {
            $path = '/uploads/cars/'.$id;
        }

        // Check if file is uploaded successfully
        if ($file['error'] === UPLOAD_ERR_OK) {
            $fileName = $file['name'];
            $fileTmpPath = $file['tmp_name'];
            $fileSize = $file['size'];
            $fileType = $file['type'];

            // Move the uploaded file to a desired location
            $destination = $path . $fileName;
            move_uploaded_file($fileTmpPath, $destination);

            // Process the form data based on the action and meet_id
            // Add your code here to handle the form data accordingly

            echo 'File uploaded successfully!';
        } else {
            echo 'Error uploading file. Please try again.';
        }
    } else {
        echo 'Missing required fields. Please make sure all fields are filled.';
    }
}
?>