<?php include_once "functions.php";

$created = $_POST['created'];

$getPosts = $conn->query("SELECT p.* FROM `posts` p LEFT JOIN `friendship` f ON p.user = f.friend_id WHERE p.created = $created AND p.user = $uid OR (f.user_id = $uid AND f.status = 'accepted') ORDER BY p.created DESC");
$post = $getPosts->fetch_assoc();
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

$post_youtube = convertYoutube($post_content);
$post_content_res = fixEmojis(makeClickable($post_content),1);
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
                    <!--div class="post_action">
                        <i class="fa-solid fa-share-nodes"></i>
                    </div-->
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
        <div id="post_c_<?=$post_id?>" hidden><?=$post_content?></div>
        <div class="post_content">
            <?=$post_content_res?>
        </div>
        <div class="post_links">
            <?=$post_youtube?>
        </div>
        <?php if ($rank > 0) {?>
        <div class="post_comments" onclick="showPost(<?=$post_id?>)">
            <i class="fa-solid fa-circle-plus"></i> (<?=$comments?>)
        </div>
        <?php }?>
    </div>
</div>