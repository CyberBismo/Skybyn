<?php include_once "./assets/header.php";

if ($devDomain == true) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

$unknownUser = false;
$myProfile = false;
$logged_in = false;

$rProfile = false; # Restricted

if (isset($uid) && $uid != "") {
    $logged_in = true;
}

// Check if user is visiting someone else's profile
if (isset($_GET['user']) && $_GET['user'] != "") {
    $user = $_GET['user'];
    if ($user == $username) {
        ?><script>window.location.href = "./profile";</script><?php
    } else {
        $user_id = getUser("username",$user,"id");
        $checkUserID = $conn->query("SELECT * FROM `users` WHERE `id`='$user_id'");
        if ($checkUserID->num_rows == 1) {
            if ($user_id != "error") {
                $myProfile = false;
                $userData = $checkUserID->fetch_assoc();
                $userRank = $userData['rank'];
                if ($userRank > 0) {
                    $friends = true;
                } else {
                    $friends = false;
                }
            } else {
                $unknownUser = true;
            }
        } else {
            $unknownUser = true;
        }
    }
} else {
    // Check if user is logged in
    if (isset($uid) && $uid != "") {
        ?><script>window.location.href = "./profile";</script><?php
    }
}


$stmt = $conn->prepare("SELECT * FROM `users` WHERE `id` = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1 && $unknownUser == false) {
    $stmt->close();
    $PUDRow = $result->fetch_assoc();
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

    if ($myProfile) {
        $GPusername = "My ";
    } else {
        $GPusername = "$Pusername's ";
    }
    
    if (!isNotEncrypted($Pemail)) { // Check if email is encrypted
        $Pemail = decrypt($Pemail);
    }

    if (!isNotEncrypted($Pfirst_name)) { // Check if first name is encrypted
        $Pfirst_name = decrypt($Pfirst_name);
    }

    if (!isNotEncrypted($Pmiddle_name)) { // Check if middle name is encrypted
        $Pmiddle_name = decrypt($Pmiddle_name);
    }

    if (!isNotEncrypted($Plast_name)) { // Check if last name is encrypted
        $Plast_name = decrypt($Plast_name);
    }

    if ($Pavatar == "../") {
        $Pavatar = "../assets/images/logo_faded_clean.png";
    }

    if ($Pwallpaper == "../") {
        $Pwallpaper = "../assets/images/blank.png";
    }

    $Pavatar_bg = "background: black";
?>
            <div class="page-container">
                <div class="wallpaper" id="wallpaper">
                    <img src="<?=$Pwallpaper?>">
                </div>
                <div class="profile">
                    <div class="profile-left" id="profile-left">
                        <div class="profile-left-user">
                            <div class="avatar" style="<?=$Pavatar_bg?>" id="avatar">
                                <img src="<?=$Pavatar?>">
                            </div>
                            <div class="username">
                                <?=$Pusername?>
                                <span>@<?=$Pusername?></span>
                            </div>
                        </div>
                        <?php if ($logged_in == true) {?>
                        <div class="profile-btns">
                            <?php if ($myProfile == false) {
                                $checkFriendship = $conn->query("SELECT * FROM `friendship` WHERE `user_id`='$uid' AND `friend_id`='$user_id'");
                                $status = $checkFriendship->num_rows ? $checkFriendship->fetch_assoc()['status'] : null; 
                            ?>
                            <?php if ($Pprivate == "0" || $rank > 3) {?>
                            <button onclick="startMessaging(<?=$uid?>,<?=$user_id?>)">
                                <i class="fa-solid fa-message"></i> <span>Chat</span>
                            </button>
                            <?php }?>
                            <div class="friend-actions" id="friend_actions">
                                <button onclick="mobFAM()"><i class="fa-solid fa-user"></i><span>Actions</span></button>
                                <div class="friend-action-buttons" id="friend_action_buttons">
                                    <?php switch ($status):
                                        case "friends": ?>
                                            <button id="fa_unfriend" onclick="friendAction('unfriend', '<?= $user_id ?>')"><i class="fa-solid fa-user-minus"></i><span>Unfriend</span></button>
                                            <button id="fa_block" onclick="friendAction('block', '<?= $user_id ?>')"><i class="fa-solid fa-user-slash"></i><span>Block</span></button>
                                            <?php break;
                                        case "sent": ?>
                                            <button id="fa_cancel" onclick="friendAction('cancel', '<?= $user_id ?>')"><i class="fa-solid fa-xmark"></i><span>Cancel</span></button>
                                            <button id="fa_block" onclick="friendAction('block', '<?= $user_id ?>')"><i class="fa-solid fa-user-slash"></i><span>Block</span></button>
                                            <?php break;
                                        case "received": ?>
                                            <button id="fa_accept" onclick="friendAction('accept', '<?= $user_id ?>')"><i class="fa-solid fa-user-check"></i><span>Accept</span></button>
                                            <button id="fa_ignore" onclick="friendAction('ignore', '<?= $user_id ?>')"><i class="fa-solid fa-xmark"></i><span>Ignore</span></button>
                                            <button id="fa_block" onclick="friendAction('block', '<?= $user_id ?>')"><i class="fa-solid fa-user-slash"></i><span>Block</span></button>
                                            <?php break;
                                        case "blocked": ?>
                                            <button id="fa_unblock" onclick="friendAction('unblock', '<?= $user_id ?>')"><i class="fa-solid fa-user-check"></i><span>Unblock</span></button>
                                            <?php break;
                                        default: ?>
                                            <button id="fa_send" onclick="friendAction('send', '<?= $user_id ?>')"><i class="fa-solid fa-user-plus"></i><span>Add Friend</span></button>
                                            <button id="fa_block" onclick="friendAction('block', '<?= $user_id ?>')"><i class="fa-solid fa-user-slash"></i><span>Block</span></button>
                                            <?php break;
                                        endswitch; ?>
                                    <button class="red" onclick="reportUser('<?= $user_id ?>')"><i class="fa-solid fa-flag"></i><span>Report</span></button>
                                </div>
                            </div>
                            <?php if (isMobile($userAgent)) {?>
                            <script>
                                function mobFAM() { // mobile Friend Action Menu
                                    const buttons = document.getElementById('friend_action_buttons');
                                    if (buttons.style.display == "block") {
                                        buttons.style.display = "none";
                                    } else {
                                        buttons.style.display = "block";
                                    }
                                }
                            </script>
                            <?php }?>
                            <?php }?>
                        </div>
                        <?php if (isMobile($userAgent) == false) {?>
                        <div class="profile-tabs">
                            <?php if ($myProfile) {?>
                            <div class="settings-cat" onclick="window.location.href='../settings'">
                                <div class="settings-icon">
                                    <i class="fa-solid fa-gears"></i>
                                </div>
                                <div class="settings-name">Settings</div>
                            </div>
                            <br>
                            <?php }?>
                            <?php if ($Pprivate == "0" || $rank > 3) {?>
                            <b><?=$GPusername?>Groups</b>
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
                        <?php }?>
                    </div>
                    <div class="profile-right" id="posts">
                        <?php if ($myProfile == true || $Pprivate == "0" || $friends == true || $rank > 3) {?>
                        <?php $getPosts = mysqli_query($conn, "SELECT * FROM `posts` WHERE `user`='$user_id' ORDER BY `created` DESC LIMIT 5");
                        while($post = mysqli_fetch_assoc($getPosts)) {
                            $post_id = $post['id'];
                            $post_user = $post['user'];
                            $post_content = decrypt($post['content']);
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
                            $post_links = extractUrls($post_content);
                            $post_content_res = fixEmojis(nl2br(cleanUrls(html_entity_decode($post_content, ENT_QUOTES | ENT_HTML5, 'UTF-8'))), 1);
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
                                    <?php if ($logged_in) {?>
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
                                    <?php }?>
                                </div>
                                <div class="post_content" id="post_c_<?=$post_id?>">
                                    <?=$post_content_res?>
                                    <?php
                                    if (!empty($post_links)) {
                                        foreach ($post_links as $post_link) {
                                            if (strpos($post_link, "https://") === false && strpos($post_link, "http://") === false) {
                                                $post_link = "https://{$post_link}"; // Ensure valid URL format
                                            }
                                    ?>
                                    <a href="<?=$post_link?>" target="_blank"><?=$post_link?></a>
                                    <?php }} ?>
                                </div>
                                <?php if (!empty($post_video)) {?>
                                <div class="post_links">
                                    <?=$post_video?>
                                </div>
                                <?php }?>
                                <?php if (!empty($post_links)) { ?>
                                <div class="link_preview">
                                    <?php
                                    foreach ($post_links as $post_link) {
                                        if (strpos($post_link, "https://") === false && strpos($post_link, "http://") === false) {
                                            $post_link = "https://" . $post_link; // Ensure valid URL format
                                        }
    
                                        $urlData = getLinkData($post_link);
                                        $urlRestricted = $urlData['restricted'];
                                        $urlLogo = !empty($urlData['favicon']) ? $urlData['favicon'] : '../assets/images/logo_faded_clean.png';
                                        $urlTitle = htmlspecialchars($urlData['title'], ENT_QUOTES, 'UTF-8');
                                        $urlDescription = htmlspecialchars($urlData['description'], ENT_QUOTES, 'UTF-8');
                                        $urlImage = !empty($urlData['featured']) ? $urlData['featured'] : ''; // Use featured image if available
    
                                        if ($urlRestricted) {
                                            continue; // Skip restricted links
                                        }
                                    ?>
                                        <div class="post_link_preview" onclick="window.open('<?= htmlspecialchars($post_link, ENT_QUOTES, 'UTF-8') ?>', '_blank')">
                                            <?php if (!empty($urlImage)) { ?>
                                                <div class="post_link_preview_image">
                                                    <img src="<?= htmlspecialchars($urlImage, ENT_QUOTES, 'UTF-8') ?>" alt="Preview Image">
                                                </div>
                                            <?php } if (!empty($urlLogo)) { ?>
                                                <div class="post_link_preview_icon">
                                                    <img src="<?= htmlspecialchars($urlLogo, ENT_QUOTES, 'UTF-8') ?>" alt="Favicon">
                                                </div>
                                            <?php } ?>
                                            <div class="post_link_preview_info">
                                                <div class="post_link_preview_title"><?= $urlTitle ?></div>
                                                <div class="post_link_preview_description"><?= $urlDescription ?></div>
                                            </div>
                                        </div>
                                    <?php } ?>
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
                                    <div class="post_comment_new">
                                        <div class="post_comment_new_content">
                                            <input type="text" id="pc_<?=$post_id?>" onkeydown="hitEnter(this,<?=$post_id?>)" placeholder="Write a comment <?php if(isset($username)) {echo $username;}?>">
                                        </div>
                                        <div class="post_comment_new_actions">
                                            <div class="btn" onclick="sendComment(<?=$post_id?>)"><i class="fa-solid fa-paper-plane"></i></div>
                                        </div>
                                    </div>
                                    <div id="post_comments_<?=$post_id?>">
                                        <?php $getComment = $conn->query("SELECT * FROM `comments` WHERE `post`='$post_id' ORDER BY `date` DESC");
                                        if ($getComment->num_rows > 0) {
                                            while($commentData = $getComment->fetch_assoc()) {
                                                $commentID = $commentData['id'];
                                                $commentUser = $commentData['user'];
                                                $commentUsername = getUser("id",$commentData['user'],"username");
                                                $commentAvatar = getUser("id",$commentData['user'],"avatar");
                                                $commentText = $commentData['content'];
                            
                                                if (isNotEncrypted($commentText)) {
                                                    $commentText = encrypt(htmlentities($commentText, ENT_QUOTES | ENT_HTML5, 'UTF-8'));
                                                    $stmt = $conn->prepare("UPDATE `comments` SET `content` = ? WHERE `id` = ?");
                                                    $stmt->bind_param("si", $commentText, $commentID);
                                                    $stmt->execute();
                                                    $stmt->close();
                                                }
                                                
                                                $commentText = fixEmojis(nl2br(cleanUrls(html_entity_decode(decrypt($commentText), ENT_QUOTES | ENT_HTML5, 'UTF-8'))), 1);
                                                
                                                if ($commentAvatar == "") {
                                                    $commentAvatar = "../assets/images/logo_faded_clean.png";
                                                }

                                                if ($commentUser == $_SESSION['user']) {
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
<?php } else {?>
            <div class="page-container">
                <div class="wallpaper" id="wallpaper">
                    <img src="../assets/images/blank.png">
                </div>
                <div class="profile">
                    <div class="profile-left" id="profile-left">
                        <div class="profile-left-user">
                            <div class="avatar" id="avatar">
                                <img src="../assets/images/logo_faded_clean.png">
                            </div>
                            <div class="username">Unknown User</div>
                        </div>
                    </div>
                    <div class="profile-right" id="posts">
                        This profile does not exist
                    </div>
                </div>
            </div>
<?php }?>