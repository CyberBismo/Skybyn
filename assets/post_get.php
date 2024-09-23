<?php include "./functions.php";

$pid = $_POST['post'];

$getPosts = $conn->query("SELECT p.*, u.username, u.avatar,
        (SELECT COUNT(*) FROM `comments` WHERE `post` = p.id) AS comment_count
    FROM `posts` p
    LEFT JOIN `users` u ON p.user = u.id
    WHERE `p.id` = '$pid'
");

$post = $getPosts->fetch_assoc();
$post_id = $post['id'];
$post_user = $post['user'];
$post_content = $post['content'];
$post_created = date("d M. y H:i:s", $post['created']);
$post_user_name = $post['username'];
$post_user_avatar = "./" . $post['avatar'];
$comment_count = $post['comment_count'];

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
        <?php if (!empty($post_links)) { ?>
        <div class="link_preview">
            <?php for ($i = 0; $i < count($post_links); $i++) {
                if ($i <= count($post_links)) {
                    if (strpos($post_links[$i], "http") === false) {
                        $post_links[$i] = "http://".$post_links[$i];
                    }
                    $urlData = getLinkData($post_links[$i]);
                    $urlLogo = $urlData['favicon'];
                    $urlTitle = $urlData['title'];
                    $urlDescription = $urlData['description'];
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
            <?php }}?>
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
            <div class="post_comment_post">
                <div class="post_comment_post_user">
                    <div class="post_comment_user_avatar">
                        <img src="<?=$avatar?>">
                    </div>
                    <span><?=$username?></span>
                </div>
                <input type="text" id="pc_<?=$post_id?>" onkeydown="hitEnter(this,<?=$post_id?>)" placeholder="Write a comment">
                <button class="btn" onclick="sendComment(<?=$post_id?>)"><i class="fa-solid fa-paper-plane"></i></button>
            </div>
            <div id="post_comments_<?=$post_id?>">
                <?php $getComment = $conn->query("SELECT * FROM `comments` WHERE `post`='$post_id' ORDER BY `date` DESC");
                if ($getComment->num_rows > 0) {
                    while($commentData = $getComment->fetch_assoc()) {
                        $commentID = $commentData['id'];
                        $commentUsername = getUser("id",$commentData['user'],"username");
                        $commentAvatar = getUser("id",$commentData['user'],"avatar");
                        $commentText = $commentData['content'];
                        
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
                        <div class="btn" onclick="delComment(<?=$commentID?>)"><i class="fa-solid fa-trash"></i></div>
                    </div>
                </div>
                <?php }}?>
            </div>
        </div>
    </div>
</div>