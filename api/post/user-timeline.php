<?php include_once(__DIR__.'/../../config.php');
$id = $_POST['userID'];

$postq = "SELECT *
    FROM `posts`
    WHERE `user`='$id'
    ORDER BY `created` DESC";
$presult = mysqli_query($conn, $postq);
$pcount = mysqli_num_rows($presult);

$i = 0;

while ($prows = mysqli_fetch_assoc($presult)) {

    $pid = $prows['id'];
    $pcontent = $prows['content'];
    $puser = $prows['user'];
    $pdate = $prows['created'];
    $ppage = $prows['page'];
    $pprofile = $prows['profile'];
    
    $breaks = array("<br />","<br>","<br/>");  
    $pcontent = str_ireplace($breaks, "\r\n", $pcontent);

    $pcontent = htmlspecialchars_decode(utf8_decode($pcontent), ENT_QUOTES);
    
    $commentq = "SELECT *
        FROM `comments`
        WHERE `post_id`='$pid'";
    $cresult = mysqli_query($conn, $commentq);
    $comments = mysqli_num_rows($cresult);

    if (!empty($pprofile)) {
        if ($pprofile == $id) {
            $fromProfile = "my profile";
        } else {
            $q = "SELECT *
                FROM `users`
                WHERE `id`='$pprofile'";
            $gpd = mysqli_query($conn, $q);
            $pdr = mysqli_fetch_assoc($gpd);
            $pduser = $pdr['username'];
            $pdnick = $pdr['nickname'];
            if ($pdnick == "") {
                $pdnick = $pduser;
            }
            $fromProfile = "$pprofile profile";
        }
    } else {
        $fromProfile = "false";
    }

    $fileq = "SELECT *
        FROM `uploads`
        WHERE `post`='$pid'";
    $fileres = mysqli_query($conn, $fileq);
    $countfiles = mysqli_num_rows($fileres);
    if ($countfiles > 0) {
        $filerow = mysqli_fetch_assoc($fileres);
        $fileName = $filerow['fileName'];
        $fileLoc = $filerow['location'];

        $file = $fileLoc.$fileName;
    } else {
        $file = null;
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
        if ($ppage == null) {

            $lq = "SELECT *
                FROM `likes`
                WHERE `pid`='$pid'";
            $likes = mysqli_query($conn, $lq);
            $lcount = mysqli_num_rows($likes);
            
            $ulq = "SELECT *
                FROM `likes`
                WHERE `pid`='$pid'
                AND `uid`='$id'";
            $ulikes = mysqli_query($conn, $ulq);
            $ulcount = mysqli_num_rows($ulikes);
            if ($ulcount == 1) {
                $ilike = "1";
            } else {
                $ilike = "0";
            }

            ## POST API JSON ##
            $posts = array(
                "postID" => "$pid",
                "userID" => "$puser",
                "username" => "$pusername",
                "avatar" => "$pavatar",
                "nickname" => "$pnickname",
                "date" => "$pdate",
                "postURL" => "/post?p=$pid",
                "fromProfile" => "$fromProfile",
                "content" => $pcontent,
                "comments_count" => "$comments",
                "likes" => "$lcount",
                "ilike" => "$ilike",
                "file" => "$file"
            );

            $data[$i] = $posts;
            $i++;
        }
    }
}
echo json_encode($data);
?>