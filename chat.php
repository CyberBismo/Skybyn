<?php include_once "assets/header.php"?>
<?php
if (!isset($_SESSION['user'])) {
    ?><meta http-equiv="Refresh" content="0; url='./'" /><?php
}

$fid = substr($_GET['u'], 4);

$getFriendData = mysqli_query($conn, "SELECT * FROM `users` WHERE `id`='$fid'");
$friend = mysqli_fetch_assoc($getFriendData);
$friend_id = $friend['id'];
$friend_username = $friend['username'];
$friend_avatar = $friend['avatar'];

if ($friend_avatar == "") {
    $friend_avatar = "./assets/images/logo_faded_clean.png";
}

$checkChat = $conn->query("SELECT * FROM `messages` WHERE `user` IN ('$uid','$fid') AND `friend` IN ('$uid','$fid')");
if ($checkChat->num_rows == 0) {
    //$conn->query("INSERT INTO `messages` (``,``) VALUES ()");
}
?>

        <div class="page-container">
            <div class="chat">
                <div class="chat_header">
                    <div class="chat_back" onclick="window.location.href='./messages'"><i class="fa-solid fa-arrow-left"></i></div>
                    <div class="chat_title">
                        <div class="chat_title_avatar">
                            <img src="<?=$friend_avatar?>">
                        </div>
                        <?=$friend_username?>
                    </div>
                    <div class="chat_action"></div>
                </div>
                <div class="chat_box" id="chatbox">
                    <?php
                    $getMessages = mysqli_query($conn, "SELECT * FROM `messages` WHERE `user` IN ('$uid','$fid') AND `friend` IN ('$uid','$fid') ORDER BY `date` ASC");
                    while ($message = mysqli_fetch_assoc($getMessages)) {
                        $message_id = $message['id'];
                        $message_user = $message['user'];
                        $message_friend = $message['friend'];
                        $message_content = html_entity_decode($message['content'], ENT_QUOTES | ENT_HTML5, 'UTF-8');
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
                                                    <i class="fa-solid fa-share"></i>Share
                                                </div>
                                                <div class="chat_action">
                                                    <i class="fa-solid fa-trash"></i>Delete
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
                    }?>
                </div>
                <div class="chat_input">
                    <input id="msg_id" hidden>
                    <input id="text" placeholder="Message.." autofocus autocomplete="new-password" required>
                    <button onclick="sendChat()"><i class="fa-solid fa-paper-plane"></i></button>
                </div>
            </div>
        </div>

        <script>
            $("#text").on('keyup', function (e) {
                if (e.key === 'Enter' || e.keyCode === 13) {
                    sendChat();
                }
            });
            function sendChat() {
                let text = document.getElementById('text').value;

                $.ajax({
                    url: 'assets/chat_send.php',
                    type: "POST",
                    data: {
                        to_id: "<?=$fid?>",
                        text: text
                    }
                }).done(function(response) {
                    console.log("Chat sent");
                    checkChats();
                });
                document.getElementById('text').value = "";
            }
            function checkChats() {
                let chatbox = document.getElementById('chatbox');
                let chat = chatbox.lastElementChild;
                let id = chat.id.replace("chat_", "");
                let amount = chatbox.children.length;
                console.log("Last chat: "+id);
                console.log("Messages: "+id);
                $.ajax({
                    url: 'assets/chat_check.php',
                    type: "POST",
                    data: {
                        friend: "<?=$fid?>",
                        amount: amount
                    }
                }).done(function(response) {
                    console.log("Checking chat: "+response);
                    if (response == "clean") {
                        cleanChat();
                    } else
                    if (response != "") {
                        chatbox.insertAdjacentHTML('afterend', response);
                    } else {
                        setTimeout(() => {
                            checkChats();
                        }, 100);
                    }
                });
            }
            checkChats();
            function cleanChat() {
                let chats = document.querySelectorAll('.chat_message');
                let chatIds = [];
                for (var i = 0; i < chats.length; i++) {
                    let chatId = chats[i].id.replace('chat_', '');
                    chatIds.push(chatId);
                }

                if (chatIds.length > 0) {
                    $.ajax({
                        url: 'assets/chat_clean.php',
                        type: "POST",
                        data: {
                            ids: chatIds
                        }
                    }).done(function(response) {
                        var nonExistingChatIds = response.split(',');
                        for (var i = 0; i < nonExistingChatIds.length; i++) {
                            var chatId = nonExistingChatIds[i];
                            var chat = document.getElementById('chat_' + chatId);
                            if (chat) {
                                chat.remove();
                            }
                        }
                    });
                }
            }
            function removeDuplicateIds() {
                const elements = document.querySelectorAll('*');
                const idMap = new Map();
                elements.forEach(element => {
                    const id = element.id;
                    if (id) {
                        if (idMap.has(id)) {
                            element.parentNode.removeChild(element);
                        } else {
                            idMap.set(id, true);
                        }
                    }
                });
            }
        </script>
    </body>
</html>