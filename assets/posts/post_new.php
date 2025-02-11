<?php include "../functions.php";

if (!empty($_POST['text'])) {
    //$public = $_POST['public'];
    $text = encrypt($_POST['text']);

    // Prepare the SQL statement to prevent SQL injection and handle special characters
    $stmt = $conn->prepare("INSERT INTO `posts` (`user`, `content`, `created`) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $uid, $text, $now);
    $stmt->execute();
    $post_id = $stmt->insert_id;
    $stmt->close();

    if (isset($_FILES['image'])) {
        $files = $_FILES['image'];
        $file_count = count($files['name']);
        
        $upload_dir = "../uploads/posts/$uid/$post_id/";
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        $success_count = 0;
        for ($i = 0; $i < $file_count; $i++) {
            $file_name = $files['name'][$i];
            $file_tmp = $files['tmp_name'][$i];
            $file_path = $upload_dir . $file_name;
            
            if (move_uploaded_file($file_tmp, $file_path)) {
                $stmt = $conn->prepare("INSERT INTO `uploads` (`user`, `post`, `file_url`, `date`) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("iiss", $uid, $post_id, $file_path, $now);
                $stmt->execute();
                $stmt->close();
                $success_count++;
            }
        }
    } else {
        $data = [
            'responseCode' => 1,
            'message' => "Post shared successfully.",
            'post_id' => $post_id
        ];
    }
} else {
    $data = [
        'responseCode' => 0,
        'message' => "Please enter some text"
    ];
}

if (!empty($data)) {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data);
}
?>