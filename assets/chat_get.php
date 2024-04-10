<?php include_once "functions.php";

$fid = $_POST['friend'];

$getMessages = mysqli_query($conn, "SELECT * FROM `messages` WHERE `user` IN ('$uid','$fid') AND `friend` IN ('$uid','$fid') ORDER BY `date` DESC");
while ($message = mysqli_fetch_assoc($getMessages)) {
    $message_id = $message['id'];
    $message_user = $message['user'];
    $message_friend = $message['friend'];
    $message_content = htmlspecialchars(cleanUrls(nl2br($message['content'])), ENT_QUOTES, 'UTF-8');
    $message_created = date("Y-m-d H:i:s", $message['date']);

    $getFriendData = mysqli_query($conn, "SELECT * FROM `users` WHERE `id`='$message_user'");
    $friend = mysqli_fetch_assoc($getFriendData);
    $friend_id = $friend['id'];
    $friend_name = $friend['username'];
    $friend_avatar = $friend['avatar'];

    if ($friend_avatar == "") {
        $friend_avatar = "./assets/images/logo_faded_clean.png";
    }

    if ($friend_id == $fid) {?>
                <div class="chat_message" id="chat_<?=$message_id?>">
                    <div class="chat_user">
                        <img src="<?=$friend_avatar?>">
                    </div>
                    <div class="chat_text"><?=$message_content?></div>
                </div>
    <?php } else {?>
                <div class="chat_message me" id="chat_<?=$message_id?>">
                    <div class="chat_message_options">
                        <i class="fa-solid fa-ellipsis-vertical"></i>
                        <div class="chat_message_option_list">
                            <div class="chat_action">
                                Share <i class="fa-solid fa-share"></i>
                            </div>
                            <div class="chat_action">
                                Delete <i class="fa-solid fa-trash"></i>
                            </div>
                        </div>
                    </div>
                    <div class="chat_message_box">
                        <div class="chat_text"><?=$message_content?></div>
                    </div>
                    <div class="chat_user">
                        <img src="<?=$friend_avatar?>">
                    </div>
                </div>
    <?php 
    }
}
?>