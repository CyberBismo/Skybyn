<?php include_once "assets/header.php"?>
<?php
if (!isset($_SESSION['user'])) {
    ?><meta http-equiv="Refresh" content="0; url='./'" /><?php
}
?>
        <div class="page-container">
            <div class="messsages-head">
                <h3>Messages</h3>
                <input placeholder="Search in messages">
            </div>
            <div class="message-new" onclick="newChat()">
                > Start new conversation <
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

                        $f_id = rand(1000,9999).$fid;

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
                        <div class="message-item" onclick="window.location.href='./chat?u=<?=$f_id?>'">
                            <div class="message-avatar">
                                <img src="<?=$friend_avatar?>">
                            </div>
                            <div class="message-info">
                                <div class="message-user"><?=$friend_name?></div>
                                <div class="message-last"><?=$message_content?></div>
                            </div>
                            <div class="message-action">
                                <i class="fa-regular fa-comment"></i>
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