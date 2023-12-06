<?php include_once(__DIR__."/../../config.php");
$id = $_POST['userID'];

$notiq = "SELECT *
    FROM `notifications`
    WHERE `to`='$id'
    ORDER BY `created`
    ASC";
$nresult = mysqli_query($conn, $notiq);
$ncount = mysqli_num_rows($nresult);

$i = 0;

if ($ncount > 0) {
    while ($nrows = mysqli_fetch_assoc($nresult)) {
        $nid = $nrows['id'];
        $nfrom = $nrows['from'];
        $ntitle = $nrows['title'];
        $ncontent = $nrows['content'];
        $ndate = $nrows['created'];
        $nseen = $nrows['seen'];
        $ntype = $nrows['type'];
    
        $breaks = array("<br />","<br>","<br/>");
        $ncontent = str_ireplace($breaks, "\r\n", $ncontent);

        $ncontent = htmlspecialchars_decode(utf8_decode($ncontent), ENT_QUOTES);
        
        $userq = "SELECT *
            FROM `users`
            WHERE `id`='$nfrom'";
        $userres = mysqli_query($conn, $userq);

        while ($urow = mysqli_fetch_assoc($userres)) {
            $cusername = $urow['username'];
            $cnickname = $urow['nickname'];
            $cavatar = $urow['avatar'];

            if ($cnickname == "") {
                $cnickname = $cusername;
            }
            if ($cavatar == "") {
                $cavatar = "https://wesocial.space/sources/avatar.jpg";
            } else {
                $cavatar = "https://wesocial.space/sources/users/avatars/$nfrom/$cavatar";
            }
            
            $notis = array(
                "notiID" => "$nid",
                "username" => "$cusername",
                "nickname" => "$cnickname",
                "avatar" => "$cavatar",
                "date" => "$ndate",
                "title" => "$ntitle",
                "content" => "$ncontent",
                "read" => "$nseen",
                "type" => "$ntype"
            );

            $data[$i] = $notis;
            $i++;
        }
    }
    echo json_encode($data);
} else {
    echo "You've got no new notifications.";
}
?>