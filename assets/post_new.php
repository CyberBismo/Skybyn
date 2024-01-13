<?php
include "./functions.php";

// Function to sanitize the user input using prepared statements.
function sanitizeInput($conn, $input) {
    if (is_array($input)) {
        foreach ($input as $key => $value) {
            $input[$key] = sanitizeInput($conn, $value);
        }
    } else {
        $input = trim($input); // No need to use htmlspecialchars for text
        $input = mysqli_real_escape_string($conn, $input);
    }
    return $input;
}

$public = isset($_POST['public']) ? $_POST['public'] : '';
$text = isset($_POST['text']) ? $_POST['text'] : '';
$image = isset($_FILES['files']) ? $_FILES['files'] : '';

$fixedText = fixEmojis($text, null);
$escapedText = sanitizeInput($conn, $fixedText);
$text = nl2br($escapedText);
$urls = extractUrls($text);

$proceed = false;

if (!empty($text)) {
    $proceed = true;
}
if (!empty($image['name'][0])) {
    $proceed = true;
}

if ($proceed == true) {
    // Use prepared statements to safely insert data into the database.
    $stmt = $conn->prepare("INSERT INTO `posts` (`user`, `content`, `public`, `created`,`urls`) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $uid, $text, $public, $now, $urls);
    $stmt->execute();
    $stmt->close();

    // Get the inserted post ID
    $postId = $conn->insert_id;

    if (!empty($image['name'][0])) {
        // Upload the file(s) only if there are files present
        $uploadDir = "../uploads/posts/$uid/$postId/"; // Directory to store the uploaded files

        // Make sure the directory exists or create it
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        if (is_array($image['tmp_name'])) {
            // If multiple files are uploaded, process each file
            foreach ($image['tmp_name'] as $index => $tmpName) {
                $fileName = $image['name'][$index];
                $filePath = $uploadDir . $fileName;

                // Move the uploaded file to the destination directory
                if (move_uploaded_file($tmpName, $filePath)) {
                    // Use prepared statements to safely insert file data into the database
                    $stmt = $conn->prepare("INSERT INTO `uploads` (`user`, `post`, `file_url`, `date`) VALUES (?, ?, ?, ?)");
                    $stmt->bind_param("ssss", $uid, $postId, $filePath, $now);
                    $stmt->execute();
                    $stmt->close();
                }
            }
        } else {
            // Only one file is uploaded
            $fileName = $image['name'];
            $filePath = $uploadDir . $fileName;

            // Move the uploaded file to the destination directory
            if (move_uploaded_file($image['tmp_name'], $filePath)) {
                // Use prepared statements to safely insert file data into the database
                $stmt = $conn->prepare("INSERT INTO `uploads` (`user`, `post`, `file_url`, `date`) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("ssss", $uid, $postId, $filePath, $now);
                $stmt->execute();
                $stmt->close();
            }
        }
    }

    echo "";
} else {
    echo "error"; // Indicate an error if the text is empty
}
?>
