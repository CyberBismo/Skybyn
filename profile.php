<?php include_once "./assets/header.php";

$loggedIn = false;
$myProfile = false;
$friends = false;
$rank = 0;

if (isset($_SESSION['user'])) {
    $uid = $_SESSION['user'];
    $loggedIn = true;
    $rank = getUser('id',$uid,'rank');
}

if (isset($_GET['user'])) {
    $user_id = getUser('username',$_GET['user'],'id');
    if ($user_id != "error") {
        if (isset($_SESSION['user'])) {
            if (checkFriendship($uid,$user_id) == "ok") {
                $friends = true;
            }
        }
    } else {
        return false;
    }
} else {
    if (isset($_SESSION['user'])) {
        $user_id = $uid;
        $myProfile = true;
    } else {
        ?><script>window.location.href = '../';</script><?php
        return false;
    }
}

if (isset($user_id)) {
    $PUDRes = $conn->query("SELECT * FROM `users` WHERE `id`='$user_id'");
    $PUDRow = $PUDRes->fetch_assoc();
    $Pemail = $PUDRow['email'];
    $Pusername = $PUDRow['username'];
    $Prank = $PUDRow['rank'];
    $Pfirst_name = $PUDRow['first_name'];
    $Pmiddle_name = $PUDRow['middle_name'];
    $Plast_name = $PUDRow['last_name'];
    $Pavatar = "../".$PUDRow['avatar'];
    $Pwallpaper = "../".$PUDRow['wallpaper'];
    $Pwallpaper_margin = $PUDRow['wallpaper_margin'];
    $Pcountry = $PUDRow['country'];
    $Pverified = $PUDRow['verified'];
    $Pprivate = $PUDRow['private'];
    $Prank = $PUDRow['rank'];

    if ($Pavatar == "../") {
        $Pavatar = "../assets/images/logo_faded_clean.png";
    }

    if ($Pwallpaper == "../") {
        $Pwallpaper = "../assets/images/blank.png";
    }

    $Pavatar_bg = "background: black";
    ?>
        <div class="page-container">
            <div class="profile-wallpaper" id="wallpaper">
                <img src="<?=$Pwallpaper?>">
            </div>
            <div class="profile">
                <div class="profile-left" id="profile-left">
                    <?php if ($myProfile == true) {?>
                    <i class="fa-regular fa-pen-to-square" onclick="changeWallpaper()"></i>
                    <?php }?>
                    <div class="profile-left-user">
                        <div class="avatar" style="<?=$Pavatar_bg?>" id="avatar">
                            <img src="<?=$Pavatar?>">
                        </div>
                        <?php if ($myProfile == true) {?>
                        <i class="fa-regular fa-pen-to-square" onclick="changeAvatar()"></i>
                        <?php }?>
                        <div class="username">
                            <?=$Pusername?>
                            <span>@<?=$Pusername?></span>
                        </div>
                    </div>
                    <div class="profile-btns">
                        <?php if ($Pprivate == "0" || $rank > 3) {?>
                        <?php if (isset($_SESSION['user'])): ?>
                            <?php if (!$myProfile): ?>
                                <?php if ($friends) {?>
                                <button onclick="startMessaging('<?= $uid ?>','<?= $user_id ?>')">
                                    <i class="fa-solid fa-message"></i> <span>Chat</span>
                                </button>
                                <?php 
                                    $checkFriendship = $conn->query("SELECT * FROM `friendship` WHERE `user_id`='$uid' AND `friend_id`='$user_id'");
                                    $status = $checkFriendship->num_rows ? $checkFriendship->fetch_assoc()['status'] : null; 
                                ?>
                                <div id="friend_actions">
                                    <?php switch ($status):
                                        case "friends": ?>
                                            <button onclick="friendAction('<?= $user_id ?>', 'unfriend')">
                                                <i class="fa-solid fa-user-minus"></i> <span>Unfriend</span>
                                            </button>
                                            <?php break;
                                        case "sent": ?>
                                            <button onclick="friendAction('<?= $user_id ?>', 'cancel')">
                                                <i class="fa-solid fa-user-xmark"></i> <span>Cancel friend request</span>
                                            </button>
                                            <?php break;
                                        case "received": ?>
                                            <button class="green" onclick="friendAction('<?= $user_id ?>', 'accept')">
                                                <i class="fa-solid fa-user-check"></i> <span>Accept</span>
                                            </button>
                                            <button class="yellow" onclick="friendAction('<?= $user_id ?>', 'ignore')">
                                                <i class="fa-solid fa-user-xmark"></i> <span>Ignore</span>
                                            </button>
                                            <?php break;
                                        case "blocked": ?>
                                            <button onclick="friendAction('<?= $user_id ?>', 'unblock')">
                                                <i class="fa-solid fa-user-slash"></i> <span>Unblock</span>
                                            </button>
                                            <?php break;
                                        default: ?>
                                            <button class="blue" onclick="friendAction('<?= $user_id ?>', 'send')">
                                                <i class="fa-solid fa-user-plus"></i> <span>Send friend request</span>
                                            </button>
                                    <?php endswitch; ?>
                                    <button class="red" onclick="friendAction('<?= $user_id ?>', 'block')">
                                        <i class="fa-solid fa-user-slash"></i> <span>Block</span>
                                    </button>
                                    <button class="orange" onclick="friendAction('<?= $user_id ?>', 'report')">
                                        <i class="fa-solid fa-triangle-exclamation"></i> <span>Report</span>
                                    </button>
                                </div>
                                <?php }?>
                            <?php endif; ?>
                        <?php endif; ?>
                        <?php } else {?>
                        <?php }?>
                    </div>
                    <?php if ($Pprivate == "0" || $rank > 3) {?>
                    <?php if (isMobile($userAgent) == false) {?>
                    <hr>
                    <div class="profile-tabs">
                        <b><?=$Pusername?>'s Groups</b>
                        <?php $groups = $conn->query("SELECT * FROM `group_members` WHERE `user`='$user_id'");
                        while($groupsData = $groups->fetch_assoc()) {
                            $group_id = $groupsData['id'];
                            $myGroups = $conn->query("SELECT * FROM `groups` WHERE `id`='$group_id'");
                            if ($myGroups->num_rows == 1) {
                                $groupData = $myGroups->fetch_assoc();
                                $group_name = $groupData['name'];
                                $group_icon = "../".$groupData['icon'];
                                
                                if ($group_icon == "../") {
                                    $group_icon = "../assets/images/logo.png";
                                }
                                ?>
                                <div class="group">
                                    <div class="group-icon"><img src="<?=$group_icon?>"></div>
                                    <div class="group-name"><?=$group_name?></div>
                                </div>
                                <?php
                            }
                        }
                        ?>
                    </div>
                    <?php }?>
                    <?php }?>
                </div>
                <div class="profile-right" id="posts">
                    <?php if ($myProfile == true || $Pprivate == "0" || $friends == true || $rank > 3) {?>
                    <?php $getPosts = mysqli_query($conn, "SELECT * FROM `posts` WHERE `user`='$user_id' ORDER BY `created` DESC LIMIT 5");
                    while($post = mysqli_fetch_assoc($getPosts)) {
                        $post_id = $post['id'];
                        $post_user = $post['user'];
                        $post_content = html_entity_decode($post['content'], ENT_QUOTES | ENT_HTML5, 'UTF-8');
                        $post_created = date("d M. y H:i:s", $post['created']);

                        $getComments = mysqli_query($conn, "SELECT * FROM `comments` WHERE `post`='$post_id'");
                        $comments = mysqli_num_rows($getComments);

                        $getPostUser = mysqli_query($conn, "SELECT * FROM `users` WHERE `id`='$post_user'");
                        $postUser = mysqli_fetch_assoc($getPostUser);
                        $post_user_name = $postUser['username'];
                        $post_user_avatar = "../".$postUser['avatar'];
                        if ($post_user_avatar == "../") {
                            $post_user_avatar = "../assets/images/logo_faded_clean.png";
                        }
                        
                        $post_video = convertVideo($post_content);
                        $post_content_res = fixEmojis(nl2br(cleanUrls($post_content)), 1);
                    ?>
                    <div class="post" id="post_<?=$post_id?>">
                        <div class="post_body">
                            <div class="post_header">
                                <div class="post_details">
                                    <div class="post_user">
                                        <div class="post_user_image" onclick="window.location.href='./profile?u=<?=$post_user_name?>'">
                                            <img src="<?=$post_user_avatar?>">
                                        </div>
                                        <div class="post_user_name"><?=$post_user_name?></div>
                                    </div>
                                    <div class="post_date"><?=$post_created?></div>
                                </div>
                                <div class="post_actions" onclick="showPostActions(<?=$post_id?>)">
                                    <i class="fa-solid fa-ellipsis-vertical"></i>
                                    <div class="post_action_list" id="pal_<?=$post_id?>" hidden>
                                        <?php if (isset($_SESSION['user'])) {if ($post_user == $uid || getUser('id',$_SESSION['user'],'rank') > 0) {?>
                                        <div class="post_action" onclick="editPost(<?=$post_id?>)">
                                            <i class="fa-solid fa-pen-to-square"></i><span>Edit</span>
                                        </div>
                                        <div class="post_action" onclick="deletePost(<?=$post_id?>)">
                                            <i class="fa-solid fa-trash"></i><span>Delete</span>
                                        </div>
                                        <?php }}?>
                                    </div>
                                </div>
                            </div>
                            <div class="post_content" id="post_c_<?=$post_id?>">
                                <?=$post_content_res?>
                            </div>
                            <?php if (!empty($post_video)) {?>
                            <div class="post_links">
                                <?=$post_video?>
                            </div>
                            <?php }?>
                            <?php if (!empty($post_links)) { ?>
                            <div class="link_preview">
                                <?php for ($i = 0; $i < count($post_links); $i++) {
                                    if ($i <= count($post_links)) {
                                        if (strpos($post_links[$i], "http") === false) {
                                            $post_links[$i] = "http://".$post_links[$i];
                                        }
                                        $urlData = getLinkData($post_links[$i]);
                                        $urlRestricted = $urlData['restricted'];
                                        $urlLogo = $urlData['favicon'];
                                        $urlTitle = $urlData['title'];
                                        $urlDescription = $urlData['description'];

                                        if ($urlRestricted == 0) {
                                    ?>
                                    <div class="post_link_preview">
                                        <div class="post_link_preview_image">
                                            <img src="<?=$urlLogo?>" alt="">
                                        </div>
                                        <div class="post_link_preview_info">
                                            <div class="post_link_preview_title"><?=$urlTitle?></div>
                                            <div class="post_link_preview_description"><?=$urlDescription?></div>
                                        </div>
                                    </div>
                                    <?php }
                                    }
                                }?>
                            </div>
                            <?php }?>
                            <?php $getUploads = $conn->query("SELECT * FROM `uploads` WHERE `post`='$post_id'");
                            if ($getUploads->num_rows > 0) {?>
                            <div class="post_uploads" id="post_u_<?=$post_id?>">
                                <div class="post_gallery" id="post_g_<?=$post_id?>">
                                    <?php while($upload = $getUploads->fetch_assoc()) {
                                        $file = $upload['file_url'];?>
                                    <img src="<?=$file?>" onclick="showImage(<?=$post_id?>)">
                                <?php }?>
                                </div>
                            </div>
                            <div class="post_expand" id="post_expand" onclick="expandPost(<?=$post_id?>)">
                                Show more
                            </div>
                            <?php }?>
                            <div class="post_comments">
                                <div class="post_comment_count"><div id="comments_count_<?=$post_id?>"><?=$comments?></div><i class="fa-solid fa-message"></i></div>
                                <?php if ($loggedIn) {?>
                                <div class="post_comment_new">
                                    <div class="post_comment_new_content">
                                        <input type="text" id="pc_<?=$post_id?>" onkeydown="hitEnter(this,<?=$post_id?>)" placeholder="Write a comment <?php if(isset($username)) {echo $username;}?>">
                                    </div>
                                    <div class="post_comment_new_actions">
                                        <div class="btn" onclick="sendComment(<?=$post_id?>)"><i class="fa-solid fa-paper-plane"></i></div>
                                    </div>
                                </div>
                                <?php }?>
                                <div id="post_comments_<?=$post_id?>">
                                    <?php $getComment = $conn->query("SELECT * FROM `comments` WHERE `post`='$post_id' ORDER BY `date` DESC");
                                    if ($getComment->num_rows > 0) {
                                        while($commentData = $getComment->fetch_assoc()) {
                                            $commentID = $commentData['id'];
                                            $commentUser = $commentData['user'];
                                            $commentUsername = getUser("id",$commentData['user'],"username");
                                            $commentAvatar = getUser("id",$commentData['user'],"avatar");
                                            $commentText = fixEmojis(nl2br(cleanUrls(html_entity_decode($commentData['content'], ENT_QUOTES | ENT_HTML5, 'UTF-8'))), 1);
                                            
                                            if ($commentAvatar == "") {
                                                $commentAvatar = "../assets/images/logo_faded_clean.png";
                                            }

                                            if ($commentUser == $user_id) {
                                                $myComment = " me";
                                            } else {
                                                $myComment = "";
                                            }
                                            ?>
                                    <div class="post_comment<?=$myComment?>" id="comment_<?=$commentID?>">
                                        <div class="post_comment_user">
                                            <div class="post_comment_user_info">
                                                <div class="post_comment_user_avatar">
                                                    <img src="<?=$commentAvatar?>">
                                                </div>
                                                <span><?=$commentUsername?></span>
                                            </div>
                                            <div class="post_comment_user_actions">
                                                <?php if (isset($_SESSION['user'])) {
                                                    $rank = getUser("id",$_SESSION['user'],"rank");
                                                    if ($rank > 0 || $commentUser == $uid) {?>
                                                <div class="btn" onclick="delComment(<?=$commentID?>)"><i class="fa-solid fa-trash"></i></div>
                                                <?php }} else {?>
                                                <div class="btn"></div>
                                                <?php }?>
                                            </div>
                                        </div>
                                        <div class="post_comment_content"><?=$commentText?></div>
                                    </div>
                                    <?php }}?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php }} else {?>
                    <div class="post">
                        <div class="post_body">
                            <div class="post_content">
                                This profile is private
                            </div>
                        </div>
                    </div>
                    <?php }?>
                </div>
            </div>
        </div>

        <?php if ($myProfile == true) {?>
        <div class="changeAvatar" hidden>
            <i class="fa-solid fa-xmark" onclick="changeAvatar()"></i>
            <form method="post" enctype="multipart/form-data">
                <h3>Change avatar</h3>
                <img src="<?=$Pavatar?>" id="previewavatar">
                <div class="changeBtns">
                    <input type="file" name="avatar" id="setavatar" onchange="preViewAvatar(this)">
                    <input type="submit" name="update_avatar" value="Update">
                </div>
            </form>
        </div>

        <div class="changeWallpaper" hidden>
            <i class="fa-solid fa-xmark" onclick="changeWallpaper()"></i>
            <form method="post" enctype="multipart/form-data">
                <h3>Change wallpaper</h3>
                <img src="<?=$Pwallpaper?>" id="previewwallpaper">
                <div class="changeBtns">
                    <input type="file" name="wallpaper" id="setwallpaper" onchange="preViewWallpaper(this)">
                    <input type="submit" name="update_wallpaper" value="Update">
                </div>
            </form>
        </div>

        <script>
            function preViewAvatar(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        document.getElementById('previewavatar').src = e.target.result;
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            }
            function preViewWallpaper(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        document.getElementById('previewwallpaper').src = e.target.result;
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            }
            function stickyProfile() {
                const avatar = document.getElementById('profile-left');
                avatar.style.height = window.innerHeight - 125 +"px";
            }

            function avatarSize() {
                document.getElementById('avatar').style.width = window.innerWidth+"px";
            }
            //window.addEventListener("resize", avatarSize);

            function changeWallpaper() {
                const changeWallpaperElements = document.getElementsByClassName("changeWallpaper");
                const changeAvatarElements = document.getElementsByClassName("changeAvatar");

                for (let i = 0; i < changeWallpaperElements.length; i++) {
                    const element = changeWallpaperElements[i];

                    if (element.hasAttribute("hidden")) {
                        element.removeAttribute("hidden");
                    } else {
                        element.setAttribute("hidden", "");
                    }
                }
                for (let i = 0; i < changeAvatarElements.length; i++) {
                    const element = changeAvatarElements[i];

                    element.setAttribute("hidden", "");
                }
            }

            function changeAvatar() {
                const changeWallpaperElements = document.getElementsByClassName("changeWallpaper");
                const changeAvatarElements = document.getElementsByClassName("changeAvatar");

                for (let i = 0; i < changeAvatarElements.length; i++) {
                    const element = changeAvatarElements[i];

                    if (element.hasAttribute("hidden")) {
                        element.removeAttribute("hidden");
                    } else {
                        element.setAttribute("hidden", "");
                    }
                }
                for (let i = 0; i < changeWallpaperElements.length; i++) {
                    const element = changeWallpaperElements[i];

                    element.setAttribute("hidden", "");
                }
            }
        </script>
        <?php if (isMobile($userAgent) == false) {?>
        <script>stickyProfile();</script>
        <?php }
    }
}?>