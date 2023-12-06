<?php include_once "assets/header.php"?>
<?php
if (!isset($_SESSION['user'])) {
    ?><meta http-equiv="Refresh" content="0; url='./'" /><?php
}

$post_id = $_GET['p'];
?>
        <div class="page-container">
            <?php $getPosts = mysqli_query($conn, "SELECT * FROM `posts` WHERE `id`='$post_id'");
            $post = mysqli_fetch_assoc($getPosts);
            $post_id = $post['id'];
            $post_user = $post['user'];
            $post_content = $post['content'];
            $post_created = date("Y-m-d H:i:s", $post['created']);

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
            <div class="post">
                <div class="post_body">
                    <div class="post_header">
                        <div class="post_details">
                            <div class="post_user">
                                <div class="post_user_image">
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
                                <div class="post_action" onclick="sharePost(<?=$post_id?>)">
                                    <i class="fa-solid fa-share"></i> Share
                                </div>
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
                    <div class="comments" id="comments">
                        <hr>
                        <div class="new_comment">
                            <div class="new_comment_avatar">
                                <img src="<?=$avatar?>">
                            </div>
                            <input placeholder="Skriv en kommenter" autofocus required>
                        </div>
                        <?php
                        $getComments = mysqli_query($conn, "SELECT * FROM `comments` WHERE `post`='$post_id'");
                        $comments = mysqli_num_rows($getComments);
                        if ($comments > 0) {
                            while($comment = mysqli_fetch_assoc($getComments)) {
                                $comment_id = $comment['id'];
                                $comment_user = $comment['user'];
                                $comment_content = $comment['content'];
                                $comment_created = $comment['created'];

                                $getCommentUser = mysqli_query($conn, "SELECT * FROM `users` WHERE `id`='$comment_user'");
                                $commentUser = mysqli_fetch_assoc($getCommentUser);
                                $comment_user_name = $commentUser['username'];
                                $comment_user_avatar = $commentUser['avatar'];
                                ?>
                                <div class="comment">
                                    <div class="comment_user">
                                        <img src="<?=$comment_user_avatar?>">
                                        <?=$comment_user_name?>
                                    </div>
                                    <div class="comment_text"><?=$comment_content?></div>
                                    <div class="comment_actions"></div>
                                </div>
                                <?php
                            }
                        } else {
                            ?>
                            <center>Vær den første til å kommentere</center>
                            <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>

        <script>
        </script>
    </body>
</html>