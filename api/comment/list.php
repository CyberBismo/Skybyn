<?php include_once(__DIR__."/../../config.php");
$id = $_POST['userID'];
$pid = $_POST['postID'];

$commentq = "SELECT *
    FROM `comments`
    WHERE `post_id`='$pid'
    ORDER BY `created`
    ASC";
$cresult = mysqli_query($conn, $commentq);

$i = 0;

while ($crows = mysqli_fetch_assoc($cresult)) {
    $cid = $crows['id'];
    $cpid = $crows['post_id'];
    $ccontent = htmlspecialchars($crows['content'], ENT_QUOTES);
    $cuser = $crows['user'];
    $cdate = $crows['created'];
    
    $breaks = array("<br />","<br>","<br/>");  
    $ccontent = str_ireplace($breaks, "\r\n", $ccontent);
    
    $userq = "SELECT *
        FROM `users`
        WHERE `id`='$cuser'";
    $userres = mysqli_query($conn, $userq);
    while ($curow = mysqli_fetch_assoc($userres)) {
        $cusername = $curow['username'];
        $cnickname = $curow['nickname'];
        $cavatar = $curow['avatar'];
        if ($cnickname == "") {
            $cnickname = $cusername;
        }
        if ($cavatar == "") {
            $cavatar = "https://wesocial.space/sources/avatar.jpg";
        } else {
            $cavatar = "https://wesocial.space/sources/users/avatars/$cuser/$cavatar";
        }
        # $cid : Comment ID.
        # $cusername : Comment users username.
        # $cavatar : Comment users avatar.
        # $cnickname : Comment users nickname.
        # $cdate : Comment date.
        # $ccontent : Comment content/text.
        
        $lq = "SELECT *
            FROM `likes`
            WHERE `cid`='$cid'";
        $likes = mysqli_query($conn, $lq);
        $lcount = mysqli_num_rows($likes);

        $ulq = "SELECT *
            FROM `likes`
            WHERE `cid`='$cid'
            AND `uid`='$id'";
        $ulikes = mysqli_query($conn, $ulq);
        $ulcount = mysqli_num_rows($ulikes);
        if ($ulcount == 1) {
            $ilike = "1";
        } else {
            $ilike = "0";
        }
        
        ## COMMENTS API JSON ##
        $posts = array(
            "commentID" => "$cid",
            "postID" => "$pid",
            "userID" => "$cuser",
            "username" => "$cusername",
            "avatar" => "$cavatar",
            "nickname" => "$cnickname",
            "date" => "$cdate",
            "content" => utf8_decode($ccontent),
            "likes" => "$lcount",
            "ilike" => "$ilike"
        );
        $data[$i] = $posts;
        $i++;
    }
}
echo json_encode($data);
?>