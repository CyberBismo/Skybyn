<?php include_once "../functions.php";

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
            $noti_text = "<a href=\"./profile/$noti_username\"><i class=\"fa-solid fa-circle-user\"></i>View their profile</a>";
            $noti_action = "";
        } else
        if ($noti_type == "friend_accepted") {
            $noti_title = "$noti_username is now your friend";
            $noti_text = "<a href=\"./profile/$noti_username\"><i class=\"fa-solid fa-circle-user\"></i></a><i class=\"fa-solid fa-message\" onclick=\"startMessaging('$uid','$noti_from')\"></i>";
            $noti_action = "";
        } else
        if ($noti_type == "comment") {
            $noti_title = "$noti_username commented";
            $noti_text = "$noti_content";
            $noti_action = "";
        } else
        if ($noti_type == "system") {
            $noti_title = "System update";
            $noti_text = $noti_content;
            $noti_action = "";
        } else
        if ($noti_type == "referral") {
            $noti_title = "You referred a new user";
            $noti_text = $noti_content;
            $noti_action = "";
        } else
        if ($noti_type == "new_friend") {
            $noti_title = "$noti_username is now your friend";
            $noti_text = $noti_content;
            $noti_action = "";
        } else {
            $noti_title = "";
            $noti_text = $noti_content;
            $noti_action = "";
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