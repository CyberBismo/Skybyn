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
                <div class="post_action_list">
                    <!--div class="post_action">
                        <i class="fa-solid fa-share-nodes"></i>
                    </div-->
                    <div class="post_action">
                        <i class="fa-solid fa-trash"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="post_content">
            <?=$post_content?>
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