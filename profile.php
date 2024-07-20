<?php include_once "assets/header.php";

$myProfile = false;

if (isset($_GET['u'])) {
    $user_id = substr($_GET['u'], 4);
    
    if ($uid != $user_id) {
        if (getUser('id',$user_id,'username') != "error") {
            if (checkFriendship($uid,$user_id) == "ok") {
                $friends = true;
            } else {
                $friends = false;
            }
        } else {
            ?><meta http-equiv="Refresh" content="url='./'" /><?php
        }
    } else {
        ?><meta http-equiv="Refresh" content="0; url='./profile'" /><?php
    }
} else {
    if (isset($_SESSION['user'])) {
        $user_id = $uid;
        $myProfile = true;
        $friends = true;
    } else {
        ?><meta http-equiv="Refresh" content="0; url='./'" /><?php
    }
}

$PUDRes = $conn->query("SELECT * FROM `users` WHERE `id`='$user_id'");
$PUDRow = $PUDRes->fetch_assoc();
$Pemail = $PUDRow['email'];
$Pusername = $PUDRow['username'];
$Prank = $PUDRow['rank'];
$Pfirst_name = $PUDRow['first_name'];
$Pmiddle_name = $PUDRow['middle_name'];
$Plast_name = $PUDRow['last_name'];
$Pavatar = "./".$PUDRow['avatar'];
$Pwallpaper = "./".$PUDRow['wallpaper'];
$Pwallpaper_margin = $PUDRow['wallpaper_margin'];
$Pcountry = $PUDRow['country'];
$Pip = $PUDRow['ip'];
$Pdarkmode = $PUDRow['darkmode'];
$Pminecraft = $PUDRow['minecraft'];
$Phabbo = $PUDRow['habbo'];
$Pfivem = $PUDRow['fivem'];
$Pverified = $PUDRow['verified'];
$Pprivate = $PUDRow['private'];

if ($Pavatar == "./") {
    $Pavatar = "./assets/images/logo_faded_clean.png";
}

if ($Pwallpaper == "./") {
    $Pwallpaper = "./assets/images/blank.png";
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
                        <?php if (isset($_SESSION['user'])) {?>
                        <div id="friend_actions">
                        <?php if ($myProfile == false) {
                        $checkFriendship = $conn->query("SELECT * FROM `friendship` WHERE `user_id`='$uid' AND `friend_id`='$user_id'");
                        if ($checkFriendship->num_rows == 1) {
                            $friendshipData = $checkFriendship->fetch_assoc();
                            $status = $friendshipData['status'];

                            if ($status == "accepted") {?>
                            <button onclick="friendship('<?= $user_id ?>','unfriend')">
                                <i class="fa-solid fa-user-minus"></i> <span>Unfriend</span>
                            </button>
                            <?php } else if ($status == "sent") {?>
                            <button onclick="friendship('<?= $user_id ?>','cancel')">
                                <i class="fa-solid fa-user-xmark"></i> <span>Cancel friend request</span>
                            </button>
                            <?php } else if ($status == "received") {?>
                            <button onclick="friendship('<?= $user_id ?>','accept')">
                                <i class="fa-solid fa-user-check"></i> <span>Accept</span>
                            </button>
                            <button onclick="friendship('<?= $user_id ?>','ignore')">
                                <i class="fa-solid fa-user-xmark"></i> <span>Ignore</span>
                            </button>
                            <?php } else if ($status == "blocked") {?>
                            <button onclick="friendship('<?= $user_id ?>','unblock')">
                                <i class="fa-solid fa-user-slash"></i> <span>Unblock</span>
                            </button>
                            <?php }} else {?>
                            <button onclick="friendship('<?= $user_id ?>','send')">
                                <i class="fa-solid fa-user-plus"></i> <span>Send friend request</span>
                            </button>
                            <button onclick="friendship('<?= $user_id ?>','block')">
                                <i class="fa-solid fa-user-slash"></i> <span>Block</span>
                            </button>
                            <?php }?>
                            <button class="red" onclick="friendship('<?= $user_id ?>','report')">
                            <i class="fa-solid fa-triangle-exclamation"></i> <span>Report</span>
                            </button>
                        <?php }?>
                        </div>
                        <?php }?>
                    </div>
                    <?php if (isMobile() == false) {?>
                    <hr>
                    <div class="profile-tabs">
                        <b>Groups</b>
                        <?php $groups = $conn->query("SELECT * FROM `group_members` WHERE `user`='$user_id'");
                        while($groupsData = $groups->fetch_assoc()) {
                            $group_id = $groupsData['id'];
                            $myGroups = $conn->query("SELECT * FROM `groups` WHERE `id`='$group_id'");
                            if ($myGroups->num_rows == 1) {
                                $groupData = $myGroups->fetch_assoc();
                                $group_name = $groupData['name'];
                                $group_icon = "./".$groupData['icon'];
                                
                                if ($group_icon == "./") {
                                    $group_icon = "./assets/images/logo.png";
                                }
                                ?>
                                <div class="group">
                                    <div class="group-icon"><img src="<?=$group_icon?>"></div>
                                    <div class="group-name"><?=$group_name?></div>
                                </div>
                                <?php
                            } else {
                                ?>
                                <div class="group">
                                    <div class="group-icon"></div>
                                    <div class="group-name">This group no longer exist</div>
                                </div>
                                <?php
                            }
                        }
                        ?>
                    </div>
                    <?php }?>
                </div>
                <div class="profile-right" id="posts">
                    <?php if ($Pprivate == "0" || $friends == true) {?>
                    <?php $getPosts = mysqli_query($conn, "SELECT * FROM `posts` WHERE `user`='$user_id' ORDER BY `created` DESC LIMIT 5");
                    while($post = mysqli_fetch_assoc($getPosts)) {
                        $post_id = $post['id'];
                        $post_user = $post['user'];
                        $post_content = $post['content'];
                        $post_created = date("d M. y H:i:s", $post['created']);

                        $getComments = mysqli_query($conn, "SELECT * FROM `comments` WHERE `post`='$post_id'");
                        $comments = mysqli_num_rows($getComments);

                        $getPostUser = mysqli_query($conn, "SELECT * FROM `users` WHERE `id`='$post_user'");
                        $postUser = mysqli_fetch_assoc($getPostUser);
                        $post_user_name = $postUser['username'];
                        $post_user_avatar = "./".$postUser['avatar'];
                        if ($post_user_avatar == "./") {
                            $post_user_avatar = "./assets/images/logo_faded_clean.png";
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
                                <div class="post_actions">
                                    <i class="fa-solid fa-ellipsis-vertical"></i>
                                    <div class="post_action_list" hidden>
                                        <div class="post_action" onclick="showImage(<?=$post_id?>)">
                                            <i class="fa-solid fa-magnifying-glass-plus"></i> Show
                                        </div>
                                        <?php if ($post_user == $uid || $rank > 0) {?>
                                        <div class="post_action" onclick="editPost(<?=$post_id?>)">
                                            <i class="fa-solid fa-pen-to-square"></i> Edit
                                        </div>
                                        <div class="post_action" onclick="deletePost(<?=$post_id?>)">
                                            <i class="fa-solid fa-trash"></i> Delete
                                        </div>
                                        <?php }?>
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
                                Read more
                            </div>
                            <?php }?>
                            <div class="post_comments">
                                <div class="post_comment_count"><?=$comments?><i class="fa-solid fa-comments"></i></div>
                                <div class="post_comment">
                                    <div class="post_comment_user">
                                        <div class="post_comment_user_avatar">
                                            <img src="<?=$avatar?>">
                                        </div>
                                        <span><?=$username?></span>
                                    </div>
                                    <div class="post_comment_content"><input type="text" id="pc_<?=$post_id?>" onkeydown="hitEnter(this,<?=$post_id?>)" placeholder="Write a comment"></div>
                                    <div class="post_comment_actions">
                                        <div class="btn" onclick="sendComment(<?=$post_id?>)"><i class="fa-solid fa-paper-plane"></i></div>
                                    </div>
                                </div>
                                <div id="post_comments_<?=$post_id?>">
                                    <?php $getComment = $conn->query("SELECT * FROM `comments` WHERE `post`='$post_id' ORDER BY `date` ASC");
                                    if ($getComment->num_rows > 0) {
                                        while($commentData = $getComment->fetch_assoc()) {
                                            $commentID = $commentData['id'];
                                            $commentUser = $commentData['user'];
                                            $commentUsername = getUser("id",$commentData['user'],"username");
                                            $commentAvatar = getUser("id",$commentData['user'],"avatar");
                                            $commentText = fixEmojis(nl2br(cleanUrls($commentData['content'])), 1);
                                            
                                            if ($commentAvatar == "") {
                                                $commentAvatar = "./assets/images/logo_faded_clean.png";
                                            }?>
                                    <div class="post_comment" id="comment_<?=$commentID?>">
                                        <div class="post_comment_user">
                                            <div class="post_comment_user_avatar">
                                                <img src="<?=$commentAvatar?>">
                                            </div>
                                            <span><?=$commentUsername?></span>
                                        </div>
                                        <div class="post_comment_content"><?=$commentText?></div>
                                        <div class="post_comment_actions">
                                            <?php if ($rank > 0 || $commentUser == $uid) {?>
                                            <div class="btn" onclick="delComment(<?=$commentID?>)"><i class="fa-solid fa-trash"></i></div>
                                            <?php }?>
                                        </div>
                                    </div>
                                    <?php }}?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php }} else {?>
                    <div class="post" id="post_<?=$post_id?>">
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
                <input type="file" name="avatar" id="setavatar" hidden>
                <h3>Update avatar</h3>
                <label for="setavatar">Browse</label>
                <br><br>
                <input type="submit" name="update_avatar" value="Update">
            </form>
        </div>

        <div class="changeWallpaper" hidden>
            <i class="fa-solid fa-xmark" onclick="changeWallpaper()"></i>
            <form method="post" enctype="multipart/form-data">
                <input type="file" name="wallpaper" id="setwallpaper" onchange="preViewWallpaper(this)" hidden>
                <h3>Set new wallpaper</h3>
                <label for="setwallpaper">Browse</label>
                <br><br>
                <input type="submit" name="update_wallpaper" value="Update">
            </form>
        </div>

        <script>
            function preViewWallpaper(x) {
                const wallpaper = document.getElementById('wallpaper');

            }
            function stickyProfile() {
                const avatar = document.getElementById('profile-left');
                avatar.style.height = window.innerHeight - 125 +"px";
            }

            function friendship(friend,action) {
                const actions = document.getElementById('friend_action');
                $.ajax({
                    url: 'assets/friendship.php',
                    type: "POST",
                    data: {
                        friend : friend,
                        action : action
                    }
                }).done(function(response) {
                    window.location.reload();
                });
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
        <?php if (isMobile() == false) {?>
        <script>stickyProfile();</script>
        <?php }?>
        <?php }?>
    </body>
</html>