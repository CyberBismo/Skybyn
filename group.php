<?php include_once "assets/header.php";

if (!isset($_SESSION['user'])) {
    include_once "assets/forms/login-popup.php";
}

if (isset($_GET['g'])) {
    $groupID = $_GET['g'];
    
    $checkGID = $conn->query("SELECT * FROM `groups` WHERE `id`='$groupID'");
    if ($checkGID->num_rows == 1) {
        $groupData = $checkGID->fetch_assoc();
        $groupName = $groupData['name'];
        $groupDesc = $groupData['description'];
        $groupOwner = $groupData['owner'];
    
        $groupIcon = "../".$groupData['icon'];
        $groupWallpaper = $groupData['wallpaper'];
        
        $groupPrivacy = $groupData['privacy'];
        $groupLockType = $groupData['lock_type'];
        
        $groupPW = $groupData['password'];
        $groupPIN = $groupData['pin'];
                    
        if ($groupIcon == "../") {
            $groupIcon = "../assets/images/logo.png";
        }

        $folder = "data/groups/";
        $filename = $groupID . ".json";
        $filePath = $folder . $filename;

        $checkMembers = $conn->query("SELECT * FROM `group_members` WHERE `group`='$groupID' AND `user`='$uid'");
        $countMembers = $checkMembers->num_rows;

        if ($countMembers == 1) {
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
            if (!file_exists($filePath)) {
                $data = [
                    "guests" => [
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
                $data["guests"][$uid] = [
                    "username" => "$username",
                    "avatar" => "$avatar",
                ];
    
                $jsonData = json_encode($data, JSON_PRETTY_PRINT);
                file_put_contents($filePath, $jsonData);
            }
        }?>
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
                                $message_content = html_entity_decode($message['content'], ENT_QUOTES | ENT_HTML5, 'UTF-8');
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

        <?php if (isMobile($userAgent) == false) {?>
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
                    },
                    success: function(response) {
                        if (response != "error") {
                            window.location.href = "../";
                        }
                    }
                });
            }

            function removeDuplicates(x) {
                const allElements = document.querySelectorAll('*');
                const idMap = {};
                allElements.forEach(element => {
                    const id = element.id;
                    if (id) {
                        if (idMap[id]) {
                            // If the ID already exists in the map, remove the element
                            element.parentNode.removeChild(element);
                        } else {
                            // Otherwise, add the element to the map
                            idMap[id] = element;
                        }
                    }
                });
            }
            
            function sendMessage(event) {
                const feed = document.getElementById('message-feed');
                const msg = document.getElementById('gmsg');
                message = msg.value;

                let send = false;

                if (event != null) {
                    if (event.key === "Enter") {
                        send = true;
                    }
                } else {
                    send = true;
                }

                if (send === true) {
                    msg.value = "";
                    $.ajax({
                        url: '../assets/group_send.php',
                        type: "POST",
                        data: {
                            group : <?=$groupID?>,
                            text : message
                        },
                        success: function(response) {
                            if (response.responseCode === "ok") {
                                var msgId = response.messageId;
                                getMsgs();
                            }
                        }
                    });
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
                    },
                    success: function(response) {
                        feed.innerHTML += response;
                        scrollMessageFeedToBottom();
                    }
                });
            }

            function cleanUp() {
                var msgs = document.getElementsByClassName('gchat-message');
                var count = msgs.length;
                
                if (count > 0) {
                    var first = msgs[0].id;
                    var last = msgs[count-1].id;
                    $.ajax({
                        url: '../assets/group_clean.php',
                        type: "POST",
                        data: {
                            group : <?=$groupID?>,
                            first : first,
                            last : last
                        },
                        success: function(response) {
                            if (response.responseCode === "ok") {
                                response.array.forEach(element => {
                                    var el = document.getElementById(element);
                                    if (el) {
                                        el.remove();
                                    }
                                });
                            }
                        }
                    });
                }
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
                        },
                        success: function(response) {
                            if (response.responseCode === "ok") {
                                getMsgs();
                            }
                        }
                    });
                }

                setTimeout(() => {
                    checkMsgs();
                    removeDuplicates();
                }, 1000);
            }
            checkMsgs();
            function checkDeletedMsgs() {
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
                        },
                        success: function(response) {
                            if (response.responseCode === "ok") {
                                getMsgs();
                            }
                        }
                    });
                }

                setTimeout(() => {
                    checkMsgs();
                    removeDuplicates();
                }, 1000);
            }
            checkDeletedMsgs();

            function delMsg(x) {
                var msg = document.getElementById("chat_<?=$groupID?>_"+x);
                $.ajax({
                    url: '../assets/group_del_msg.php',
                    type: "POST",
                    data: {
                        message : x
                    },
                    success: function(response) {
                        if (response.responseCode === "ok") {
                            msg.remove();
                        }
                    }
                })
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

        function checkUserActivity() {
            fetch('../data/groups/<?=$groupID?>.json')
            .then(response => response.json())
            .then(data => {
                if (Object.keys(data.members).length > 0) {
                    Object.keys(data.members).forEach(memberId => {
                        memberId = memberId.toString();
                        const {username,avatar} = data.members[memberId];
                        createGboxMemberElement(memberId, username, avatar);
                    });
                }
                if (Object.keys(data.guests).length > 0) {
                    Object.keys(data.guests).forEach(guestId => {
                        guestId = guestId.toString();
                        const {username,avatar} = data.guests[guestId];
                        createGboxMemberElement(guestId, username, avatar);
                    });
                }
            });
            
            fetch('../data/groups/<?=$groupID?>.json')
            .then(response => response.json())
            .then(data => {
                document.querySelectorAll('.gbox-member').forEach(memberElement => {
                    memberElement.classList.remove('active');
                });
                Object.keys(data.members).forEach(memberId => {
                    memberId = memberId.toString();
                    var memberElement = document.querySelector('.gbox-member[id="' + memberId + '"]');
                    if (memberElement) {
                        memberElement.classList.add('active');
                    }
                });
                Object.keys(data.guests).forEach(guestId => {
                    guestId = guestId.toString();
                    var guestElement = document.querySelector('.gbox-member[id="' + guestId + '"]');
                    if (guestElement) {
                        guestElement.classList.add('active');
                    }
                });
            })
            .catch(error => {
                console.error('Error loading JSON data:', error);
            });
        }
        setInterval(checkUserActivity, 1000);
        </script>
        <?php }
    } else {
        ?><meta http-equiv="Refresh" content="0; url='./group?new'" /><?php
    }
} else {
if (isset($_GET['new'])) {?>
        <div class="group-container">
            <div class="page-head">
                Create New Group
            </div>
            <div class="new-group-create">
                <div class="left" id="left">
                    <h3>What are groups for<span onclick="showLeft()"><i class="fa-solid fa-angles-right"></i></span></h3>
                    <ul>
                        <li>Meeting ground for people</li>
                        <li>Share with many at once</li>
                        <li>Plan a party</li>
                        <li>Make discussions easier</li>
                        <li>Make your own rules</li>
                    </ul>
                </div>
                <div class="form">
                    <i class="fa-solid fa-sign-hanging"></i>
                    <input type="text" name="group_name" id="ng-name" placeholder="Name of group" autofocus required>
                    <i class="fa-solid fa-quote-right"></i>
                    <textarea name="group_desc" id="ng-desc" placeholder="Who is this group for"></textarea>
                    <div class="new-group-privacy">
                        <span><input type="radio" name="group_privacy" value="open" id="ng-p-open" checked> Open</span>
                        <span><input type="radio" name="group_privacy" value="locked" id="ng-p-locked"> Locked</span>
                        <span><input type="radio" name="group_privacy" value="private" id="ng-p-private"> Private</span>
                    </div>
                    <div id="lock-options" style="display: none">
                        <select name="group_lock_type" id="ng-lt" onchange="lockType(this)">
                            <option value="" hidden>- Select lock type -</option>
                            <option value="password">Password</option>
                            <option value="pin">PIN code</option>
                        </select>
                        <input type="password" name="group_password" id="lt-password" placeholder="Password" title="Enter a password to access the group" autocomplete="new-password" style="display: none">
                        <input type="password" name="group_pin" id="lt-pin" pattern="[0-9]{4,}" placeholder="PIN" title="Enter a PIN code to access the group" autocomplete="new-password" style="display: none">
                    </div>
                    
                    <input type="submit" onclick="createGroup()" value="Create">
                </div>
            </div>
        </div>

        <script>
            const privacyInputs = document.getElementsByName('group_privacy');
            privacyInputs.forEach(element => {
                element.addEventListener('change', function () {
                    const lockOptions = document.getElementById('lock-options');
                    if (element.value == "locked") {
                        lockOptions.style.display = "block";
                    } else {
                        lockOptions.style.display = "none";
                    }
                });
            });

            function showLeft() {
                const left = document.getElementById('left');
                if (left.style.height == "auto") {
                    left.style.height = "80px";
                } else {
                    left.style.height = "auto";
                }
            }
            function lockType(x) {
                const pw = document.getElementById('lt-password');
                const pin = document.getElementById('lt-pin');
                if (x.value == "password") {
                    pw.style.display = "block";
                    pin.style.display = "none";
                }
                if (x.value == "pin") {
                    pin.style.display = "block";
                    pw.style.display = "none";
                }
            }
            function createGroup() {
                const name = document.getElementById('ng-name');
                const desc = document.getElementById('ng-desc');
                const privacy = document.getElementsByName('privacy').value;
                const lockType = document.getElementById('ng-lt').value;
                const password = document.getElementById('lt-password').value;
                const pin = document.getElementById('lt-pin').value;
                
                if (privacy === 'locked') {
                    if (lockType === 'password') {
                        password = password;
                    } else if (lockType === 'pin') {
                        pin = pin;
                    }
                }
                
                const data = {
                    group_name: name.value,
                    group_desc: desc.value,
                    group_privacy: privacy,
                    group_lock_type: lockType,
                    group_password: password,
                    group_pin: pin,
                };

                $.ajax({
                    url: '../assets/group_new.php',
                    type: "POST",
                    data: data,
                }).done(function (response) {
                    var result = JSON.parse(response);
                    var response = result['response'];
                    var message = result['message'];

                    if (response === "ok") {
                        window.location.href = "./group?id=" + message;
                    }
                    if (response === "error") {
                        alert(message);
                    }
                });
            }
        </script>
<?php } else {?>
    <script>window.location.href = "./groups";</script>
<?php }}?>