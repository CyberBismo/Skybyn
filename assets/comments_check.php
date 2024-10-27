<?php include "./functions.php";

$comment_id = $_POST['comment_id'];
$post_id = $_POST['post_id'];

$getComment = $conn->query("SELECT * FROM `comments` WHERE `post`='$post_id' AND `id`='$comment_id'");
if ($getComment->num_rows == 1) {
    $commentData = $getComment->fetch_assoc();
    $commentID = $commentData['id'];
    $commentUser = $commentData['user'];
    $commentUsername = getUser("id",$commentData['user'],"username");
    $commentAvatar = getUser("id",$commentData['user'],"avatar");
    $commentText = $commentData['content'];
    
    if ($commentAvatar == "") {
        $commentAvatar = "./assets/images/logo_faded_clean.png";
    }

    if ($commentData['user'] == $_SESSION['user']) {
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
        <div class="post_comment_actions">
            <div class="btn" onclick="delComment(<?=$commentID?>)"><i class="fa-solid fa-trash"></i></div>
        </div>
    </div>
    <div class="post_comment_content"><?=$commentText?></div>
</div>
<?php }?>