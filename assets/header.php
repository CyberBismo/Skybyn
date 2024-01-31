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
        <link rel="apple-touch-icon" sizes="180x180" href="/assets/images/logo_fav.png">
        <link rel="icon" type="image/x-icon" href="/assets/images/logo_fav.png">
        <link href="/fontawe/css/all.css" rel="stylesheet">
        <script src="/assets/js/jquery.min.js"></script>
        <script src="/assets/js/scripts.js"></script>
        <script src="assets/js/welcome.js"></script>
        <?php if (isMobile() == true) {?>
        <script src="assets/js/small_screen.js"></script>
        <?php } else {?>
        <script src="assets/js/big_screen.js"></script>
        <?php }?>
        <?php if (isset($_SESSION['user'])) {?>
        <script src="assets/js/scripts_logged.js"></script>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                var img = document.getElementsByClassName('pixelated-image');
                img.onload = function() {
                    img.style.imageRendering = 'auto';
                };
            });
        </script>
        <?php }?>
        <?php include_once "style.php"?>
    </head>
    <body>
        <div class="header" id="header">
            <?php if (isset($_SESSION['user'])) {
            if (isMobile() == false) {
                include_once("assets/logo.php");
            }} else {
                include_once("assets/logo.php");
            }?>
            <?php if (isset($_SESSION['user'])) {
                if (isMobile() == false) {?>
            <div class="new_post_button" id="new_post_btn" onclick="newPost()">Anything new?</div>
            <?php }?>
            <?php }?>
            <?php if (!isset($_SESSION['user'])) {
                if (isMobile() == false) {?>
            <div class="login">
                <?php include_once("/assets/forms/login.php");?>
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
                    <img src="<?=$avatar?>" onclick="window.location.href='./profile'" class="pixelated-image">
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
                <div class="create_post_actions_top">
                    <img src="<?=$avatar?>" class="pixelated-image">
                    <textarea type="text" placeholder="What's on your mind?" id="new_post_input" oninput="adjustTextareaHeight()" onkeydown="checkEnter()" onkeyup="convertEmoji(this.value)"></textarea>
                    <i class="fa-solid fa-paper-plane share" id="create_post_btn" onclick="createPost()"></i>
                </div>
                <div class="new_post_files" id="new_post_files"></div>
                <div class="create_post_actions create_post_actions_bottom">
                    <span>
                        <i class="fa-solid fa-earth-americas"></i>
                        <select id="new_post_public">
                            <option value="0">Private</option>
                            <option value="1" selected>Friends only</option>
                            <option value="2">Public</option>
                        </select>
                    </span>
                    <span style="word-break: break-all">
                        <input type="file" id="image_to_share" accept=".jpg, .jpeg, .gif, .png" multiple hidden onchange="updateFileNameLabel()">
                        <label for="image_to_share"><i class="fa-solid fa-image"></i><span id="image_to_share_text">No image selected</span></label>
                    </span>
                </div>
            </div>
        </div>
        <?php }?>

        <script>
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
            function newPost() {
                const header = document.getElementById('header');
                const mobile_nav_btn = document.getElementById('mobile_new_post');
                const new_post_btn = document.getElementById('new_post_btn');
                const new_post = document.getElementById('new_post');
                const new_post_input = document.getElementById('new_post_input');
                const newPostIcon = document.getElementById('newPostIcon');

                if (new_post.style.display == "block") {
                    new_post.style.display = "none";
                    <?php if (isMobile() == true) {?>
                    newPostIcon.classList.add("fa-plus");
                    newPostIcon.classList.remove("fa-xmark");
                    mobile_nav_btn.style.transform = "rotate(0deg)";
                    new_post.style.background = "rgba(var(--dark),.2)";
                    header.style.background = "rgba(var(--dark),.2)";
                    <?php } else {?>
                    new_post_btn.innerHTML = "Anything new?";
                    <?php }?>
                } else {
                    new_post.style.display = "block";
                    <?php if (isMobile() == true) {?>
                    newPostIcon.classList.add("fa-xmark");
                    newPostIcon.classList.remove("fa-plus");
                    mobile_nav_btn.style.transform = "rotate(45deg)";
                    new_post.style.background = "rgba(var(--dark),1)";
                    header.style.background = "rgba(var(--dark),1)";
                    <?php } else {?>
                    new_post_btn.innerHTML = "Cancel";
                    <?php }?>
                    new_post_input.focus();
                }
            }
            <?php if (isset($_SESSION['user'])) {?>
            document.addEventListener('click', hideMenus);
            <?php }?>
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
                                <div class="group-icon"><img src="<?= $group_icon ?>" class="pixelated-image"></div>
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
                                <div class="page-icon"><img src="<?=$page_icon?>" class="pixelated-image"></div>
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
                <input type="text" id="searchInput" onkeyup="startSearch(this)" placeholder="Search... (@user | /page | /group)">
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
                                    <div class="friend-avatar"><img src="<?=$friend_avatar?>" class="pixelated-image"></div>
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
                <div class="friend-referral">
                    <h3>Refer a friend</h3>
                    <div class="fr_code" id="frc" <?php if($referral == "error") {?>onclick="genRef()"<?php }?>>
                        <?php if($referral == "error") {?>
                            Generate code
                        <?php } else { echo $referral;}?>
                    </div>
                    <div class="fr_info" onclick="friExpand()">
                        <i class="fa-regular fa-circle-question"></i> What is this? <span id="fri">+</span>
                        <div class="fr_info_text" id="frit"><br>Refer a friend simply works as an invitation. By inviting a friend, you instantly become friends and earn 10 <a href="#">points</a>.</div>
                    </div>
                </div>
            </div>
        </div>

        <?php if (isMobile() == false) {?>
        <div class="left-panel-open" id="lp-open" onclick="showLeftPanel()"><i class="fa-solid fa-chevron-right"></i></div>
        <div class="right-panel-open" id="rp-open" onclick="showRightPanel()"><i class="fa-solid fa-chevron-left"></i></div>
        <?php }?>

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
        <div class="bottom-nav" id="bottom-nav">
            <div class="bnav-btn" onclick="showSearch()"><i class="fa-solid fa-magnifying-glass"></i></div>
            <div class="bnav-btn" onclick="newPost()"><i class="fa-solid fa-plus" id="mobile_new_post"></i></div>
            <div class="bnav-btn" onclick="showRightPanel()"><i class="fa-solid fa-user-group"></i></div>
        </div>
        <?php } else {?>
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
        <?php }?>

        <div class="image_viewer" id="image_viewer" style="display: none">
            <div class="image_post" id="image_post" <?php if (isMobile() == true) {?>hidden<?php }?>></div>
            <div class="image_box">
                <div class="image_box_close" onclick="showImage(null)"><i class="fa-solid fa-xmark"></i></div>
                <div class="image_frame" id="image_frame" onclick="toggleImageSlider()"></div>
                <div class="image_slider" id="image_slider"></div>
            </div>
        </div>

        <script>
            function animatedEffect() {
                const header = document.getElementById('header');
                const lp = document.getElementById('left-panel');
                const rp = document.getElementById('right-panel');
                const bn = document.getElementById('bottom-nav');
                
                <?php if (isMobile() == false) {?>
                header.style.transform = "translateY(0px)";
                if (window.innerWidth < 1240) {
                    lp.style.transform = "translate(-300px)";
                    rp.style.transform = "translate(300px)";
                } else {
                    lp.style.transform = "translate(0px)";
                    rp.style.transform = "translate(0px)";
                }
                bn.style.transform = "translate(-50%, 100px)";
                <?php } else {?>
                header.style.transform = "translate(0, -75px)";
                bn.style.transform = "translate(-50%, 100px)";
                setTimeout(() => {
                    header.style.transform = "translate(0, 0)";
                    bn.style.transform = "translate(-50%, -20px)";
                }, 1000);
                <?php }?>
            }
        </script>
        <?php }?>

        <?php if (!isset($_SESSION['user'])) {
            if (!isset($_COOKIE['welcomeScreen'])) {
        ?>
        <div id="welcome-screen" onclick="hideWelcome()">
            <div id="welcome-inner">
                <img src="assets/images/logo_faded_clean.png" alt="Skybyn Logo" class="cloudZoom">
                <center>
                    <h3>Welcome to</h3>
                    <h1>Skybyn</h1>
                </center>
            </div>
        </div>
        <?php }}?>

        <script>
            <?php if (skybyn('register') == "1") {
                if (!isset($_SESSION['user'])) {?>
            setTimeout(() => {
                checkData();
            }, 1000);
            <?php }}?>
        </script>

        <?php if (skybyn('register') == "1") {
        if (!isset($_SESSION['user'])) {?>
        <div class="new_users" id="new_users"></div>
        <?php }}?>

        <script>
            function hideSidePanels() {
                const lp = document.getElementById('left-panel');
                const rp = document.getElementById('right-panel');
                const lb = document.getElementById('lp-open');
                const rb = document.getElementById('rp-open');
                if (window.innerWidth < 1240) {
                    lp.style.transform = "translateX(-300px)";
                    rp.style.transform = "translateX(300px)";
                    lb.style.transform = "translateX(0px)";
                    rb.style.transform = "translateX(0px)";
                } else {
                    lp.style.transform = "translateX(0px)";
                    rp.style.transform = "translateX(0px)";
                    lb.style.transform = "translateX(-33px)";
                    rb.style.transform = "translateX(33px)";
                }
            }
            <?php if (isMobile() == false) {?>
            hideSidePanels();
            window.addEventListener('resize', hideSidePanels);
            <?php }?>

            function expandPost(x) {
                const p_uploads = document.getElementById('post_u_'+x);
                const p_gallery = document.getElementById('post_g_'+x);
                if (p_uploads.style.maxHeight == p_gallery.clientHeight+"px") {
                    p_uploads.style.maxHeight = "300px";
                } else {
                    p_uploads.style.maxHeight = p_gallery.clientHeight+"px";
                }
            }

            function updateThemeBasedOnSystemSettings() {
                const mediaQuery = window.matchMedia('(prefers-color-scheme: dark)');

                function applyThemeChange(e) {
                    if (e.matches) {
                    } else {
                    }
                }

                // Initial check
                applyThemeChange(mediaQuery);

                // Listen for changes
                mediaQuery.addListener(applyThemeChange);
            }

            // Call the function to apply initial theme and set up listener
            updateThemeBasedOnSystemSettings();
        </script>