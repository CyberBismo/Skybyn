<?php include_once("../functions.php");

$fid = $_POST['friend'];
$amount = $_POST['amount'];

$getMsgs = $conn->query("SELECT * FROM `messages` WHERE `user`='$uid' AND `friend`='$fid'");
if ($getMsgs->num_rows < $amount) {
    echo "clean";
} else
if ($count > $amount) {
    $getMessages = $conn->query("SELECT * FROM `messages` WHERE `user` IN ('$uid','$fid') AND `friend` IN ('$uid','$fid') ORDER BY `date`, `viewed`='0' DESC");
    while ($message = $getMessages->fetch_assoc()) {
        $message_id = $message['id'];
        $message_user = $message['user'];
        $message_friend = $message['friend'];
        $message_content = $conn->real_escape_string(htmlentities(decrypt($message['content']), ENT_QUOTES | ENT_HTML5, 'UTF-8'));
        $message_created = date("Y-m-d H:i:s", $message['date']);
    
        $getFriendData = $conn->query("SELECT * FROM `users` WHERE `id`='$message_friend'");
        $friend = $getFriendData->fetch_assoc();
        $friend_name = $friend['username'];
        $friend_avatar = $friend['avatar'];

        if ($friend_avatar == "") {
            $friend_avatar = "./assets/images/logo_faded_clean.png";
        }
    
        if ($message_user == $uid) {
        ?>
                    <div class="chat_message">
                        <div class="chat_user">
                            <img src="<?=$friend_avatar?>">
                        </div>
                        <div class="chat_text"><?=$message_content?></div>
                    </div>
        <?php
        } else {
        ?>
                    <div class="chat_message me">
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
}?>