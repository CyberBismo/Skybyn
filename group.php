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
                    
        if ($groupIcon == "") {
            $groupIcon = "./assets/images/logo.png";
        }

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
        <div class="group-container">
            <div class="group-head">
                <?=$groupName?><img src="<?=$groupIcon?>">
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
            <div class="group-box" id="gbox">
                <div class="gbox-main">
                    <div id="gbox-chat">
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

                                $msgID = "chat_".$groupID."_$message_id";

                                if ($guser_id == $uid) {?>
                                            <div class="gchat-message me" id="<?=$msgID?>">
                                                <div class="gchat-message-options">
                                                    <i class="fa-solid fa-ellipsis-vertical"></i>
                                                    <div class="gchat-message-option-list">
                                                        <div class="gchat-action" onclick="editMsg(<?=$message_id?>)">
                                                            <i class="fa-regular fa-pen-to-square"></i>Edit
                                                        </div>
                                                        <div class="gchat-action" onclick="delMsg(<?=$message_id?>)">
                                                            <i class="fa-solid fa-trash"></i>Delete
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="gchat-message-box">
                                                    <div class="gchat-text"><?=$message_content?></div>
                                                </div>
                                                <div class="gchat-user">
                                                    <img src="<?=$guser_avatar?>">
                                                </div>
                                            </div>
                                <?php } else {?>
                                            <div class="gchat-message" id="<?=$msgID?>">
                                                <div class="gchat-user">
                                                    <img src="<?=$guser_avatar?>">
                                                </div>
                                                <div class="gchat-text"><?=$message_content?></div>
                                            </div>
                                <?php 
                                }
                            }?>
                        </div>
                        <div class="gbox-msg-send form">
                            <?php if (getGM($groupID,$uid) == "ok") {?>
                            <input name="msg" placeholder="Message.." id="gmsg" onkeyup="sendMessage(event)" autofocus>
                            <i class="fa-solid fa-paper-plane" onclick="sendMessage(null)"></i>
                            <?php } else {?>
                            <input type="button" value="Join Group" onclick="joinGroup()">
                            <?php }?>
                        </div>
                    </div>
                    <div id="gbox-members" hidden>
                        <div class="gbox-memberlist">
                            <?php
                            $groupMembers = $conn->query("SELECT * FROM `group_members` WHERE `group`='$groupID'");
                            while ($groupMember = $groupMembers->fetch_assoc()) {
                                $gmid = $groupMember['user'];
                                $getMemberData = $conn->query("SELECT * FROM `users` WHERE `id`='$gmid'");
                                $gmd = $getMemberData->fetch_assoc();
                                $memberID = $gmd['id'];
                                $memberName = $gmd['username'];
                                $memberAvatar = $gmd['avatar'];

                                if ($memberAvatar == "") {
                                    $memberAvatar = "../assets/images/logo_faded_clean.png";
                                }
                                ?>
                                <div class="gbox-member" id="<?=$gmid?>">
                                    <img src="<?=$memberAvatar?>" alt="Avatar">
                                    <div class="gbox-member-name"><?=$memberName?></div>
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                    <div id="gbox-gallery" hidden></div>
                    <div id="gbox-settings" hidden>
                        <div class="gbox-settings">
                            <div class="split">
                                <div class="divider">
                                    <label>Icon:</label>
                                    <div class="gbs-icon">
                                        <img src="<?=$groupIcon?>">
                                    </div>

                                    <label>Name:</label>
                                    <input placeholder="<?=$groupName?>">
                                    
                                    <label>Description:</label>
                                    <textarea placeholder="<?=$groupDesc?>"></textarea>
                                </div>
                                <div class="divider">
                                    <label>Security:</label>
                                    <select>
                                        <option>Open</option>
                                        <option>Private</option>
                                        <option>Locked</option>
                                    </select>

                                    <p>This group is only visible for your friends.</p>

                                    <label>PIN</label>
                                    <input placeholder="PIN code">

                                    <label>Password</label>
                                    <input placeholder="Password">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="gbox-logout" hidden></div>
                </div>
                <div class="gbox-right">
                    <div class="gbox-right-top">
                        <div class="gbox-right-icons">
                            <div class="gbox-right-icon" onclick="gvt('chat')"><i class="fa-solid fa-comments"></i></div>
                        </div>
                        <div class="gbox-right-icons">
                            <div class="gbox-right-icon" onclick="gvt('members')"><i class="fa-solid fa-users"></i></div>
                        </div>
                        <div class="gbox-right-icons">
                            <div class="gbox-right-icon" onclick="gvt('gallery')"><i class="fa-regular fa-images"></i></div>
                        </div>
                    </div>
                    <div class="gbox-right-icons">
                        <div class="gbox-right-icon" onclick="gvt('settings')"><i class="fa-solid fa-gear"></i></div>
                    </div>
                    <div class="gbox-right-icons gleave">
                        <div class="gbox-right-icon" onclick="gvt('logout')"><i class="fa-solid fa-right-from-bracket"></i></div>
                    </div>
                </div>
            </div>
            <?php }?>
        </div>

        <?php if (isMobile() == false) {?>
        <script>
            function setChatboxHeight() {
                const gbox = document.getElementById('gbox');
                gbox.style.height = window.innerHeight - 300 + 'px';
            }
            setChatboxHeight();
            window.addEventListener('resize', setChatboxHeight);
        </script>
        <?php } else {?>
        <script>
            function setChatboxHeight() {
                const gbox = document.getElementById('gbox');
                gbox.style.height = window.innerHeight - 300 + 'px';
            }
            setChatboxHeight();
            window.addEventListener('resize', setChatboxHeight);
        </script>
        <?php }?>

        <script>
            function gvt(x) { // Group View Toggle
                const view = document.getElementById('gbox-'+x);
                if (view.hasAttribute("hidden")) {
                    const chat = document.getElementById('gbox-chat').setAttribute("hidden","");
                    const members = document.getElementById('gbox-members').setAttribute("hidden","");
                    const gallery = document.getElementById('gbox-gallery').setAttribute("hidden","");
                    const settings = document.getElementById('gbox-settings').setAttribute("hidden","");
                    const logout = document.getElementById('gbox-logout').setAttribute("hidden","");
                    view.removeAttribute("hidden");
                }
            }
            
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

                let send = false;

                if (event != null) {
                    if (event.key === "Enter") {
                        send = true;
                    }
                } else {
                    send = true;
                }

                if (send === true) {
                    $.ajax({
                        url: '../assets/group_send.php',
                        type: "POST",
                        data: {
                            group : <?=$groupID?>,
                            text : msg.value
                        }
                    }).done(function(response) {
                        msg.value = "";
                    });
                    getMsgs();
                    scrollMessageFeedToBottom();
                }
            }

            function getMsgs() {
                var feed = document.getElementById('message-feed');

                var msgs = document.getElementsByClassName('gchat-message');
                var count = msgs.length;

                if (count > 0) {
                    var id = msgs[count-1].id;
                } else {
                    var id = 'chat_<?=$groupID?>_0';
                }

                $.ajax({
                    url: '../assets/group_get.php',
                    type: "POST",
                    data: {
                        group : <?=$groupID?>,
                        last : id
                    }
                }).done(function(response) {
                    feed.innerHTML += response;
                    scrollMessageFeedToBottom();
                });
            }

            function checkMsgs() {
                var msgs = document.getElementsByClassName('gchat-message');
                var count = msgs.length;
                
                if (count > 0) {
                    var id = msgs[count-1].id;
                    $.ajax({
                        url: '../assets/group_check.php',
                        type: "POST",
                        data: {
                            group : <?=$groupID?>,
                            last : id
                        }
                    }).done(function(response) {
                        if (response.responseCode === "ok") {
                            getMsgs();
                        }
                    });
                }

                setTimeout(() => {
                    checkMsgs();
                    scrollMessageFeedToBottom();
                }, 1000);
            }
            checkMsgs();

            function delMsg(x) {
                var msg = document.getElementById("chat_<?=$groupID?>_"+x);
                $.ajax({
                    url: '../assets/group_del_msg.php',
                    type: "POST",
                    data: {
                        message : x
                    }
                }).done(function(response) {
                    if (response.responseCode === "ok") {
                        msg.remove();
                    }
                });
            }
            function editMsg(x) {}
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
            if (Object.keys(data).length === 0) {
                console.log("The data is empty.");
                return;
            }
            const { id, username, avatar } = data;
            //createGboxMemberElement(id, username, avatar);
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
        setInterval(checkUserActivity, 1000);
        </script>
        <?php }?>
    </body>
</html>