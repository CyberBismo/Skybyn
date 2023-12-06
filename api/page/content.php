<?php include_once(__DIR__."/../../config.php");

$page = $_POST['pageID'];
$user = $_POST['userID'];

$pagesq = "SELECT *
    FROM `pages`
    WHERE `id`='$page'";
$pagesresults = mysqli_query($conn, $pagesq);
$pagecount = mysqli_num_rows($pagesresults);
if ($pagecount > 0) {
    $prow = mysqli_fetch_assoc($pagesresults);
    $page_id = $prow['id'];
    $page_name = $prow['name'];
    $page_desc = $prow['description'];
    $page_private = $prow['private'];
    $page_password = $prow['password'];
    $page_group = $prow['group'];
    $page_banner = $prow['banner'];
    $page_logo = $prow['logo'];
    $page_logo_bg = $prow['logo_bg'];
    $page_logo_border = $prow['logo_border'];
    $page_background = $prow['background'];
    
    $breaks = array("<br />","<br>","<br/>");
    $page_desc = str_ireplace($breaks, "\r\n", $page_desc);

    $page_name = htmlspecialchars_decode(utf8_decode($page_name), ENT_QUOTES);
    $page_desc = htmlspecialchars_decode(utf8_decode($page_desc), ENT_QUOTES);

    if ($page_banner == "") {
        $page_banner = "banner.jpg";
    }
    if ($page_logo == "") {
        $page_logo = "bg.png";
    }
    if ($page_logo_bg != "") {
        $page_logo_bg = $page_logo_bg;
        $logo_bg = "";
    } else {
        $page_logo_bg = "";
        $logo_bg = "checked";
    }
    if ($page_logo_border == "1") {
        $page_logo_border = "border: 5px solid rgba(0,0,0,.3);";
        $logo_border = "checked";
    } else {
        $logo_border = "";
    }
    if ($page_background == "") {
        $page_background = $darkmode;
    }

    if ($page_password != null) {
        if (isset($_SESSION["pa_$i"])) {
            $passcode = false;
        } else {
            $passcode = true;
        }
    } else {
        $passcode = false;
        if ($page_private == "1") {
            $page_private = true;
            $page_group_lock = "";
            $page_unlocked = "";
        } else 
        if ($page_private == "2") {
            $page_private = "";
            $gminfo = "SELECT *
                FROM `group_members`
                WHERE `user`='$user'
                AND `group`='$page_group'";
            $gmres = mysqli_query($conn, $gminfo);
            $gmem = mysqli_num_rows($gmres);
            if ($gmem == "1") {
                $page_group_lock = false;
            } else {
                $page_group_lock = true;
            }
            $page_unlocked = "";
        } else {
            $page_private = "";
            $page_group_lock = "";
            $page_unlocked = true;
        }
    }
    
    $minfo = "SELECT *
        FROM `page_members`
        WHERE `user`='$user'
        AND `page`='$page'";
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

    $myPageMembership = "SELECT *
        FROM `page_members`
        WHERE `page`='$page_id'
        AND `user`='$id'";
    $MyPMResult = mysqli_query($conn, $myPageMembership);
    $countmPM = mysqli_num_rows($MyPMResult);
    if ($countmPM == 1) {
        $pmRow = mysqli_fetch_assoc($MyPMResult);
        $page_rank = $pmRow['rank'];
        $page_status = $pmRow['status'];

        if ($page_rank > 2) {
            $moderation = true;
        } else {
            $moderation = false;
        }
    } else {
        $moderation = false;
    }

    $json = array(
        "responseCode"=>"1",
        "name"=>"$page_name",
        "desc"=>"$page_desc",
        "logo"=>"$page_logo",
        "banner"=>"$page_banner",
        "logo_bg"=>"$page_logo_bg",
        "logo_border"=>"$page_logo_border",
        "private"=>"$page_private",
        "password"=>"$page_password",
        "group"=>"$page_group",
        "member"=>"$member",
        "members"=>"$memc"
    );

    echo json_encode($json);
}
?>