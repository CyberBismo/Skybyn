<?php include_once "assets/header.php"?>
<?php
if (!isset($_SESSION['user'])) {
    include_once "assets/forms/login-popup.php";
    return;
}
?>
        <div class="page-container">
            <div class="messsages-head">
                <h3>Messages</h3>
                <input id="msg_search" onkeyup="searchMsg(this)" placeholder="Search in messages">
            </div>
            <div class="message-new" onclick="newChat()">
                New chat
            </div>
            <div class="messages">
                <?php
                $myFriends = $conn->query("SELECT * FROM `friendship` WHERE `user_id`='$uid' AND `status`='accepted'");
                $countFriends = $myFriends->num_rows;
                if ($countFriends > 0) {
                    while($friendId = $myFriends->fetch_assoc()) {
                        $fid = $friendId['friend_id'];
                        $getFriendData = $conn->query("SELECT * FROM `users` WHERE `id`='$fid'");
                        $friendData = $getFriendData->fetch_assoc();

                        $friend_username = $friendData['username'];
                        $friend_avatar = "./".$friendData['avatar'];
                        
                        if ($friend_avatar == "./") {
                            $friend_avatar = "./assets/images/logo_faded_clean.png";
                        }
    
                        $lastMessage = mysqli_query($conn, "SELECT * FROM `messages` WHERE `user` IN ('$uid','$fid') AND `friend` IN ('$uid','$fid') ORDER BY `date` DESC LIMIT 1");
                        $last = mysqli_fetch_assoc($lastMessage);
                        $last_user = $last['user'];
                        $msg = $last['content'];
                        $date = date("Y-m-d H:i:s", $last['date']);
                        $seen = $last['viewed'];
    
                        if ($seen == "0") {
                            $message_content = "New message: $msg";
                        } else
                        if ($last_user == $uid) {
                            $message_content = "You: $msg";
                        } else {
                            $message_content = "$msg";
                        }
    
                        ?>
                        <div class="message-box<?=$open?>" id="message_box_<?=$fid?>">
                            <div class="message-header">
                                <div class="message-user" onclick="maximizeMessageBox('<?=$fid?>')">
                                    <img src="<?=$friend_avatar?>" id="msg_user_avatar_<?=$fid?>">
                                    <span id="msg_user_name_<?=$fid?>"><?=$friend_username?></span>
                                </div>
                                <div class="message-actions">
                                    <div class="message-min" onclick="maximizeMessageBox('<?=$fid?>')"><i class="fa-solid <?=$icon?>" id="msg_min_<?=$fid?>"></i></div>
                                    <div class="message-close" onclick="closeMessageBox('<?=$fid?>')"><i class="fa-solid fa-xmark"></i></div>
                                </div>
                            </div>
                            <div class="message-body" id="message_body_<?=$fid?>">
                                <?php
                                $getMessages = $conn->query("SELECT * FROM `messages` WHERE `from`='$uid' AND `to`='$fid' OR `from`='$fid' AND `to`='$uid' ORDER BY `date` ASC");
                                if ($getMessages->num_rows > 0) {
                                    while ($msgData = $getMessages->fetch_assoc()) {
                                        $msg = $msgData['content'];
                                        $msg_from = $msgData['from'];
                                        $msg_to = $msgData['to'];
                                        $msg_date = $msgData['date'];
                                        $msg_id = $msgData['id'];
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