<?php include_once "functions.php";

$domend = $_SERVER['HTTP_HOST'];
$extension = substr($domend, -3);
if ($extension == 'com') {
    $domend = '.no';
} elseif ($extension == 'no') {
    $domend = '.com';
}

$devDomain = 'dev.skybyn.no';
if ($domain == $devDomain) {
    $homepage = "https://dev.skybyn$domend/";
} else {
    $homepage = "https://skybyn$domend/";
}

$signup = false;
$beta = false;

if (isset($_SESSION['beta'])) {
    $beta = true;
}

if (isset($_GET['signup'])) {
	if (skybyn('register') == "1" || $beta == true) {
		$signup = true;
	} else {
		$signup = false;
	}
}

if (isset($_GET['betaaccess'])) {
    $code = $_GET['betaaccess'];
    $checkCode = $conn->query("SELECT `key` FROM `beta_access` WHERE `key`='$code'");
    if ($checkCode->num_rows == 1) {
        $_SESSION['beta'] = $code;
        ?><script>window.location.href = "../";</script><?php
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?=skybyn("title")?></title>
        <meta name="description" content="Skybyn - Social Network">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <meta name="mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black">
        <link rel="apple-touch-icon" sizes="180x180" href="../../assets/images/logo_fav.png">
        <link rel="icon" type="image/x-icon" href="../../assets/images/logo_fav.png">
        <link href="../fontawe/css/all.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/lozad@1.0.0/dist/lozad.min.js"></script>
        <script>
            const observer = lozad();
            observer.observe();
        </script>
        <script src = "https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
        <script src="../assets/js/scripts.js"></script>
        <script src="../assets/js/ws.js"></script>
        <?php if (!isset($_COOKIE['welcomeScreen'])) {?>
        <script src="../assets/js/welcome.js"></script>
        <?php }?>
        <?php if (isMobile($userAgent) == true) {?>
        <script src="../assets/js/small_screen.js"></script>
        <?php } else {?>
        <script src="../assets/js/big_screen.js"></script>
        <?php }?>
        <?php if (isset($_SESSION['user'])) {?>
        <script src="../assets/js/comments/updateComments.js"></script>
        <script src="../assets/js/notifications/notis.js"></script>
        <script src="../assets/js/posts/updateFeed.js"></script>
        <script src="../assets/js/scripts_logged.js"></script>
        <script src="../assets/js/chat/message.js"></script>
        <?php }?>
        <?php include_once "style.php"?>
    </head>
    <body>
        <?php if (skybyn("celebration") == "new_year") {?>
        <div class="happy_new_year" id="firework">
            <div class="background"></div>
            <div class="clouds">
                <div class="cloud"></div>
            </div>
            <div class="happy_new_year_text">
                <p id="hny_title">New Year In</p>
                <h1 id="hny_timer">00:00:00</h1>
                <h1 id="hny_year" hidden>2025</h1>
                <h1 id="hny_message" hidden>Happy New Year</h1>
            </div>
        </div>
        <script>
            async function updateTimer() {
                async function getTimeZoneByIP() {
                    try {
                        const response = await fetch('https://ipwhois.app/json/');
                        const data = await response.json();
                        return data.timezone; // Returns the timezone as a string, e.g., "America/New_York"
                    } catch (error) {
                        console.error('Error fetching timezone:', error);
                        return null;
                    }
                }
                let hny_title = document.getElementById('hny_title');
                let hny_timer = document.getElementById('hny_timer');
                let hny_year = document.getElementById('hny_year');
                let hny_msg = document.getElementById('hny_message');

                const timeZone = await getTimeZoneByIP();//Intl.DateTimeFormat().resolvedOptions().timeZone;
                const now = new Date(new Date().toLocaleString("en-US", { timeZone }));
                const newYearDate = new Date(2025, 0, 1, 0, 0, 0);

                const timeDiff = newYearDate - now;

                if (timeDiff > 0) {
                    const hours = String(Math.floor((timeDiff / (1000 * 60 * 60)) % 24)).padStart(2, '0');
                    const minutes = String(Math.floor((timeDiff / (1000 * 60)) % 60)).padStart(2, '0');
                    const seconds = String(Math.floor((timeDiff / 1000) % 60)).padStart(2, '0');

                    hny_timer.innerHTML = `${hours}:${minutes}:${seconds}`;
                } else {
                    hny_title.setAttribute("hidden", "");
                    hny_timer.setAttribute("hidden", "");
                    hny_year.removeAttribute("hidden");
                    hny_msg.removeAttribute("hidden");
                    launchFireworks();
                }
            }

            setInterval(updateTimer, 1000);
            updateTimer();
        </script>
        <?php }?>
        <?php if (skybyn("celebration") == "xmas") {?>
        <div class="xmas"></div>
        <?php }?>
        <?php if (skybyn("celebration") == "easter") {?>
        <div class="easter"></div>
        <?php }?>
        <?php if (skybyn("celebration") == "halloween") {?>
        <div class="halloween"></div>
        <?php }?>

        <div class="background"></div>
        <div class="clouds">
            <div class="cloud"></div>
        </div>

        <?php if ($beta == true) {?>
        <div class="beta_access">
            <span>beta</span>
            <p>As a beta tester, you are obligated for reporting bugs and issues to the developers.</p>
        </div>
        <?php }?>

        <div class="header" id="header">

            <?php // Logo
            if (isMobile($userAgent) == false) {
                include_once("assets/logo.php");
            }
            ?>

            <?php // New post if logged in
            if (isset($_SESSION['user'])) {
                if (isMobile($userAgent) == false) {?>
            <div class="new_post_button" id="new_post_btn" onclick="newPost()">Anything new?</div>
            <?php }?>
            <script>// Display new post button only when the user is on the home page
            const homePage = window.location.href;
            const pages = ['group', 'page', 'search', 'notifications', 'settings', 'profile', 'post'];
            if (!pages.some(page => homePage.includes(page))) {
                if (document.getElementById('new_post_btn')) {
                    const newPostBtn = document.getElementById('new_post_btn');
                    newPostBtn.style.display = 'block';
                }
            } else {
                if (document.getElementById('new_post_btn')) {
                    const newPostBtn = document.getElementById('new_post_btn');
                    newPostBtn.style.display = 'block';
                }
            }
            </script>
            <?php }?>

            <div class="top">
                <?php if (isset($_SESSION['user'])) {?>
                <div class="top-nav">
                    <ul>
                        <?php if (isMobile($userAgent) == true) {?>
                        <li onclick="showSearch()"><i class="fa-solid fa-magnifying-glass"></i></li>
                        <?php } else {?>
                        <li onclick="showNotifications(event)" id="notification">
                            <div class="notification_alert nat"><i class="fa-solid fa-circle-exclamation"></i></div>
                            <i class="fa-solid fa-bell"></i>
                        </li>
                        <?php }?>
                    </ul>
                </div>
                <?php } else {?>
                <div class="top-nav">
                    <ul>
                        <?php if (isMobile($userAgent) == true) {?>
                        <li><i class="fa-solid "></i></li>
                        <?php }?>
                    </ul>
                </div>
                <?php }?>
                <?php if (isset($_SESSION['user'])) {?>
                <div class="user-avatar" id="user_avatar_<?=$uid?>">
                    <?php if (isMobile($userAgent) == true) {?>
                    <img src="../assets/images/logo_faded_clean.png" onclick="window.location.href='./'">
                    <?php } else {?>
                    <img src="<?=$avatar?>" onclick="window.location.href='../profile'">
                    <?php }?>
                </div>
                <?php } else {?>
                <div class="user-avatar">
                    <?php if (isMobile($userAgent) == true) {?>
                    <img src="../assets/images/logo_faded_clean.png" onclick="window.location.href='../../'">
                    <?php }?>
                </div>
                <?php }?>
                <?php if (isset($_SESSION['user'])) {?>
                <div class="user-nav" onclick="showUserMenu(event)">
                    <ul>
                        <li><i class="fa-solid fa-bars"></i></li>
                    </ul>
                </div>
                <?php } else {?>
                <div class="user-nav">
                    <ul>
                        <li><i class="fa-solid "></i></li>
                    </ul>
                </div>
                <?php }?>
            </div>
        </div>

        <?php if (isset($_SESSION['user'])) {?>
        <div class="user-dropdown" id="usermenu">
            <ul>
                <li onclick="window.location.href='../'"><i class="fa-solid fa-house"></i>Home</li>
                <li onclick="window.location.href='../profile'"><i class="fa-solid fa-user"></i></i>My Profile</li>
                <?php if (isset($rank)) {?>
                <?php if ($rank > 0) {?>
                <li class="pet"><i class="fa-solid fa-paw"></i>My Pet</li>
                <?php }?>
                <?php if ($rank > 0) {?>
                <li class="car"><i class="fa-solid fa-car"></i>My Car</li>
                <?php }?>
                <li onclick="window.location.href='../settings'"><i class="fa-solid fa-gears"></i>Settings</li>
                <?php if ($rank > 0) {?>
                <li class="balance"><i class="fa-solid fa-coins"></i><?=$wallet?></li>
                <?php }}?>
                <hr>
                <li onclick="window.location.href='../logout.php'"><i class="fa-solid fa-arrow-right-from-bracket"></i> Logout</li>
            </ul>
        </div>
        <?php }?>

        <?php if (isset($_SESSION['user'])) {?>
        <div class="new_post" id="new_post" hidden>
            <div class="create_post">
                <div class="create_post_actions_top">
                    <div class="create_post_user">
                        <img src="<?=$avatar?>">
                        <?php if (isset($username)) {?>
                        <div><?=$username?></div>
                        <?php }?>
                    </div>
                </div>
                <textarea type="text" placeholder="What's on your mind?" id="new_post_input" oninput="adjustTextareaHeight()" onkeydown="checkEnter()" onkeyup="convertEmoji(this.value)"></textarea>
                <div class="new_post_files" id="new_post_files"></div>
                <div class="create_post_actions create_post_actions_bottom">
                    <!--span>
                        <i class="fa-solid fa-earth-americas"></i>
                        <select id="new_post_public">
                            <option value="0">Private</option>
                            <option value="1" selected>Friends only</option>
                            <option value="2">Public</option>
                        </select>
                    </span-->
                    <span style="word-break: break-all">
                        <input type="file" id="image_to_share" accept="image/*;capture=camera" multiple hidden onchange="updateFileNameLabel()">
                        <label for="image_to_share"><i class="fa-solid fa-image"></i><span id="image_to_share_text">No image selected</span></label>
                    </span>
                    <i class="fa-solid fa-paper-plane share" id="create_post_btn" onclick="createPost()"></i>
                </div>
            </div>
        </div>
        <script>
            function checkEnter() {
                let text = document.getElementById('new_post_input');

                text.addEventListener('keydown', function(event) {
                    if (event.key === "Enter" && !event.shiftKey) {
                        event.preventDefault();
                        createPost();
                    }
                });
            }
        </script>
        <?php }?>

        <script>
        <?php if (isset($_SESSION['user'])) {?>
        function showUserMenu(event) {
            const um = document.getElementById('usermenu');
            const left = document.getElementById('left-panel');
            const right = document.getElementById('right-panel');
            const mSearch = document.getElementById('mobile-search');
            <?php if (isMobile($userAgent) == true) {?>
            if (um.style.transform == "translateX(0px)") {
                um.style.transform = 'translateX(100%)';
            } else {
                um.style.transform = 'translateX(0px)';
                left.style.transform = 'translateX(-100%)';
                right.style.transform = 'translateX(100%)';
                mSearch.style.transform = 'translateY(-155px)';
            }
            <?php } else {?>
            if (event) {
                event.stopPropagation();
                um.style.display = "block";
            }
            <?php }?>
        }
        function showLeftPanel() {
            const left = document.getElementById('left-panel');
            const leftButton = document.getElementById('lp-open');
            const right = document.getElementById('right-panel');
            const rightButton = document.getElementById('rp-open');
            const um = document.getElementById('usermenu');
            if (left.style.transform == "translateX(0px)") {
                um.style.transform = 'translateX(100%)';
                left.style.transform = 'translateX(-100%)';
                right.style.transform = 'translateX(100%)';
                leftButton.style.transform = 'translateX(0px)';
                rightButton.style.transform = 'translateX(0px)';
            } else {
                um.style.transform = 'translateX(100%)';
                left.style.transform = 'translateX(0px)';
                right.style.transform = 'translateX(100%)';
                leftButton.style.transform = 'translateX('+left.clientWidth+'px)';
                rightButton.style.transform = 'translateX(0px)';
            }
        }
        function showRightPanel() {
            const left = document.getElementById('left-panel');
            const leftButton = document.getElementById('lp-open');
            const right = document.getElementById('right-panel');
            const rightButton = document.getElementById('rp-open');
            const um = document.getElementById('usermenu');
            if (right.style.transform == "translateX(0px)") {
                um.style.transform = 'translateX(100%)';
                left.style.transform = 'translateX(-100%)';
                right.style.transform = 'translateX(100%)';
                leftButton.style.transform = 'translateX(0px)';
                rightButton.style.transform = 'translateX(0px)';
            } else {
                um.style.transform = 'translateX(100%)';
                left.style.transform = 'translateX(-100%)';
                right.style.transform = 'translateX(0px)';
                rightButton.style.transform = 'translateX(-'+left.clientWidth+'px)';
                leftButton.style.transform = 'translateX(0px)';
            }
        }
        <?php }?>
        function newPost() {
            const header = document.getElementById('header');
            const mobile_nav_btn = document.getElementById('mobile_new_post');
            const new_post_btn = document.getElementById('new_post_btn');
            const new_post = document.getElementById('new_post');
            const new_post_input = document.getElementById('new_post_input');
            const newPostIcon = document.getElementById('newPostIcon');

            if (new_post.style.display == "block") {
                new_post.style.display = "none";
                <?php if (isMobile($userAgent) == true) {?>
                newPostIcon.classList.add("fa-plus");
                newPostIcon.classList.remove("fa-xmark");
                mobile_nav_btn.style.transform = "rotate(0deg)";
                new_post.style.background = "rgba(var(--dark),.2)";
                header.style.background = "rgba(var(--dark),.2)";
                <?php } else {?>
                new_post_btn.innerHTML = "Anything new?";
                <?php }?>
                new_post_input.innerHTML = "";
            } else {
                new_post.style.display = "block";
                <?php if (isMobile($userAgent) == true) {?>
                newPostIcon.classList.remove("fa-plus");
                newPostIcon.classList.add("fa-xmark");
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
                    <h4 id="noti_win_username"></h4>
                </div>
                <div class="noti-win-head-close" onclick="closeNotiWin()"><i class="fa-solid fa-xmark"></i></div>
            </div>
            <div class="noti-win-body" id="noti_win_text" hidden></div>
            <div class="noti-win-foot" id="noti_win_foot">
                <div class="btn" id="noti_win_foot_profile"><i class="fa-solid fa-circle-user" title="View profile"></i></div>
            </div>
        </div>

        <div class="left-panel" id="left-panel">

            <?php if (isset($rank) && $rank > 3) {?>
            <div class="shortcuts music">
                <h3><i class="fa-solid fa-music"></i><div>Music</div><i class="fa-solid fa-record-vinyl" onclick="window.location.href='./music?upload'" title="Add to music"></i></h3>
                <div id="my-music">
                    <?php
                    $getMusic = $conn->query("SELECT * FROM `music`");
                    if ($getMusic->num_rows > 0) {
                        while($mu_data = $getMusic->fetch_assoc()) {
                            $music_name = $mu_data['title'];
                            ?>
                            <div class="sortcut sortcut-music">
                                <div class="sortcut-icon"><img src=""></div>
                                <div class="sortcut-name"><?=$music_name?></div>
                            </div>
                            <?php
                        }
                    }
                    ?>
                </div>
            </div>
            <?php }?>

            <?php if (isset($rank) && $rank > 3) {?>
            <div class="shortcuts gaming">
                <h3><i class="fa-solid fa-gamepad"></i><div>Games</div><i></i></h3>
                <div id="my-games">
                    <?php
                    $getGames = $conn->query("SELECT * FROM `game_store`");
                    if ($getGames->num_rows > 0) {
                        while($g_data = $getGames->fetch_assoc()) {
                            $game_name = $g_data['name'];
                            ?>
                            <div class="sortcut sortcut-game">
                                <div class="sortcut-icon"><img src=""></div>
                                <div class="sortcut-name"><?=$game_name?></div>
                            </div>
                            <?php
                        }
                    }
                    ?>
                </div>
            </div>
            <?php }?>

            <?php if (isset($rank) && $rank > 3) {?>
            <div class="shortcuts events">
                <h3><i class="fa-solid fa-calendar-days"></i><div>Events</div><i class="fa-regular fa-calendar-plus" onclick="window.location.href='./event?new'" title="Create new event"></i></h3>
                <div id="my-events">
                    <?php
                    $getEvents = $conn->query("SELECT * FROM `events` WHERE `private`='0'");
                    if ($getEvents->num_rows > 0) {
                        while($e_data = $getEvents->fetch_assoc()) {
                            $event_name = $e_data['name'];
                            ?>
                            <div class="sortcut sortcut-event">
                                <div class="sortcut-icon"><img src=""></div>
                                <div class="sortcut-name"><?=$event_name?></div>
                            </div>
                            <?php
                        }
                    }
                    ?>
                </div>
            </div>
            <?php }?>

            <?php if (isset($rank) && $rank > 3) {?>
            <div class="shortcuts groups">
                <h3><i class="fa-solid fa-comments"></i><div>Groups</div><i class="fa-solid fa-comment-medical" onclick="window.location.href='/group?new'" title="Create new group"></i></h3>
                <div id="my-groups">
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
                            <div class="sortcut shortcut-group" onclick="window.location.href='../group?id=<?=$gid?>'">
                                <div class="group-icon"><img src="<?=$group_icon?>"></div>
                                <div class="group-name"><?=$group_name?></div>
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
            <?php }?>

            <?php if (isset($rank) && $rank > 3) {?>
            <div class="shortcuts pages">
                <h3><i class="fa-regular fa-newspaper"></i><div>Pages</div><i class="fa-solid fa-file-circle-plus" onclick="window.location.href='/page?new'" title="Create new page"></i></h3>
                <div id="my-pages">
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
                            <div class="sortcut shortcut-page">
                                <div class="page-icon"><img src="<?=$page_icon?>"></div>
                                <div class="page-name"><?=$page_name?></div>
                            </div>
                            <?php
                        }
                    }
                    ?>
                </div>
            </div>
            <?php }?>

            <?php if (isset($rank) && $rank > 3) {?>
            <div class="shortcuts markets">
                <h3><i class="fa-solid fa-store"></i><div>Markets</div><i class="fa-solid fa-cash-register" onclick="window.location.href='./market?new'" title="Sell on market"></i></h3>
                <div id="my-markets">
                    <?php
                    $getMarkets = $conn->query("SELECT * FROM `markets`");
                    if ($getMarkets->num_rows > 0) {
                        while($m_data = $getMarkets->fetch_assoc()) {
                            $market_name = $m_data['name'];
                            ?>
                            <div class="sortcut sortcut-market">
                                <div class="sortcut-icon"><img src=""></div>
                                <div class="sortcut-name"><?=$market_name?></div>
                            </div>
                            <?php
                        }
                    }
                    ?>
                </div>
            </div>
            <?php }?>

            <?php if (isset($rank) && $rank > 3) {?>
            <div class="shortcuts terminal">
                <h3><i class="fa-solid fa-terminal"></i><div>Admin Terminal</div><i class="fa-solid fa-delete-left" onclick="clearConsole()"></i></h3>
                <span id="console_countdown"></span>
                <div id="console">
                    <?php if (isset($_COOKIE['logged'])) echo '<p id="term_rem">Remember ON</p>'; else echo '<p id="term_rem">Remember OFF</p>';?>
                </div>
            </div>
            <script>
                function clearConsole() {
                    const console = document.getElementById('console');
                    console.innerHTML = "";
                }
            </script>
            <?php }?>

        </div>

        <div class="right-panel" id="right-panel">
            <?php if (isMobile($userAgent) == false) {?>
            <div class="search">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" id="searchInput" onkeyup="startSearch(this)" placeholder="Search...">
            </div>
            <div class="search_result" id="search_result" hidden>
                <div id="search_res_users" hidden>
                    <h3>Users</h3>
                    <div id="search_r_users"></div>
                </div>
                <div id="search_res_groups" hidden>
                    <h3>Groups</h3>
                    <div id="search_r_groups"></div>
                </div>
                <div id="search_res_pages" hidden>
                    <h3>Pages</h3>
                    <div id="search_r_pages"></div>
                </div>
                <div id="search_res_posts" hidden>
                    <h3>Posts</h3>
                    <div id="search_r_posts"></div>
                </div>
            </div>
            <?php }?>
            <div class="friend-list" id="friend_list">
                <h3>Friends</h3>
                <div id="friend-list">
                    <?php
                    $myFriends = $conn->query("SELECT * FROM `friendship` WHERE `user_id`='$uid' AND `status`='friends'");
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
                            ?>
                            <div class="friend">
                                <div class="friend-user">
                                    <div class="friend-avatar"><img src="<?=$friend_avatar?>"></div>
                                    <div class="friend-name"><?=$friend_username?></div>
                                </div>
                                <div class="friend-actions">
                                    <div class="friend-action" onclick="window.location.href='../profile/<?=$friend_username?>'">
                                        <i class="fa-solid fa-user"></i>
                                    </div>
                                    <div class="friend-action" onclick="startMessaging('<?=$uid?>','<?=$fid?>')">
                                        <i class="fa-solid fa-message"></i>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                    }
                    ?>
                </div>
                <div class="friend-referral" id="fr">
                    <div onclick="expandFR()">
                        <span>Refer a friend</span><i class="fa-solid fa-plus"></i>
                    </div>
                    <div class="fr_code" id="frc" <?php if(isset($referral) && $referral == "error") {?>onclick="genRef()"<?php } else {?>onclick="ctc()"<?php }?>>
                        <?php if(isset($referral)) {
                            if($referral == "error") {
                                ?>Generate code<?php
                            } else {
                                echo trim($referral);
                            }
                        }?>
                    </div>
                    <div class="fr_info" onclick="friExpand()">
                        <i class="fa-regular fa-circle-question"></i> What is this? <span id="fri">+</span>
                        <div class="fr_info_text" id="frit"><br>Refer a friend simply works as an invitation. By inviting a friend, you instantly become friends and earn 10 <a href="#">points</a>.</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="message-container" id="msg_con">
            <?php if (isMobile($userAgent)) {?>
            <div class="icon" onclick="openMessages()"><i class="fa-regular fa-message"></i></div>
            <?php }?>
            <?php if (isset($_SESSION['user'])) { if (getuser("id",$_SESSION['user'],"rank") > 3) {include_once './assets/design/chat_popup.php';}}?>
            <?php
            $checkActiveChats = $conn->query("SELECT * FROM `active_chats` WHERE `user`='$uid'");
            if ($checkActiveChats->num_rows > 0) {
                while ($chatData = $checkActiveChats->fetch_assoc()) {
                    $friend = $chatData['friend'];
                    $open = $chatData['open'];
                    $getFriendData = $conn->query("SELECT * FROM `users` WHERE `id`='$friend'");
                    $friendData = $getFriendData->fetch_assoc();
                    $friend_username = $friendData['username'];
                    $friend_avatar = "./".$friendData['avatar'];
                    if ($friend_avatar == "./") {
                        $friend_avatar = "./assets/images/logo_faded_clean.png";
                    }
                    if ($open == "1") {
                        $open = " maximized";
                        $icon = "fa-chevron-down";
                    } else {
                        $open = "";
                        $icon = "fa-chevron-up";
                    }
                ?>
                <div class="message-box-icon" onclick="showChat('<?=$friend?>')">
                    <img src="<?=$friend_avatar?>">
                </div>
                <div class="message-box<?=$open?>" id="message_box_<?=$friend?>">
                    <div class="message-header">
                        <div class="message-user" onclick="maximizeMessageBox('<?=$friend?>')">
                            <img src="<?=$friend_avatar?>" id="msg_user_avatar_<?=$friend?>">
                            <span id="msg_user_name_<?=$friend?>"><?=$friend_username?></span>
                        </div>
                        <div class="message-actions">
                            <?php if (isMobile($userAgent) == false) {?>
                            <div class="message-min" onclick="maximizeMessageBox('<?=$friend?>')"><i class="fa-solid <?=$icon?>" id="msg_min_<?=$friend?>"></i></div>
                            <div class="message-close" onclick="closeMessageBox('<?=$friend?>')"><i class="fa-solid fa-xmark"></i></div>
                            <?php } else {?>
                                <div class="message-close" onclick="closeMessages()"><i class="fa-solid fa-xmark"></i></div>
                            <?php }?>
                        </div>
                    </div>
                    <div class="message-body" id="message_body_<?=$friend?>">
                        <?php
                        $getMessages = $conn->query("SELECT * FROM `messages` WHERE `from`='$uid' AND `to`='$friend' OR `from`='$friend' AND `to`='$uid' ORDER BY `date` ASC");
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

        <?php if (isMobile($userAgent) == false) {?>
        <div class="left-panel-open" id="lp-open" onclick="showLeftPanel()"><i class="fa-solid fa-chevron-right"></i></div>
        <div class="right-panel-open" id="rp-open" onclick="showRightPanel()"><i class="fa-solid fa-chevron-left"></i></div>
        <?php }?>

        <?php if (isMobile($userAgent) == true) {?>
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
            <div class="bnav-btn" onclick="showLeftPanel()"><i class="fa-solid fa-bookmark"></i></div>
            <div class="bnav-btn" onclick="newPost()" id="mobile_new_post"><i class="fa-solid fa-plus"></i></div>
            <div class="bnav-btn" onclick="showRightPanel()"><i class="fa-solid fa-user-group"></i></div>
        </div>

        <div class="noti_menu" onclick="showNotifications()">
            <div class="notification_alert nab"><i class="fa-solid fa-circle-exclamation"></i></div>
            <i class="fa-solid fa-bell"></i>
        </div>

        <?php }?>

        <div class="image_viewer" id="image_viewer" style="display: none">
            <div class="image_post" id="image_post" <?php if (isMobile($userAgent) == true) {?>hidden<?php }?>></div>
            <div class="image_box">
                <div class="image_box_close" onclick="showImage(null)"><i id="newPostIcon" class="fa-solid fa-xmark"></i></div>
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
            
            <?php if (isMobile($userAgent) == false) {?>
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

        <div id="welcome-screen" onclick="hideWelcome();requestNotificationPermission()">
            <div class="clouds" id="welcome-clouds"></div>
            <div id="welcome-inner">
                <img src="../assets/images/logo_faded_clean.png" alt="Skybyn Logo" class="cloudZoom">
                <center>
                    <h3>Welcome to</h3>
                    <h1>Skybyn</h1>
                </center>
            </div>
            <p id="welcome-click">Click to continue</p>
        </div>

        <?php if (isset($_COOKIE['welcomeScreen'])) {?>
        <script>
            let welcomeScreen = document.getElementById('welcome-screen');
            welcomeScreen.style.display = "none";
        </script>
        <?php }?>

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
        <?php if (isset($_SESSION['user'])) {?>
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
        <?php if (isMobile($userAgent) == false) {?>
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
        <?php }?>
        </script>