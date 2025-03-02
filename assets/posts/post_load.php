<?php include "../functions.php";

if (isset($_POST['post_id'])) {
    $post_id = $_POST['post_id'];
    
    $stmt = $conn->prepare("SELECT * FROM `posts` WHERE `id` = ? AND (`user` = ? OR `user` IN (SELECT `friend_id` FROM `friendship` WHERE `user_id` = ? AND `status` = 'friends'))");
    $stmt->bind_param('iii', $post_id, $uid, $uid);
    $stmt->execute();
    $checkPost = $stmt->get_result();

    if ($checkPost->num_rows == 1) {
        $post = $checkPost->fetch_assoc();
        $post_id = $post['id'];
        $post_user = $post['user'];
        $post_content = html_entity_decode(decrypt($post['content']), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $post_created = date("d M. y H:i:s", $post['created']);

        $getComments = $conn->query("SELECT * FROM `comments` WHERE `post`='$post_id'");
        $comments = $getComments->num_rows;

        $getPostUser = $conn->query("SELECT * FROM `users` WHERE `id`='$post_user'");
        $postUser = $getPostUser->fetch_assoc();
        $post_user_name = $postUser['username'];
        $post_user_avatar = "../".$postUser['avatar'];
        if ($post_user_avatar == "../") {
            $post_user_avatar = "../assets/images/logo_faded_clean.png";
        }

        $post_video = convertVideo($post_content);
        $post_links = extractUrls($post_content);
        $post_content_res = fixEmojis(cleanUrls(nl2br($post_content)), 1);
    ?>

    <div class="post" id="post_<?=$post_id?>">
        <div class="post_body">
            <div class="post_header">
                <div class="post_details">
                    <div class="post_user">
                        <div class="post_user_image" onclick="window.location.href='../profile?user=<?=$post_user_name?>'">
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
                <?php
                if (!empty($post_links)) {
                    foreach ($post_links as $post_link) {
                        if (strpos($post_link, "https://") === false && strpos($post_link, "http://") === false) {
                            $post_link = "https://" . $post_link; // Ensure valid URL format
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
                ?>
                <div class="post_link_preview" id="plp_<?=$post_id?>" alt="<?= htmlspecialchars($post_link, ENT_QUOTES, 'UTF-8') ?>" onclick="window.open('<?= htmlspecialchars($post_link, ENT_QUOTES, 'UTF-8') ?>', '_blank')"></div>
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
                            $commentText = fixEmojis(nl2br(cleanUrls(decrypt($commentData['content']))), 1);
                            
                            if ($commentAvatar == "") {
                                $commentAvatar = "./assets/images/logo_faded_clean.png";
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
    <?php }
}?>