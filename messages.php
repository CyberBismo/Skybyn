<?php include_once "assets/header.php"?>
<?php
if (!isset($_SESSION['user'])) {
    include_once "assets/forms/login-popup.php";
    return;
}
?>
<style>
    .messsages-head {
        position: sticky;
        display: flex;
        justify-content: space-between;
        align-items: center;
        top: 75px;
        width: 100%;
        padding: 10px;
        background-color: rgba(0, 0, 0, 0.1);
        backdrop-filter: blur(10px);
        box-sizing: border-box;
        z-index: 10;
    }
    .messsages-head input {
        width: 100%;
        padding: 8px;
        border: 1px solid rgba(0, 0, 0, 0.2);
        border-radius: 5px;
        font-size: 16px;
        color: #fff;
        background-color: rgba(0, 0, 0, 0.3);
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        transition: border-color 0.3s;
        outline: none;
    }
    .messsages-container {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        width: 100%;
        padding: 10px;
        box-sizing: border-box;
        overflow-y: auto;
    }
    .messsages-box {
        flex-direction: column;
        width: 100%;
        height: 60px;
        background-color: rgba(0, 0, 0, 0.2);
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        transition: all 0.3s ease;
    }
    .messsages-box.open {
        width: 100%;
        height: 500px;
    }
    .messages-box-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px;
        background-color: none;
        cursor: pointer;
        box-sizing: border-box;
    }
    .message-user {
        display: flex;
        align-items: center;
    }
    .message-user-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        overflow: hidden;
        margin-right: 10px;
    }
    .message-user-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
</style>
        <div class="page-container">
            <div class="messsages-head">
                <input id="msg_search" onkeyup="searchMsg(this)" placeholder="Search in messages">
            </div>
            <div class="messsages-container">
                <?php if (isset($_SESSION['user'])) { if (getuser("id",$_SESSION['user'],"rank") > 3) {include_once './assets/design/chat_popup.php';}}?>
                <?php $checkActiveChats = $conn->query("SELECT * FROM `active_chats` WHERE `user`='$uid'");
                if ($checkActiveChats->num_rows > 0) {
                    while ($chatData = $checkActiveChats->fetch_assoc()) {
                        $friend = $chatData['friend'];
                        
                        $getFriendData = $conn->query("SELECT * FROM `users` WHERE `id`='$friend'");
                        $friendData = $getFriendData->fetch_assoc();
                        $friend_username = $friendData['username'];
                        $friend_avatar = "../".$friendData['avatar'];
                        if ($friend_avatar == "../") {
                            $friend_avatar = "../assets/images/logo_faded_clean.png";
                        }
                    ?>
                    <div class="messsages-box<?=$open?>" id="message_box_<?=$friend?>">
                        <div class="messages-box-header">
                            <div class="message-user">
                                <div class="message-user-avatar">
                                    <img src="<?=$friend_avatar?>" id="msg_user_avatar_<?=$friend?>">
                                </div>
                                <span id="msg_user_name_<?=$friend?>"><?=$friend_username?></span>
                            </div>
                            <div class="message-actions">
                                <?php if (isMobile($userAgent) == false) {?>
                                <div class="message-close" onclick="closeMessageBox('<?=$friend?>')"><i class="fa-solid fa-xmark"></i></div>
                                <?php }?>
                            </div>
                        </div>
                        <div class="messages-body" id="message_body_<?=$friend?>">
                            <?php
                            $getMessages = $conn->query("SELECT * FROM `messages` WHERE `from`='$uid' AND `to`='$friend' OR `from`='$friend' AND `to`='$uid' ORDER BY `date` ASC");
                            if ($getMessages->num_rows > 0) {
                                while ($msgData = $getMessages->fetch_assoc()) {
                                    $msg_id = $msgData['id'];
                                    $msg = decrypt($msgData['content']);
                                    $msg_from = $msgData['from'];
                                    $msg_to = $msgData['to'];
                                    $msg_date = $msgData['date'];
                                    $msg_time = date("H:i", $msg_date);
                                    $msg_date = date("d.m.Y", $msg_date);

                                    if ($msg_from == $uid) {
                                        $msg_class = " me";
                                    } else {
                                        $msg_class = "";
                                    }

                                    if ($msg_from == $uid) {
                                        $msg_from = "You";
                                    } else {
                                        $msg_from = $friend_username;
                                    }
                                    ?>
                                    <div class="message<?=$msg_class?>">
                                        <div class="message-user">
                                            <?php if ($msg_class == "") {?>
                                            <div class="message-user-avatar"><img src="<?=$friend_avatar?>"></div>
                                            <div class="message-user-name"><?=$msg_from?></div>
                                            <?php } else {?>
                                            <div class="message-user-name"><?=$msg_from?></div>
                                            <div class="message-user-avatar"><img src="<?=$avatar?>"></div>
                                            <?php }?>
                                        </div>
                                        <div class="message-content"><p><?=$msg?></p></div>
                                    </div>
                                    <?php
                                }
                            }
                            ?>
                        </div>
                        <div class="message-input">
                            <input type="text" id="message_input_<?=$friend?>" placeholder="Type your message..." onkeyup="if (event.keyCode === 13) {sendMessage('<?=$uid?>', '<?=$friend?>');}">
                            <button onclick="sendMessage('<?=$uid?>','<?=$friend?>')"><i class="fa-solid fa-paper-plane"></i></button>
                        </div>
                    </div>
                    <?php
                    }
                }
                ?>
            </div>
        </div>

        <script>
        </script>
    </body>
</html>