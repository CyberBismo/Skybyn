<?php include_once(__DIR__."/../../config.php");

$user = $_POST['userID'];

$pageuq = "SELECT *
    FROM `page_members`
    WHERE `user`='$user'";
$pures = mysqli_query($conn, $pageuq);
$pucount = mysqli_num_rows($pures);

$i = 0;

if ($pucount > 0) {
    while ($purow = mysqli_fetch_assoc($pures)) {
        $page_id = $purow['id'];
        
        $pageq = "SELECT *
            FROM `pages`
            WHERE `id`='$page_id'
            ORDER BY `created`
            DESC";
        $pres = mysqli_query($conn, $pageq);

        while ($prow = mysqli_fetch_assoc($pres)) {
            $page_id = $prow['id'];
            $page_name = $prow['name'];
            $page_desc = $prow['description'];
            $page_password = $prow['password'];
            $page_private = $prow['private'];
            $page_group = $prow['group'];
            $page_logo = $prow['logo'];
            $page_banner = $prow['banner'];

            if ($page_logo == "") {
                $page_logo = "bg.png";
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

            $pages = array(
                "page_id"=>"$page_id",
                "logo"=>"$page_logo",
                "name"=>"$page_name",
                "desc"=>"$page_desc",
                "lock"=>"$pgpw"
            );

            $data[$i] = $pages;
            $i++;
        }
    }
}
echo json_encode($data);
?>