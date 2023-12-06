<?php include_once(__DIR__."/../../config.php");

$user = $_POST['userID'];
$page = $_POST['pageID'];

$postq = "SELECT *
FROM `posts`
WHERE `page`='$page'
ORDER BY `created`
DESC";
$presult = mysqli_query($conn, $postq);
$pcount = mysqli_num_rows($presult);

$i = 0;

while ($prows = mysqli_fetch_assoc($presult)) {
    $pid = $prows['id'];
    $pcontent = utf8_decode($prows['content']);
    $puser = $prows['user'];
    $pdate = $prows['created'];

    $pcontent = make_links_clickable($pcontent);
    $pcontent = make_bold($pcontent);
    $pcontent = make_italic($pcontent);

    $pdate = date("d.m.Y H:i:s", $pdate);

    $gfq = "SELECT *
        FROM `uploads`
        WHERE `page`='$page'
        AND `post`='$pid'";
    $getfile = mysqli_query($conn, $gfq);
    $filecount = mysqli_num_rows($getfile);
    if ($filecount > 0) {
        $filerow = mysqli_fetch_assoc($getfile);
        $filename = $filerow['fileName'];
        $fileloc = $filerow['location'];

        $pp_file = $fileloc.$filename;

        if (isset($pp_file)) {
            $fileParts = explode('.',$pp_file);
            $fileType = end($fileParts);
        
            if ($fileType == "txt") {
                $file_img = "txt.png";
                $file_txt = $pp_file;
            } else
            if ($fileType == "zip") {
                $file_img = "zip.png";
                $file_dl = $pp_file;
            } else
            if ($fileType == "jpg" || "jpeg" || "gif" || "png") {
                $file_img = $pp_file;
            } else {
                $file_img = "unknown.png";
            }
        }
    }

    $userq = "SELECT *
        FROM `users`
        WHERE `id`='$puser'";
    $userres = mysqli_query($conn, $userq);
    while ($urow = mysqli_fetch_assoc($userres)) {
        $pusername = $urow['username'];
        $pnickname = $urow['nickname'];
        $pavatar = $urow['avatar'];

        if ($pnickname == "") {
            $pnickname = $pusername;
        }
        if ($pavatar == "") {
            $pavatar = "https://wesocial.space/sources/avatar.jpg";
        } else {
            $pavatar = "https://wesocial.space/sources/users/avatars/$puser/$pavatar";
        }

        $q = "SELECT *
            FROM `page_members`
            WHERE `page`='$page'
            AND `user`='$id'";
        $result = mysqli_query($conn, $q);
        $count = mysqli_num_rows($result);
        if ($count > 0) {
            $row = mysqli_fetch_assoc($result);
            $page_lvl = $row['rank'];
        }
        
        if (isset($pp_file)) {
            if (isset($file_txt)) {
                $file_txt;
            } else
            if (isset($file_dl)) {
                $file_img;
            } else {
                $file_img;
            }
        }
        
        $posts = array(
            "responseCode"=>"1",
            "date"=>"$pdate",
            "content"=>"$pcontent",
            "username"=>"$pnickname",
            "avatar"=>"$pavatar",
            "content"=>"$pcontent",
            "file"=>"$file_img",
            "fileTxt"=>"$file_txt",
            "fileDL"=>"$file_dl"
        );

        $data[$i] = $posts;
        $i++;
    }
}
echo json_encode($data);
?>