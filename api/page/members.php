<?php include_once(__DIR__."/../../config.php");

$page = $_POST['pageID'];

$i = 0;

$PageMembers = "SELECT *
    FROM `page_members`
    WHERE `page`='$page'";
$PMResult = mysqli_query($conn, $PageMembers);
$countmPM = mysqli_num_rows($PMResult);
while ($pmRow = mysqli_fetch_assoc($PMResult)) {
    $mem_id = $pmRow['id'];
    $member = $pmRow['user'];
    $mem_rank = $pmRow['rank'];
    $mem_status = $pmRow['status'];

    if ($mem_rank == "0") {
        $lvl = '';
    } else 
    if ($mem_rank == "1") {
        $lvl = '<i class="fas fa-star"></i>';
    } else 
    if ($mem_rank == "2") {
        $lvl = '<i class="fas fa-shield-alt"></i>';
    } else 
    if ($mem_rank == "3") {
        $lvl = '<i class="fas fa-gavel"></i>';
    } else 
    if ($mem_rank == "4") {
        $lvl = '<i class="fas fa-crown"></i>';
    } else {
        $lvl = "";
    }

    $q = "SELECT *
        FROM `users`
        WHERE `id`='$member'";
    $member_result = mysqli_query($conn, $q);
    while ($member_data = mysqli_fetch_assoc($member_result)) {
        $pm_user_id = $member_data['id'];
        $pm_username = $member_data['username'];
        $pm_nickname = $member_data['nickname'];
        $pm_avatar = $member_data['avatar'];

        if ($pm_nickname == "") {
            $pm_nickname = $pm_username;
        }
        if ($pm_avatar == "") {
            $pm_avatar = "https://wesocial.space/sources/avatar.jpg";
        } else {
            $pm_avatar = "https://wesocial.space/sources/users/avatars/$member/$pm_avatar";
        }

        $members = array(
            "responseCode"=>"1",
            "member_id"=>"$pm_user_id",
            "user_id"=>"$member",
            "avatar"=>"$pm_avatar",
            "rank"=>"$mem_rank",
            "nickname"=>"$pm_nickname",
            "username"=>"$pm_username"
        );

        $data[$i] = $members;
        $i++;
    }
}
echo json_encode($data);
?>