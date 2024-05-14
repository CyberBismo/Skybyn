<?php include "./functions.php";

$pid = $_POST['post'];

$getPosts = $conn->query("SELECT * FROM `posts` WHERE `id`='$pid'");
$getComments = $conn->query("SELECT * FROM `comments` WHERE `post`='$pid'");
$comment_count = $getComments->num_rows;

$post = $getPosts->fetch_assoc();
$post_id = $post['id'];
$post_user = $post['user'];
$post_content = $post['content'];
$post_created = date("d M. y H:i:s", $post['created']);

$getPostUser = $conn->query("SELECT * FROM `users` WHERE `id`='$post_user'");
$postUser = $getPostUser->fetch_assoc();

$post_user_name = $postUser['username'];
$post_user_avatar = "./" . $postUser['avatar'];

if ($post_user_avatar == "./") {
    $post_user_avatar = "./assets/images/logo_faded_clean.png";
}

$post_video = convertVideo($post_content);
$post_content_res = fixEmojis(cleanUrls(nl2br($post_content)), 1);
?>

<div class="post_header">
    <div class="post_details">
        <div class="post_user">
            <div class="post_user_image" onclick="window.location.href='./profile?u=<?=$post_user_name?>'">
                <img src="<?=$post_user_avatar?>" class="pixelated-image">
            </div>
            <div class="post_user_name"><?=$post_user_name?></div>
        </div>
        <div class="post_date"><?=$post_created?></div>
    </div>
    <div class="post_actions">
        <i class="fa-solid fa-ellipsis-vertical"></i>
        <div class="post_action_list" hidden>
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
<div class="post_content">
    <?=$post_content_res?>
</div>
<?php if (!empty($post_video)) {?>
<div class="post_links">
    <?=$post_video?>
</div>
<?php }?>
<div class="post_comments">
    <div class="post_comment_count"><?=$comment_count?><i class="fa-solid fa-comments"></i></div>
    <div class="post_comment">
        <div class="post_comment_user">
            <img src="<?=$avatar?>">
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
                $commentAvatar = getUser("id",$commentData['user'],"avatar");
                $commentText = fixEmojis(nl2br(cleanUrls($commentData['content'])), 1);
                
                if ($commentAvatar == "") {
                    $commentAvatar = "./assets/images/logo_faded_clean.png";
                }?>
        <div class="post_comment" id="comment_<?=$commentID?>">
            <div class="post_comment_user">
                <img src="<?=$commentAvatar?>">
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