<?php include "./functions.php";

$post_id = $_POST['post'];

$getComment = $conn->query("SELECT * FROM `comments` WHERE `post`='$post_id' ORDER BY `date` DESC");
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
                <?php if ($rank > 0 || $commentUser == $uid) {?>
                <div class="btn" onclick="delComment(<?=$commentID?>)"><i class="fa-solid fa-trash"></i></div>
                <?php }?>
            </div>
        </div>
<?php }}?>