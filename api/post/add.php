<?php include_once(__DIR__."/../../config.php");
$id = $_POST['userID'];
$page = $_POST['postID'];

$pid = rand();
$content = nl2br(htmlspecialchars(utf8_encode($_POST['content']), ENT_QUOTES));
$file = basename($_FILES['file']['name']);

if (!empty($file)) {
    $target_dir = "sources/posts/$pid/";
    if (!file_exists($target_dir)) {
        mkdir($target_dir);
    }

    if (file_exists($target_dir.$file)) {
        $filePart = explode(".", strrev($file), 2);
        $fileType = strrev($filePart['0']);
        $fileName = strrev($filePart['1']);
        $file = $fileName."0.".$fileType;
    }
    $upload = $target_dir.$file;

    $size = $_FILES["file"]["size"];
    $uploadq = "INSERT INTO `uploads` (
            `page`,
            `fileName`,
            `location`,
            `size`,
            `date`,
            `user`,
            `post`
        )
        VALUES (
            '$page',
            '$file',
            '$target_dir',
            '$size',
            UNIX_TIMESTAMP(),
            '$id',
            '$pid'
        )";
    mysqli_query($conn, $uploadq);
    move_uploaded_file($_FILES["file"]["tmp_name"], $upload);
}

$q = "INSERT INTO `posts` (
        `id`,
        `content`,
        `user`,
        `created`
    )
    VALUES (
        '$pid',
        '$content',
        '$id',
        UNIX_TIMESTAMP()
    )";
$post = mysqli_query($conn, $q);
if ($post) {
    $json = array(
        "responseCode"=>"1",
        "message"=>"Posted",
        "postID"=>"$pid"
    );
    echo json_encode($json);
} else {
    $json = array("responseCode"=>"0","message"=>"Something went wrong adding post.");
    echo json_encode($json);
}