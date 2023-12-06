<?php include_once(__DIR__."/../../config.php");

$user = $_POST['userID'];

$pageq = "SELECT *
    FROM `pages`
    ORDER BY `created`
    DESC";
$pres = mysqli_query($conn, $pageq);
$pcount = mysqli_num_rows($pres);

$i = 0;

if ($pcount > 0) {
    while ($prow = mysqli_fetch_assoc($pres)) {
        $page_id = $prow['id'];
        $page_name = $prow['name'];
        $page_desc = $prow['description'];
        $page_password = $prow['password'];
        $page_private = $prow['private'];
        $page_group = $prow['group'];
        $page_logo = $prow['logo'];
        $page_banner = $prow['banner'];
    
        $breaks = array("<br />","<br>","<br/>");
        $page_desc = str_ireplace($breaks, "\r\n", $page_desc);
    
        $page_name = htmlspecialchars_decode(utf8_decode($page_name), ENT_QUOTES);
        $page_desc = htmlspecialchars_decode(utf8_decode($page_desc), ENT_QUOTES);

        if ($page_logo == "") {
            $page_logo = "https://wesocial.space/bg.png";
        } else {
            $page_logo = "https://wesocial.space/sources/page/$page_id/$page_logo";
        }
        if ($page_banner == "") {
            $page_banner = "https://wesocial.space/banner.jpg";
        } else {
            $page_banner = "https://wesocial.space/sources/page/$page_id/$page_banner";
        }

        if ($page_password != null) {
            $pgpw = "true";
            $page_private = "";
            $page_group_lock = "";
            $page_unlocked = "";
        } else {
            $pgpw = "";
            if ($page_private != null) {
                $page_private = "true";
                $page_group_lock = "";
                $page_unlocked = "";
            } else 
            if ($page_group != null) {
                $page_private = "";
                $page_group_lock = "true";
                $page_unlocked = "";
            } else {
                $page_private = "";
                $page_group_lock = "";
                $page_unlocked = "true";
            }
        }
    
        $minfo = "SELECT *
            FROM `page_members`
            WHERE `user`='$user'
            AND `page`='$page_id'";
        $mres = mysqli_query($conn, $minfo);
        $mem = mysqli_num_rows($mres);
    
        if ($mem > 0) {
            $member = "true";
        } else {
            $member = "false";
        }

        $mcinfo = "SELECT *
            FROM `page_members`
            WHERE `page`='$page_id'";
        $mcres = mysqli_query($conn, $mcinfo);
        $memc = mysqli_num_rows($mcres);
        
        $pages = array(
            "page_id"=>"$page_id",
            "logo"=>"$page_logo",
            "name"=>"$page_name",
            "desc"=>"$page_desc",
            "lock"=>"$pgpw",
            "member"=>"$member",
            "members"=>"$memc"
        );

        $data[$i] = $pages;
        $i++;
    }
    echo json_encode($data);
} else {
    $pages = array(
        "responseCode"=>"0",
        "message"=>"No pages found"
    );
    
    echo json_encode($pages);
}
?>