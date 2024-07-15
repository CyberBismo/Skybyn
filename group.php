<?php include_once "assets/header.php";

if (!isset($_SESSION['user'])) {
    ?><meta http-equiv="Refresh" content="0; url='./'" /><?php
    return false;
}

if (isset($_GET['id'])) {
    $groupID = $_GET['id'];
    
    $checkGID = $conn->query("SELECT * FROM `groups` WHERE `id`='$groupID'");
    if ($checkGID->num_rows == 1) {
        $groupData = $checkGID->fetch_assoc();
        $groupName = $groupData['name'];
        $groupDesc = $groupData['description'];
        $groupOwner = $groupData['owner'];
    
        $groupIcon = $groupData['icon'];
        $groupWallpaper = $groupData['wallpaper'];
        
        $groupPrivacy = $groupData['privacy'];
        $groupLockType = $groupData['lock_type'];
        
        $groupPW = $groupData['password'];
        $groupPIN = $groupData['pin'];

        $folder = "data/groups/";
        $filename = $groupID . ".json";
        $filePath = $folder . $filename;

        if (!file_exists($filePath)) {
            $data = [
                "members" => [
                    "$uid" => [
                        "username" => "$username",
                        "avatar" => "$avatar",
                    ]
                ]
            ];

            $jsonData = json_encode($data, JSON_PRETTY_PRINT);

            if (!is_dir($folder)) {
                mkdir($folder, 0777, true);
            }
            file_put_contents($filePath, $jsonData);
        } else {
            $json = file_get_contents($filePath);
            $data = json_decode($json, true);
            $data["members"][$uid] = [
                "username" => "$username",
                "avatar" => "$avatar",
            ];

            $jsonData = json_encode($data, JSON_PRETTY_PRINT);
            file_put_contents($filePath, $jsonData);
        }
    } else {
        ?><meta http-equiv="Refresh" content="0; url='.?notfound'" /><?php
    }
} else {
    ?><meta http-equiv="Refresh" content="0; url='./groups'" /><?php
}

?>
        <div class="page-container">
            <div class="page-head group-head">
                <?=$groupName?>
            </div>
            <?php
            if ($groupPrivacy == "1") {
                if ($groupLockType == "pin") {
                    $lock = 'pattern="[0-9]" placeholder="PIN required"';
                }
                if ($groupLockType == "password") {
                    $lock = 'placeholder="Password required"';
                }
                ?>
                <div class="group-lock">
                    <input type="password" <?=$lock?> required>
                    <input type="submit" name="enter_group" value="ENTER">
                </div>
                <?php
            } else {?>
            <div class="group-box">
                <!--div class="gbox-left">
                    <div class="gbox-memberlist">
                        < ?php $groupMembers = $conn->query("SELECT * FROM `group_members` WHERE `group`='$groupID'");
                        while($groupMember = $groupMembers->fetch_assoc()) {
                            $gmid = $groupMember['user'];
                            $getMemberData = $conn->query("SELECT * FROM `users` WHERE `id`='$gmid'");
                            $gmd = $getMemberData->fetch_assoc();
                            $memberName = $gmd['username'];
                            $memberAvatar = $gmd['avatar'];

                            if ($memberAvatar == "") {
                                $memberAvatar = "../assets/images/logo_faded_clean.png";
                            }
                        ?>
                        <div class="gbox-member" id="< ?=$gmid?>">
                            <img src="< ?=$memberAvatar?>">
                            <div class="gbox-member-name">< ?=$memberName?></div>
                        </div>
                        < ?php }?>
                    </div>
                </div-->
                <div class="gbox-main">
                    <div class="gbox-chat" id="message-feed">
                        <?php
                        $getMessages = mysqli_query($conn, "SELECT * FROM `group_messages` WHERE `group`='$groupID' ORDER BY `date` ASC");
                        while ($message = mysqli_fetch_assoc($getMessages)) {
                            $message_id = $message['id'];
                            $message_user = $message['user'];
                            $message_content = $message['content'];
                            $message_created = date("Y-m-d H:i:s", $message['date']);
                            $message_system = $message['system'];

                            $getUserData = mysqli_query($conn, "SELECT * FROM `users` WHERE `id`='$message_user'");
                            $gUser = mysqli_fetch_assoc($getUserData);
                            $guser_id = $gUser['id'];
                            $guser_name = $gUser['username'];
                            $guser_avatar = $gUser['avatar'];

                            if ($guser_avatar == "") {
                                $guser_avatar = "./assets/images/logo_faded_clean.png";
                            }

                            if ($guser_id == $uid) {?>
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
                                                <img src="<?=$guser_avatar?>">
                                            </div>
                                        </div>
                            <?php } else {?>
                                        <div class="chat_message" id="chat_<?=$message_id?>">
                                            <div class="chat_user">
                                                <img src="<?=$guser_avatar?>">
                                            </div>
                                            <div class="chat_text"><?=$message_content?></div>
                                        </div>
                            <?php 
                            }
                        }?>
                    </div>
                    <div class="gbox-msg-send form">
                        <?php if (getGM($groupID,$uid) == "ok") {?>
                        <input name="msg" placeholder="Message.." id="gmsg" onkeyup="sendMessage(event)" autofocus>
                        <i class="fa-solid fa-paper-plane" onclick="sendMessage()"></i>
                        <?php } else {?>
                        <input type="button" value="Join Group" onclick="joinGroup()">
                        <?php }?>
                    </div>
                </div>
                <div class="gbox-right">
                    <div class="gbox-right-icons">
                        <div class="gbox-right-icon"><i class="fa-solid fa-comments"></i></div>
                    </div>
                    <div class="gbox-right-icons">
                        <div class="gbox-right-icon"><i class="fa-solid fa-users"></i></div>
                    </div>
                    <div class="gbox-right-icons">
                        <div class="gbox-right-icon"><i class="fa-regular fa-images"></i></div>
                    </div>
                    <div class="gbox-right-icons">
                        <div class="gbox-right-icon"><i class="fa-solid fa-gear"></i></div>
                    </div>
                    <div class="gbox-right-icons gleave">
                        <div class="gbox-right-icon"><i class="fa-solid fa-right-from-bracket"></i></div>
                    </div>
                </div>
            </div>
            <?php }?>
        </div>

        <?php if (isMobile() == false) {?>
        <script>
            function setChatboxHeight() {
                const chatbox = document.getElementById('message-feed');
                chatbox.style.height = window.innerHeight - 430 + 'px';
            }
            //setChatboxHeight();
            //window.addEventListener('resize', setChatboxHeight);
        </script>
        <?php } else {?>
        <script>
            function setChatboxHeight() {
                const chatbox = document.getElementById('message-feed');
                chatbox.style.height = window.innerHeight - 270 + 'px';
            }
            setChatboxHeight();
            window.addEventListener('resize', setChatboxHeight);
        </script>
        <?php }?>

        <script>
            function isChatFeedAtBottom() {
                const messageFeed = document.getElementById('message-feed');
                return messageFeed.scrollTop + messageFeed.clientHeight === messageFeed.scrollHeight;
            }
            const messageFeed = document.getElementById('message-feed');
            messageFeed.addEventListener('mouseenter', () => {
            });
            messageFeed.addEventListener('mouseleave', () => {
                isChatFeedAtBottom();
            });
        </script>
        <script>
            function scrollMessageFeedToBottom() {
                const messageFeed = document.getElementById('message-feed');
                messageFeed.scrollTop = messageFeed.scrollHeight;
            }

            function joinGroup() {

            }
            
            function delGroup(x) {
                $.ajax({
                    url: '../assets/group_delete.php',
                    type: "POST",
                    data: {
                        group : x
                    }
                }).done(function(response) {
                    if (response != "error") {
                        window.location.href = "../";
                    }
                });
            }
            
            function sendMessage(event) {
                const feed = document.getElementById('message-feed');
                const msg = document.getElementById('gmsg');

                if (event.key === "Enter") {
                    $.ajax({
                        url: '../assets/group_send.php',
                        type: "POST",
                        data: {
                            group : <?=$groupID?>,
                            text : msg.value
                        }
                    }).done(function(response) {
                        if (response != "error") {
                            msg.value = "";
                            scrollMessageFeedToBottom();
                        }
                    });
                }
            }

            function checkMsgs() {
                const feed = document.getElementById('message-feed');
                var msgs = document.getElementsByClassName('chat_message');
                
                if (msgs.length > 0) {
                    var lastMsg = msgs[msgs.length - 1];
                    var gid = lastMsg.id;
                    $.ajax({
                        url: '../assets/group_check.php',
                        type: "POST",
                        data: {
                            group : <?=$groupID?>,
                            last : gid
                        }
                    }).done(function(response) {
                        var tempContainer = document.createElement('div');
                        tempContainer.innerHTML = response;
                        
                        while (tempContainer.firstChild) {
                            feed.appendChild(tempContainer.firstChild);
                        }
                    });
                }
            }
            setInterval(() => {
                checkMsgs();
            }, 1000);
        </script>
        <?php if (isset($_SESSION['user'])) {?>
        <script>
        window.addEventListener("beforeunload", function () {
            $.ajax({
                url: '../assets/group_leave.php',
                type: "POST",
                data: {
                    group : <?=$groupID?>,
                    user : <?=$uid?>
                }
            });
        });
        window.addEventListener("unload", function () {
            $.ajax({
                url: '../assets/group_leave.php',
                type: "POST",
                data: {
                    group : <?=$groupID?>,
                    user : <?=$uid?>
                }
            });
        });

        function createGboxMemberElement(id, username, avatarUrl) {
            const gboxMemberlist = document.getElementsByClassName('gbox-memberlist');
            const gboxMember = document.getElementById(id);
            if (!gboxMember) {
                const newElement = document.createElement('div');
                newElement.className = 'gbox-member';
                newElement.id = id;

                const imgElement = document.createElement('img');
                imgElement.src = avatarUrl;

                const nameElement = document.createElement('div');
                nameElement.className = 'gbox-member-name';
                nameElement.textContent = `[Guest] ${username}`;

                newElement.appendChild(imgElement);
                newElement.appendChild(nameElement);

                gboxMemberlist[0].appendChild(newElement);
            }
        }

        fetch('../data/groups/<?=$groupID?>.json')
        .then(response => response.json())
        .then(data => {
            const { id, username, avatar } = data;
            createGboxMemberElement(id, username, avatar);
        });

        function checkUserActivity() {
            var loggedInUserId = <?=$uid?>;

            fetch('../data/groups/<?=$groupID?>.json')
            .then(response => response.json())
            .then(data => {
                Object.keys(data.members).forEach(memberId => {
                    memberId = memberId.toString();
                    var memberElement = document.querySelector('.gbox-member[id="' + memberId + '"]');

                    if (memberElement) {
                        if (memberId === loggedInUserId.toString()) {
                            memberElement.classList.add('active');
                        } else {
                            memberElement.classList.remove('active');
                        }
                    } else {
                        console.error('Element with id="' + memberId + '" not found.');
                    }
                });
            })
            .catch(error => {
                console.error('Error loading JSON data:', error);
            });
        }
        setInterval(checkUserActivity, 500);
        </script>
        <?php }?>
    </body>
</html>