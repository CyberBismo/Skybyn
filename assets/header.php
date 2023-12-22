<?php include_once "functions.php";

$devDomain = 'dev.skybyn.no';
$currentUrl = domain();
if ($currentUrl == $devDomain) {
    $dev_access = true;
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?=skybyn("title")?></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <meta name="mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black">
        <link rel="apple-touch-icon" sizes="180x180" href="/images/logo_fav.png">
        <link rel="icon" type="image/x-icon" href="assets/images/logo_fav.png">
        <link href="fontawe/css/all.css" rel="stylesheet">
        <script src="https://kit.fontawesome.com/bafdb5f0e9.js" crossorigin="anonymous"></script>
        <script src="assets/js/jquery.min.js"></script>
        <?php include_once "style.php"?>
    </head>
    <body onload="hideMsg()">
        <!--button id="install-button" style="display: none;">Install App</button-->
        <?php if ($_SESSION['verify']) {?>
        <div class="msg" id="msg">
            <?=$_SESSION['verify']?>
        </div>
        <?php }?>
        <?php if ($_COOKIE['msg']) {?>
        <div class="msg" id="msg">
            <?=$_COOKIE['msg']?>
        </div>
        <?php }?>
        <script>
            function hideMsg() {
                const msg = document.getElementById('msg');
                if (msg) {
                    console.log("Hiding message in 5..");
                    setTimeout(() => {
                        msg.style.display = "none";
                        console.log("Hidden");
                    }, 5000);
                }
            }
        </script>
        <?php
        if (isset($_SESSION['user'])) {
            if ($verified == 1) {
                if (!empty($username)) {?>
        <!--?php include_once "bottom_nav.php"?>
        < ?php include_once "side_menu.php"?>
        < ?php include_once "friends.php"?-->
        <?php }}}?>

        <!--div class="clouds"></div-->

        <div class="header">
            <?php if (isset($_SESSION['user'])) {
            if (isMobile() == false) {
                include_once("assets/logo.php");
            }} else {
                include_once("assets/logo.php");
            }?>
            <?php if (isset($_SESSION['user'])) {
                if (isMobile() == false) {?>
            <div class="new_post_button" id="new_post_btn" onclick="newPost()">What's on your mind?</div>
            <?php }?>
            <?php }?>
            <?php if (!isset($_SESSION['user'])) {
                if (isMobile() == false) {?>
            <div class="login">
                <!--div class="form">
                    <?php include_once("assets/forms/login.php");?>
                </div-->
            </div>
            <?php }} else {?>
            <div class="top">
                <div class="top-nav">
                    <ul>
                        <?php if (isMobile() == true) {?>
                        <li onclick="showLeftPanel()"><i class="fa-solid fa-list-ul"></i></li>
                        <?php } else {?>
                        <li onclick="showNotifications(event)" id="notification">
                            <div class="notification_alert" id="noti_alert"><i class="fa-solid fa-circle-exclamation"></i></div>
                            <i class="fa-solid fa-bell"></i>
                        </li>
                        <?php }?>
                    </ul>
                </div>
                <div class="user-avatar">
                    <?php if (isMobile() == true) {?>
                    <img src="./assets/images/logo_faded_clean.png" onclick="window.location.href='./'">
                    <?php } else {?>
                    <img src="<?=$avatar?>" onclick="window.location.href='./profile'">
                    <?php }?>
                </div>
                <div class="user-nav" onclick="showUserMenu(event)">
                    <ul>
                        <li><i class="fa-solid fa-bars"></i></li>
                    </ul>
                </div>
            </div>
            <div class="user-dropdown" id="usermenu">
                <ul>
                    <li onclick="window.location.href='./'"><i class="fa-solid fa-house"></i>Home</li>
                    <li onclick="window.location.href='./profile'"><i class="fa-solid fa-user"></i></i>Profile</li>
                    <?php if ($rank > 0) {?>
                    <li><i class="fa-solid fa-people-group"></i>Groups</li>
                    <?php }?>
                    <?php if ($rank > 0) {?>
                    <li><i class="fa-solid fa-book-open"></i>Pages</li>
                    <?php }?>
                    <?php if ($rank > 0) {?>
                    <li class="pet"><i class="fa-solid fa-paw"></i>My Pets</li>
                    <?php }?>
                    <li onclick="window.location.href='./settings'"><i class="fa-solid fa-gears"></i>Settings</li>
                    <?php if ($rank > 0) {?>
                    <li class="balance"><i class="fa-solid fa-coins"></i><?=$wallet?></li>
                    <?php }?>
                    <hr>
                    <li onclick="window.location.href='./logout.php'"><i class="fa-solid fa-arrow-right-from-bracket"></i> Logout</li>
                </ul>
            </div>
            <?php }?>
        </div>

        <?php if (isset($_SESSION['user'])) {?>
        <div class="new_post" id="new_post" hidden>
            <div class="create_post">
                <img src="<?=$avatar?>">
                <div class="create_post_actions_top">
                    <span>
                        <i class="fa-solid fa-earth-americas"></i>
                        <select id="new_post_public">
                            <option value="1">Public</option>
                            <option value="0">Private</option>
                        </select>
                    </span>
                    <span>
                        <i class="fa-solid fa-location-dot"></i> <?=$country?>
                    </span>
                    <?php if (isMobile() == false) {?>
                    <span class="close" onclick="newPost('close')">
                        <i class="fa-solid fa-xmark"></i>
                    </span>
                    <?php }?>
                </div>
                <textarea type="text" placeholder="What's on your mind?" id="new_post_input" oninput="adjustTextareaHeight()" onkeydown="checkEnter()" onkeyup="convertEmoji(this.value)"></textarea>
                <div class="create_post_actions create_post_actions_bottom">
                    <span style="width:calc(100% - 100px);word-break: break-all">
                        <input type="file" id="image_to_share" accept=".jpg, .jpeg, .gif, .png" multiple hidden onchange="updateFileNameLabel()">
                        <label for="image_to_share"><i class="fa-solid fa-image"></i><span id="image_to_share_text">No image selected</span></label>
                    </span>
                    
                    <i class="fa-solid fa-paper-plane share" id="create_post_btn" onclick="createPost()"></i>
                </div>
                <div class="new_post_files" id="new_post_files"></div>
            </div>
        </div>
        <?php }?>

        <script>
            function showSearch() {
                const mobileSearch = document.getElementById('mobile-search');
                const search = document.getElementById('searchInput');
                const searchRes = document.getElementById('search_result');
                if (mobileSearch.style.transform == "translateY(0px)") {
                    mobileSearch.style.transform = "translateY(-135px)";
                    searchRes.style.display = "none";
                } else {
                    mobileSearch.style.transform = "translateY(0px)";
                    search.focus();
                }
            }
            function startSearch(x) {
                const searchResult = document.getElementById('search_result');
                const searchResUsers = document.getElementById('search_res_users');
                const searchRUsers = document.getElementById('search_r_users');
                const searchResGroups = document.getElementById('search_res_groups');
                const searchRGroups = document.getElementById('search_r_groups');
                const searchResPages = document.getElementById('search_res_pages');
                const searchRPages = document.getElementById('search_r_pages');

                if (x.value.length >= 4) {
                    searchResult.style.display = "block";

                    // Check if the input starts with "/user"
                    if (x.value.startsWith("/user ")) {
                        $.ajax({
                            url: 'assets/search_users.php',
                            type: "POST",
                            data: {
                                text: x.value
                            }
                        }).done(function(response) {
                            // Handle the response for user search
                            if (response != "") {
                                searchResUsers.removeAttribute("hidden");
                                searchRUsers.innerHTML = response;
                            } else {
                                searchResUsers.setAttribute("hidden", "");
                                searchRUsers.innerHTML = "";
                            }
                        });
                    } else
                    if (x.value.startsWith("/page ")) {
                        $.ajax({
                            url: 'assets/search_pages.php',
                            type: "POST",
                            data: {
                                text: x.value
                            }
                        }).done(function(response) {
                            // Handle the response for page search
                            if (response != "") {
                                searchResPages.removeAttribute("hidden");
                                searchRPages.innerHTML = response;
                            } else {
                                searchResPages.setAttribute("hidden", "");
                                searchRPages.innerHTML = "";
                            }
                        });
                    } else {
                        $.ajax({
                            url: 'assets/search.php',
                            type: "POST",
                            data: {
                                text: x.value
                            }
                        }).done(function(response) {
                            // Handle the response for page search
                            if (response != "") {
                                searchResPages.removeAttribute("hidden");
                                searchRPages.innerHTML = response;
                            } else {
                                searchResPages.setAttribute("hidden", "");
                                searchRPages.innerHTML = "";
                            }
                        });
                    }

                    // Add more conditions here if needed for other types of searches

                } else {
                    searchResult.style.display = "none";
                }
            }
            function showUserMenu(event) {
                const um = document.getElementById('usermenu');
                <?php if (isMobile() == true) {?>
                const left = document.getElementById('left-panel');
                const right = document.getElementById('right-panel');
                if (um.style.transform == "translateX(0px)") {
                    um.style.transform = 'translateX(100%)';
                } else {
                    um.style.transform = 'translateX(0px)';
                    left.style.transform = 'translateX(-100%)';
                    right.style.transform = 'translateX(100%)';
                }
                <?php } else {?>
                if (event) {
                    event.stopPropagation();
                    um.style.display = "block";
                }
                <?php }?>
            }
            function newPost(x) {
                const new_post_btn = document.getElementById('new_post_btn');
                const new_post = document.getElementById('new_post');
                const new_post_input = document.getElementById('new_post_input');
                const newPostIcon = document.getElementById('newPostIcon');

                if (x == "close") {
                    if (newPostIcon) {
                        newPostIcon.classList.add("fa-plus");
                        newPostIcon.classList.remove("fa-xmark");
                    }
                    new_post_btn.style.display = "block";
                    new_post.style.display = "none";
                } else {
                    if (new_post.style.display == "block") {
                        if (newPostIcon) {
                            newPostIcon.classList.add("fa-plus");
                            newPostIcon.classList.remove("fa-xmark");
                        }
                        new_post.style.display = "none";
                        new_post_btn.style.display = "block";
                    } else {
                        if (newPostIcon) {
                            newPostIcon.classList.add("fa-xmark");
                            newPostIcon.classList.remove("fa-plus");
                        }
                        new_post.style.display = "block";
                        new_post_btn.style.display = "none";
                        new_post_input.focus();
                    }
                }
            }
            function updateFileNameLabel() {
                const fileInput = document.getElementById('image_to_share');
                const fileNameLabel = document.getElementById('image_to_share_text');
                const filesDiv = document.getElementById('new_post_files');

                filesDiv.innerHTML = ''; // Clear the previous content

                if (fileInput.files.length === 0) {
                    fileNameLabel.textContent = 'No image selected';
                } else if (fileInput.files.length === 1) {
                    const file = fileInput.files[0];
                    if (isFileSupported(file)) {
                        fileNameLabel.textContent = file.name;
                        displayImageAsThumbnail(file, filesDiv);
                    } else {
                        fileNameLabel.textContent = 'Invalid file type';
                    }
                } else {
                    // Check the file types of all selected files
                    let allFilesSupported = true;
                    for (let i = 0; i < fileInput.files.length; i++) {
                        const file = fileInput.files[i];
                        if (!isFileSupported(file)) {
                            allFilesSupported = false;
                            break;
                        }
                        displayImageAsThumbnail(file, filesDiv);
                    }

                    fileNameLabel.textContent = allFilesSupported ? fileInput.files.length + ' files selected' : 'Invalid file type';
                }
            }
            function isFileSupported(file) {
                const allowedExtensions = ['.jpg', '.jpeg', '.gif', '.png'];
                const fileExtension = file.name.split('.').pop().toLowerCase();
                return allowedExtensions.includes('.' + fileExtension);
            }
            function displayImageAsThumbnail(file, filesDiv) {
                const reader = new FileReader();
                reader.onload = function (event) {
                    const img = document.createElement('img');
                    img.src = event.target.result;
                    filesDiv.appendChild(img);
                };
                reader.readAsDataURL(file);
            }
            let isCreatingPost = false;
            function createPost() {
                if (isCreatingPost) {
                    // If post creation is already in progress, do nothing
                    return;
                }

                isCreatingPost = true; // Set the flag to indicate post creation is in progress

                const text = document.getElementById('new_post_input');
                const public = document.getElementById('new_post_public');
                const image = document.getElementById('image_to_share');
                const filesDiv = document.getElementById('new_post_files');

                var formData = new FormData();
                for (var i = 0; i < image.files.length; i++) {
                    formData.append('files[]', image.files[i]);
                }

                formData.append('text', text.value);
                formData.append('public', public.value); // Visibility

                $.ajax({
                    url: 'assets/post_new.php',
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false
                }).done(function(response) {
                    console.log(response);
                    if (response !== "error") {
                        text.value = "";
                        image.value = "";
                        filesDiv.innerHTML = "";
                        checkPosts();
                        newPost("close");
                    }
                    isCreatingPost = false;
                });
            }
            function checkEnter() {
                let text = document.getElementById('new_post_input');

                text.addEventListener('keydown', function(event) {
                    if (event.key === "Enter" && !event.shiftKey) {
                        event.preventDefault();
                        createPost(); // Call the createPost function directly
                    }
                });
            }

            function convertEmoji(string) {
                let text = document.getElementById('new_post_input');
                const emojiMap = {
                    ':)': '🙂',
                    ':D': '😁',
                    ':P': '😛',
                    ':(': '🙁',
                    ';)': '😉',
                    ':O': '😮',
                    ':*': '😘',
                    '<3': '❤️',
                    ':/': '😕',
                    ':|': '😐',
                    ':$': '🤫',
                    ':s': '😕',
                    ':o)': '👽',
                    ':-(': '😞',
                    ':-)': '😊',
                    ':-D': '😂',
                    ':-P': '😜',
                    ':-/': '😕',
                    ':-|': '😐',
                    ';-)': '😉',
                    '=)': '😊',
                    '=D': '😃',
                    '=P': '😛',
                    '=\\': '😕',
                    ':poop:': '💩',
                    ':fire:': '🔥',
                    ':rocket:': '🚀',
                };
                text.value = string.replace(/(:\)|:D)/g, (match) => emojiMap[match]);
            }
            function adjustTextareaHeight() {
                const textarea = document.getElementById("new_post_input");
                textarea.rows = textarea.value.split("\n").length;
            }

            function showNotifications(event) {
                const notifications = document.getElementById('notifications');
                const notiList = document.getElementById('noti-list');
                $.ajax({
                    url: 'assets/noti_get.php'
                }).done(function(response) {
                    notiList.innerHTML = response;
                    notifications.style.display = "block";
                });
            }
            function hideMenus(event) {
                const usermenu = document.getElementById('usermenu');
                const notification = document.getElementById('notification');
                const notifications = document.getElementById('notifications');
                if (notifications.style.display == "block") {
                    if (!notifications.contains(event.target) && !notification.contains(event.target)) {
                        notifications.style.display = "none";
                        console.log("Hiding notification menu");
                    }
                }
                if (usermenu.style.display == "block") {
                    if (!usermenu.contains(event.target)) {
                        usermenu.style.display = "none";
                        console.log("Hiding user menu");
                    }
                }
            }
            <?php if (isset($_SESSION['user'])) {?>
            document.addEventListener('click', hideMenus);
            <?php }?>

            function showNoti(x) {
                let notiWin = document.getElementById('notification-window');
                let notWin_avatar = document.getElementById('noti_win_avatar');
                let notWin_user = document.getElementById('noti_win_username');
                let notWin_text = document.getElementById('noti_win_text');
                let notWin_foot = document.getElementById('noti_win_foot');
                let notWin_foot_profile = document.getElementById('noti_win_foot_profile');

                $.ajax({
                    url: 'assets/noti_window_data.php',
                    type: "POST",
                    data: {
                        noti : x
                    }
                }).done(function(response) {
                    data = response;
                    noti_from = data.noti_from;
                    noti_date = data.noti_date;
                    noti_profile = data.noti_profile;
                    noti_post = data.noti_post;
                    noti_type = data.noti_type;
                    
                    if (noti_from !== null) {
                        notWin_avatar.src.value = data.notiUserAvatar;
                        notWin_user.innerHTML = data.notiUserUsername;
                    } else {
                        notWin_user.innerHTML = "Skybyn";
                    }
                    if (noti_profile !== null) {
                        notWin_foot.removeAttribute("hidden");
                        var profileURL = "window.location.href='./profile="+noti_profile+"'";
                        notWin_foot_profile.setAttribute("onclick",profileURL);
                    } else {
                        notWin_foot.setAttribute("hidden","");
                    }
                    notWin_text.innerHTML = data.noti_content;

                    notiWin.removeAttribute("hidden");

                    $.ajax({
                        url: 'assets/noti_status.php',
                        type: "POST",
                        data: {
                            noti : x
                        }
                    }).done(function(response) {
                        const noti_status = document.getElementById('noti_status_'+x);
                        if (response === "1") {
                            noti_status.innerHTML = '<i class="fa-solid fa-envelope-open-text"></i>';
                        }
                    });
                });
            }
            function closeNotiWin() {
                const notiWin = document.getElementById('notification-window');
                if (notiWin.hasAttribute("hidden")) {
                    notiWin.removeAttribute("hidden");
                } else {
                    notiWin.setAttribute("hidden","");
                }
            }
            function readNoti() {
                $.ajax({
                    url: 'assets/noti_status.php',
                    type: "POST",
                    data: {
                        read: 1
                    }
                }).done(function(response) {
                    const noti_status = document.getElementsByClassName('noti-status');
                    for (let i = 0; i < noti_status.length; i++) {
                        noti_status[i].innerHTML = '<i class="fa-solid fa-envelope-open-text"></i>';
                    }
                });
            }
            function delNoti(x) {
                const notiList = document.getElementById('noti-list');
                const noti = document.getElementsByClassName('noti');
                if (x === "all") {
                    $.ajax({
                        url: 'assets/noti_delete.php',
                        type: "POST",
                        data: {
                            noti: 'all'
                        }
                    }).done(function(response) {
                        for (let i = 0; i < noti.length; i++) {
                            noti[i].remove();
                        }
                        notiList.innerHTML = '<center><br>No new notifications<br><br></center>';
                    });
                } else {
                    $.ajax({
                        url: 'assets/noti_delete.php',
                        type: "POST",
                        data: {
                            noti: x
                        }
                    }).done(function(response) {
                        document.getElementById('noti_'+x).remove();
                        if (noti.length == 0) {
                            notiList.innerHTML = '<center><br>No new notifications<br><br></center>';
                        }
                    });
                }
                checkNoti();
            }
            function checkNoti() {
                var notiAlert = document.getElementById('noti_alert');
                $.ajax({
                    url: 'assets/noti_check.php'
                }).done(function(response) {
                    if (response == "unread") {
                        notiAlert.style.opacity = '1';
                    } else {
                        notiAlert.style.opacity = '0';
                    }
                });
            }
        </script>
        <?php if (isset($_SESSION['user'])) {
            if (isMobile() == false) {?>
        <script>
            checkNoti();
            setInterval(() => {
                checkNoti();
            }, 3000);
        </script>
        <?php }}?>
        <?php if (isset($_SESSION['user'])) {?>
        <div class="notifications" id="notifications" style="display: none">
            <div class="notifications-head">
                <div onclick="readNoti()"><i class="fa-solid fa-envelope-open-text" title="Read all"></i></div>
                <h4>Notifications</h4>
                <div onclick="delNoti('all')"><i class="fa-solid fa-trash-can" title="Delete all"></i></div>
            </div>
            <div id="noti-list"></div>
        </div>
        <div class="notification-window" id="notification-window" hidden>
            <div class="noti-win-head">
                <div class="noti-win-head-user">
                    <img src="../assets/images/logo_faded_clean.png" id="noti_win_avatar">
                    <h4 id="noti_win_username">Username</h4>
                </div>
                <div class="noti-win-head-close" onclick="closeNotiWin()"><i class="fa-solid fa-xmark"></i></div>
            </div>
            <div class="noti-win-body" id="noti_win_text">This is a notification body text</div>
            <div class="noti-win-foot" id="noti_win_foot">
                <div class="btn" id="noti_win_foot_profile"><i class="fa-solid fa-circle-user" title="View profile"></i></div>
            </div>
        </div>
        <script>
            function expandNoti(x) {
                if (x.style.height === "auto") {
                    x.style.height = "40px";
                } else {
                    x.style.height = "auto";
                }
            }
            function markRead(x) {
                $.ajax({
                    url: 'assets/noti_read.php',
                    type: "POST",
                    data: {
                        noti : x
                    }
                }).done(function(response) {
                    
                });
            }
        </script>

        <div class="left-panel" id="left-panel">
            <div class="shortcuts groups">
                <h3><div><i class="fa-solid fa-comments"></i> Group chats</div><i class="fa-solid fa-plus" onclick="window.location.href='/newgroup'" title="Create new group"></i></h3>
                <div id="my-groups">
                    <div class="shortcut-browse" onclick="window.location='/groups'">
                        <div>Browse</div>
                    </div>
                    <?php
                    $myGroups = $conn->query("SELECT * FROM `group_members` WHERE `user`='$uid'");
                    
                    $countOwnedGroups = $myOwnedGroups->num_rows;
                    $countGroups = $myGroups->num_rows;
                    
                    if ($countOwnedGroups > 0 || $countGroups > 0) {
                        while ($groupData = $myGroups->fetch_assoc()) {
                            $gid = $groupData['group'];
                            $myGroupsData = $conn->query("SELECT * FROM `groups` WHERE `id`='$gid'");
                            $groupData = $myGroupsData->fetch_assoc();
                    
                            $group_name = $groupData['name'];
                            $group_owner = $groupData['owner'];
                            $group_icon = "./" . $groupData['icon'];
                    
                            if ($group_icon == "./") {
                                $group_icon = "./assets/images/logo.png";
                            }
                            ?>
                            <div class="group" onclick="window.location.href='../group?id=<?=$gid?>'">
                                <div class="group-icon"><img src="<?= $group_icon ?>"></div>
                                <div class="group-name"><?= $group_name ?></div>
                                <?php if ($group_owner == $uid) {?>
                                <div class="group-extra"><i class="fa-solid fa-crown"></i></div>
                                <?php }?>
                            </div>
                            <?php
                        }
                    }                    
                    ?>
                </div>
            </div>

            <div class="shortcuts pages">
                <h3><div><i class="fa-regular fa-newspaper"></i> Pages</div><i class="fa-solid fa-plus" title="Create new page"></i></h3>
                <div id="my-pages">
                    <div class="shortcut-browse" onclick="window.location='/pages'">
                        <div>Browse</div>
                    </div>
                    <?php
                    $myPages = $conn->query("SELECT * FROM `page_members` WHERE `user_id`='$uid'");
                    $countPages = $myPages->num_rows;
                    if ($countPages > 0) {
                        while($pageID = $myPages->fetch_assoc()) {
                            $pid = $pageID['id'];
                            $getPageData = $conn->query("SELECT * FROM `pages` WHERE `id`='$pid'");
                            $pageData = $getPageData->fetch_assoc();
        
                            $page_name = $pageData['name'];
                            $page_description = $pageData['description'];
                            $page_icon = $pageData['icon'];
                            $page_locked = $pageData['locked'];
                            ?>
                            <div class="page">
                                <div class="page-icon"><img src="<?=$page_icon?>"></div>
                                <div class="page-name"><?=$page_name?></div>
                            </div>
                            <?php
                        }
                    }
                    ?>
                </div>
            </div>

            <div class="shortcuts markets">
                <h3 onclick="window.location.href='./markets'"><div><i class="fa-solid fa-store"></i> Markets</div><i class="fa-solid fa-plus" title="Add to market"></i></h3>
                <div id="my-markets">
                    <div class="shortcut-browse">
                        <div>Browse</div>
                    </div>
                    <?php
                    $getMarkets = $conn->query("SELECT * FROM `markets`");
                    if ($getMarkets->num_rows > 0) {
                        while($m_data = $getMarkets->fetch_assoc()) {
                            $market_name = $m_data['name'];
                            ?>
                            <div class="sortcut">
                                <div class="sortcut-icon"><img src=""></div>
                                <div class="sortcut-name"><?=$market_name?></div>
                            </div>
                            <?php
                        }
                    }
                    ?>
                </div>
            </div>

            <div class="shortcuts gaming">
                <h3><div><i class="fa-solid fa-gamepad"></i> Gaming</div><!--i class="fa-solid fa-plus" title="Add new game"></i--></h3>
                <div id="my-games">
                    <div class="shortcut-browse">
                        <div>Browse</div>
                    </div>
                    <?php
                    $getGames = $conn->query("SELECT * FROM `games`");
                    if ($getGames->num_rows > 0) {
                        while($g_data = $getGames->fetch_assoc()) {
                            $game_name = $g_data['name'];
                            ?>
                            <div class="sortcut">
                                <div class="sortcut-icon"><img src=""></div>
                                <div class="sortcut-name"><?=$game_name?></div>
                            </div>
                            <?php
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
        
        <div class="right-panel" id="right-panel">
            <?php if (isMobile() == false) {?>
            <div class="search">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" id="searchInput" onkeyup="startSearch(this)" placeholder="Search">
            </div>
            <?php }?>
            <div class="friend-list">
                <h3>Friends</h3>
                <div id="friend-list">
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
                            ?>
                            <div class="friend">
                                <div class="friend-user">
                                    <div class="friend-avatar"><img src="<?=$friend_avatar?>"></div>
                                    <div class="friend-name"><?=$friend_username?></div>
                                </div>
                                <div class="friend-actions">
                                    <div class="friend-action" onclick="window.location.href='./profile?u=<?=$f_id?>'">
                                        <i class="fa-solid fa-user"></i>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                    }
                    ?>
                </div>
            </div>
        </div>

        <?php if (isMobile() == true) {?>
        <div class="mobile-search" id="mobile-search">
            <div class="search">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" id="searchInput" onkeyup="startSearch(this)" placeholder="Search">
            </div>
            <div class="search_result" id="search_result" hidden>
                <div id="search_res_users" hidden>
                    <p>Users</p>
                    <div id="search_r_users"></div>
                </div>
                <div id="search_res_groups" hidden>
                    <p>Groups</p>
                    <div id="search_r_groups"></div>
                </div>
                <div id="search_res_pages" hidden>
                    <p>Pages</p>
                    <div id="search_r_pages"></div>
                </div>
                <div id="search_res_markets" hidden>
                    <p>Markets</p>
                    <div id="search_r_markets"></div>
                </div>
            </div>
        </div>
        <div class="bottom-nav">
            <div class="bnav-btn" onclick="showSearch()"><i class="fa-solid fa-magnifying-glass"></i></div>
            <div class="bnav-btn" onclick="newPost()"><i class="fa-solid fa-plus"></i></div>
            <div class="bnav-btn" onclick="showRightPanel()"><i class="fa-solid fa-user-group"></i></div>
        </div>
        <?php }?>

        <script>
            function showLeftPanel() {
                const left = document.getElementById('left-panel');
                const right = document.getElementById('right-panel');
                const um = document.getElementById('usermenu');
                if (left.style.transform == "translateX(0px)") {
                    left.style.transform = 'translateX(-100%)';
                } else {
                    left.style.transform = 'translateX(0px)';
                    right.style.transform = 'translateX(100%)';
                    um.style.transform = 'translateX(100%)';
                }
            }
            function showRightPanel() {
                const left = document.getElementById('left-panel');
                const right = document.getElementById('right-panel');
                const um = document.getElementById('usermenu');
                if (right.style.transform == "translateX(0px)") {
                    right.style.transform = 'translateX(100%)';
                } else {
                    right.style.transform = 'translateX(0px)';
                    left.style.transform = 'translateX(-100%)';
                    um.style.transform = 'translateX(100%)';
                }
            }
        </script>

        <div class="image_viewer" id="image_viewer" style="display: none">
            <div class="image_post" id="image_post" <?php if (isMobile() == true) {?>hidden<?php }?>></div>
            <div class="image_box">
                <div class="image_box_close" onclick="showImage(null)"><i class="fa-solid fa-xmark"></i></div>
                <div class="image_frame" id="image_frame" onclick="toggleImageSlider()"></div>
                <div class="image_slider" id="image_slider"></div>
            </div>
        </div>

        <script>
            function showImage(x) {
                const image_viewer = document.getElementById('image_viewer');
                const image_post = document.getElementById('image_post');
                const image_frame = document.getElementById('image_frame');
                const image_slider = document.getElementById('image_slider');

                image_slider.style.display = "flex";

                if (image_viewer.style.display === "flex") {
                    image_viewer.style.display = "none";
                } else {
                    $.ajax({
                        url: 'assets/post_full.php',
                        type: "POST",
                        data: {
                            post : x
                        }
                    }).done(function(postData) {
                        image_post.innerHTML = postData;
                        image_viewer.style.display = "flex";
                    });

                    $.ajax({
                        url: 'assets/post_images.php',
                        type: "POST",
                        data: {
                            post : x
                        }
                    }).done(function(images) {
                        let sliderHTML = "";
                        images.forEach((image, index) => {
                            const isActive = index === 0 ? "active" : ""; // Set first image as active by default
                            sliderHTML += `<img src="${image.file_url}" class="${isActive} image_slider_item" onclick="changeImage(${index},${x})">`;
                        });

                        image_frame.innerHTML = `<img src="${images[0].file_url}" id="mainImage">`;
                        image_slider.innerHTML = sliderHTML;
                    });
                }
            }
            function toggleImageSlider() {
                const image_slider = document.getElementById('image_slider');
                if (image_slider.style.display == "none") {
                    image_slider.style.display = "flex";
                } else {
                    image_slider.style.display = "none";
                }
            }
            
            function changeImage(index,x) {
                $.ajax({
                    url: 'assets/post_images.php',
                    type: "POST",
                    data: {
                        post : x
                    }
                }).done(function(response) {
                    const mainImage = document.getElementById('mainImage');
                    mainImage.src = response[index].file_url;

                    const image_slider = document.getElementById("image_slider");
                    const images = image_slider.getElementsByClassName("image_slider_item");
                    for (let i = 0; i < images.length; i++) {
                        images[i].classList.remove("active");
                    }
                    images[index].classList.add("active");
                });
            }
        </script>

        <?php }?>

        <?php if (!isset($_SESSION['user'])) {
            if (!isset($_COOKIE['welcomeScreen'])) {
        ?>
        <div id="welcome-screen" onclick="hideWelcome()">
            <div id="welcome-inner">
                <img src="assets/images/logo_faded_clean.png" alt="Skybyn Logo">
                <center>
                    <h3>Welcome to</h3>
                    <h1>Skybyn</h1>
                </center>
            </div>
            <div id="welcome-click"><i class="fa-solid fa-angle-right"></i> Click to continue <i class="fa-solid fa-angle-left"></i></div>
        </div>

        <script>
            window.addEventListener('load', function() {
                const welcomeInner = document.getElementById('welcome-inner');
                const welcomeScreen = document.getElementById('welcome-screen');

                setTimeout(function() {
                    welcomeInner.classList.add('show');
                }, 1000);
            });
            function hideWelcome() {
                const welcomeScreen = document.getElementById('welcome-screen');
                const login_email = document.getElementById('login-email');
                welcomeScreen.remove();
                login_email.focus();
            }

            function toggleFullScreen() {
                if (!document.fullscreenElement) {
                    document.documentElement.requestFullscreen();
                } else {
                    if (document.exitFullscreen) {
                    document.exitFullscreen();
                    }
                }
            }
        </script>
        <?php }}?>
        <?php if (isset($_SESSION['user'])) {?>
        <script>
            function checkRegistrationDuration(registrationTimestamp, unlockDuration) {
                const currentTimestamp = Math.floor(Date.now() / 1000);
                const duration = currentTimestamp - registrationTimestamp;

                if (duration >= unlockDuration) {
                    return true;
                } else {
                    return false;
                }
            }

            setInterval(() => {
                const registrationTimestamp = <?=$reg_date?>;
                const tenDays = <?=calcTime("days")?> * 10;
                const month = <?=calcTime("months")?>;
                
                if (checkRegistrationDuration(registrationTimestamp, tenDays)) {
                    
                }
                if (checkRegistrationDuration(registrationTimestamp, month)) {
                    
                }
            }, 1000);
        </script>
        <?php }?>

        <script>
            function timeAgo(timestamp) {
                const currentTimestamp = Math.floor(Date.now() / 1000);
                const secondsAgo = currentTimestamp - timestamp;

                if (secondsAgo < 60) {
                    return `${secondsAgo} second${secondsAgo === 1 ? '' : 's'} ago`;
                } else if (secondsAgo < 3600) {
                    const minutesAgo = Math.floor(secondsAgo / 60);
                    return `${minutesAgo} minute${minutesAgo === 1 ? '' : 's'} ago`;
                } else if (secondsAgo < 86400) {
                    const hoursAgo = Math.floor(secondsAgo / 3600);
                    return `${hoursAgo} hour${hoursAgo === 1 ? '' : 's'} ago`;
                } else {
                    const daysAgo = Math.floor(secondsAgo / 86400);
                    return `${daysAgo} day${daysAgo === 1 ? '' : 's'} ago`;
                }
            }

            function checkData() {
                const new_users = document.getElementById('new_users');
                $.ajax({
                    url: 'assets/update.php',
                    type: "POST",
                    data: {
                        
                    }
                }).done(function(response) {
                    const data = JSON.parse(response);

                    for (const key in data) {
                        if (data.hasOwnProperty(key)) {
                            const username = key;
                            const registered = data[key].reg_date;

                            const newUserDiv = document.createElement('div');
                            newUserDiv.classList.add('new_user');
                            const newUserLeftDiv = document.createElement('div');
                            newUserLeftDiv.classList.add('new_user_left');
                            const newUserNameDiv = document.createElement('div');
                            newUserNameDiv.setAttribute('id', 'new_user_name');
                            const newUserTimeDiv = document.createElement('div');
                            newUserTimeDiv.setAttribute('id', 'new_user_time');
                            newUserLeftDiv.appendChild(newUserNameDiv);
                            newUserLeftDiv.appendChild(newUserTimeDiv);
                            const newUserRightDiv = document.createElement('div');
                            newUserRightDiv.classList.add('new_user_right');
                            const icon = document.createElement('i');
                            icon.classList.add('fa-solid', 'fa-circle-user');
                            newUserRightDiv.appendChild(icon);
                            newUserDiv.appendChild(newUserLeftDiv);
                            newUserDiv.appendChild(newUserRightDiv);
                            
                            newUserNameDiv.textContent = `${username}`;
                            newUserTimeDiv.textContent = timeAgo(`${registered}`);
                            new_users.appendChild(newUserDiv);
                            newUserDiv.style.opacity = '1';

                            setTimeout(() => {
                                hideNewUsers();
                            }, 3000);
                        }
                    }
                });
            }
            
            <?php if (skybyn('register') == "1") {
                if (!isset($_SESSION['user'])) {?>
            setTimeout(() => {
                checkData();
            }, 1000);
            <?php }}?>

            function hideNewUsers() {
                const newUserElements = document.querySelectorAll('.new_user');

                function fadeOutAndRemove(element, duration) {
                    let opacity = 1;
                    const interval = 50; // Duration of each step in milliseconds

                    const fadeOutInterval = setInterval(() => {
                        opacity -= interval / duration;
                        element.style.opacity = opacity;

                        if (opacity <= 0) {
                            clearInterval(fadeOutInterval);
                            element.style.display = 'none';
                        }
                    }, interval);
                }

                newUserElements.forEach((element, index) => {
                    setTimeout(() => {
                        fadeOutAndRemove(element, 1000); // Adjust the duration as needed
                    }, index * 2000); // Adjust the delay between elements as needed
                });
            }
        </script>

        <?php if (skybyn('register') == "1") {
        if (!isset($_SESSION['user'])) {?>
        <div class="new_users" id="new_users"></div>
        <?php }}?>