<?php include_once(__DIR__."/../../config.php");

$id = rand();
$rid = rand();
$user = $_POST['userID'];
$name = htmlspecialchars(utf8_encode($_POST['name']), ENT_QUOTES);
$desc = nl2br(htmlspecialchars(utf8_encode($_POST['desc']), ENT_QUOTES));
$pw = $_POST['password'];
$lock = $_POST['special'];
$created = $today;
$target_dir = "https://wesocial.space/sources/pages/$id/banners/";
if (!file_exists($target_dir)) {
    mkdir($target_dir);
}
$target_dir = "https://wesocial.space/sources/pages/$id/logos/";
if (!file_exists($target_dir)) {
    mkdir($target_dir);
}
$q = "INSERT INTO `pages` (
        `id`,
        `name`,
        `description`,
        `author`,
        `created`,
        `private`,
        `password`
    )
    VALUES (
        '$id',
        '$name',
        '$desc',
        '$user',
        UNIX_TIMESTAMP(),
        '$lock',
        '$pw'
    )";
$create = mysqli_query($conn, $q);
if ($create) {
    $q = "INSERT INTO `page_members` (
            `id`,
            `page`,
            `user`,
            `rank`
        )
        VALUES (
            '$rid',
            '$id',
            '$user',
            '4'
        )";
    mysqli_query($conn, $q);
    
    $json = array("responseCode"=>"1","message"=>"Successfully created page","pageID"=>"$id");
    echo json_encode($json);
}
?>