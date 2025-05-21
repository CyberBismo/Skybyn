<?php include "../functions.php";

$pid = $_POST['post'];

$getPosts = $conn->query("SELECT * FROM `posts` WHERE `id`='$pid'");
$getComments = $conn->query("SELECT * FROM `comments` WHERE `post`='$pid'");
$comment_count = $getComments->num_rows;

$post = $getPosts->fetch_assoc();
$post_id = $post['id'];
$post_user = $post['user'];
$post_content = html_entity_decode(decrypt($post['content']), ENT_QUOTES | ENT_HTML5, 'UTF-8');
$post_created = date("d M. y H:i:s", $post['created']);

$getComments = $conn->query("SELECT * FROM `comments` WHERE `post`='$post_id'");
$comments = $getComments->num_rows;

$getPostUser = $conn->query("SELECT * FROM `users` WHERE `id`='$post_user'");
$postUser = $getPostUser->fetch_assoc();
$post_user_name = $postUser['username'];
$post_user_avatar = "./".$postUser['avatar'];
if ($post_user_avatar == "./") {
    $post_user_avatar = "./assets/images/logo_faded_clean.png";
}

$post_video = convertVideo($post_content);
$post_links = extractUrls($post_content);
$post_content_res = fixEmojis(cleanUrls(nl2br($post_content)), 1);
?>

<?php if (isset($_SESSION['user'])) {?>
<script src="assets/js/posts/updateFeed.js"></script>
<script src="assets/js/comments/updateComments.js"></script>
<?php }?>

<div class="post_header">
    <div class="post_details">
        <div class="post_user">
            <div class="post_user_image" onclick="window.location.href='./profile/<?=$post_user_name?>'">
                <img src="<?=$post_user_avatar?>" class="pixelated-image">
            </div>
            <div class="post_user_name"><?=$post_user_name?></div>
        </div>
        <div class="post_date"><?=$post_created?></div>
    </div>
    <div class="post_actions" onclick="showPostActions(<?=$post_id?>)">
        <i class="fa-solid fa-ellipsis-vertical"></i>
        <div class="post_action_list" id="pal_<?=$post_id?>" hidden>
            <?php if ($post_user == $uid || $rank > 0) {?>
            <div class="post_action" onclick="editPost(<?=$post_id?>)">
                <i class="fa-solid fa-pen-to-square"></i><span>Edit</span>
            </div>
            <div class="post_action" onclick="deletePost(<?=$post_id?>)">
                <i class="fa-solid fa-trash"></i><span>Delete</span>
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
            <?php } else { ?>
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
<div class="post_comments">
    <div class="post_comment_count"><?=$comment_count?><i class="fa-solid fa-comments"></i></div>
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
        <?php $getComment = $conn->query("SELECT * FROM `comments` WHERE `post`='$post_id' ORDER BY `date` DESC");
        if ($getComment->num_rows > 0) {
            while($commentData = $getComment->fetch_assoc()) {
                $commentID = $commentData['id'];
                $commentUsername = getUser("id",$commentData['user'],"username");
                $commentAvatar = "../".getUser("id",$commentData['user'],"avatar");
                $commentText = fixEmojis(nl2br(cleanUrls(decrypt($commentData['content']))), 1);
                
                if ($commentAvatar == "../") {
                    $commentAvatar = "../assets/images/logo_faded_clean.png";
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
                <div class="btn" onclick="delComment(<?=$commentID?>)"><i class="fa-solid fa-trash"></i></div>
            </div>
        </div>
        <?php }}?>
    </div>
</div>