<?php include_once "./functions.php";

$getNotifications = $conn->query("SELECT * FROM `notifications` WHERE `to`='$uid' ORDER BY `date` DESC");
if ($getNotifications->num_rows > 0) {
    while ($notiData = $getNotifications->fetch_assoc()) {
        $noti_id = $notiData['id'];
        $noti_from = $notiData['from'];
        $noti_content = $notiData['content'];
        $noti_date = $notiData['date'];
        $noti_profile = $notiData['profile'];
        $noti_post = $notiData['post'];
        $noti_read = $notiData['read'];
        $noti_type = $notiData['type'];

        $noti_username = getUser("id",$noti_from,"username");

        if ($noti_type == "friend_request") {
            $noti_title = "$noti_username send you a friend request";
            $noti_text = "<a href=\"./profile?u=$noti_username\">View their profile</a>";
        } else
        if ($noti_type == "friend_accepted") {
            $noti_title = "Your friend $noti_username is here";
            $noti_text = "Say hi";
        } else
        if ($noti_type == "comment") {
            $noti_title = "$noti_username commented";
            $noti_text = "$noti_content";
        } else
        if ($noti_type == "system") {
            $noti_title = "System update";
            $noti_text = $noti_content;
        } else {
            $noti_text = $noti_content;
        }
        ?>
        <div class="noti" id="noti_<?=$noti_id?>">
            <div class="noti-status" id="noti_status_<?=$noti_id?>">
                <?php if ($noti_read == "1") {?>
                <i class="fa-solid fa-envelope-open-text"></i>
                <?php } else {?>
                <i class="fa-solid fa-envelope"></i>
                <?php }?>
            </div>
            <div class="noti-content" onclick="expandNoti(this);markRead(<?=$noti_id?>)">
                <div class="noti-title"><?=$noti_title?></div>
                <span><?=$noti_text?></span>
            </div>
            <div class="noti-actions">
                <div class="noti-action" onclick="showNoti(<?=$noti_id?>)"><i class="fa-solid fa-arrow-up-right-from-square"></i></div>
                <div class="noti-action" onclick="delNoti(<?=$noti_id?>)"><i class="fa-solid fa-trash-can"></i></div>
            </div>
        </div>
        <?php
    }
} else {?>
<center><br>No new notifications<br><br></center>
<?php }?>