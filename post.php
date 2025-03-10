<?php include_once "assets/header.php"?>
<?php
if (!isset($_SESSION['user'])) {
    include_once "assets/forms/login-popup.php";
}

if (isset($_GET['p'])) {
    $post_id = $_GET['p'];
?>
        <div class="page-container">
            <div class="post-container">
                <?php
                $getPost = $conn->query("SELECT * FROM `posts` WHERE `id`='$post_id'");
                if ($getPost->num_rows == 1) {
                    $postData = $getPost->fetch_assoc();
                    $postUser = $postData['user'];
                    $postContent = html_entity_decode($postData['content'], ENT_QUOTES | ENT_HTML5, 'UTF-8');
                    $postDate = $postData['date'];
                    $postLikes = $postData['likes'];
                    $postComments = $postData['comments'];
                    $postComments = json_decode($postComments, true);
                    $postLikes = json_decode($postLikes, true);
                    $postDate = date("d/m/Y H:i", $postDate);
                    $getUser = $conn->query("SELECT `username`,`rank` FROM `users` WHERE `id`='$postUser'");
                    $userData = $getUser->fetch_assoc();
                    $postUsername = $userData['username'];
                    $postRank = $userData['rank'];
                    ?>
                    <div class="post">
                        <div class="post-head">
                            <div class="post-user">
                                <a href="profile.php?u=<?php echo $postUsername;?>"><?php echo $postUsername;?></a>
                                <?php if ($postRank > 5) {?>
                                    <span class="admin">Admin</span>
                                <?php }?>
                            </div>
                            <div class="post-date"><?php echo $postDate;?></div>
                        </div>
                        <div class="post-content">
                            <?php echo $postContent;?>
                        </div>
                        <div class="post-actions">
                            <div class="like" id="like-<?php echo $post_id;?>" onclick="likePost(<?php echo $post_id;?>)">
                                <i class="fa-regular fa-heart"></i>
                                <span id="like-count-<?php echo $post_id;?>"><?php echo count($postLikes);?></span>
                            </div>
                            <div class="comment" id="comment-<?php echo $post_id;?>" onclick="commentPost(<?php echo $post_id;?>)">
                                <i class="fa-regular
                                fa-comment"></i>
                                <span id="comment-count-<?php echo $post_id;?>"><?php echo count($postComments);?></span>
                            </div>
                        </div>
                    </div>
                    <div class="comments" id="comments-<?php echo $post_id;?>">
                        <?php
                        if (count($postComments) > 0) {
                            foreach ($postComments as $comment) {
                                $commentUser = $comment['user'];
                                $commentContent = $comment['content'];
                                $commentDate = $comment['date'];
                                $commentDate = date("d/m/Y H:i", $commentDate);
                                $getUser = $conn->query("SELECT `username`,`rank` FROM `users` WHERE `id`='$commentUser'");
                                $userData = $getUser->fetch_assoc();
                                $commentUsername = $userData['username'];
                                $commentRank = $userData['rank'];
                                ?>
                                <div class="comment">
                                    <div class="comment-head">
                                        <div class="comment-user">
                                            <a href="profile.php?u=<?php echo $commentUsername;?>"><?php echo $commentUsername;?></a>
                                            <?php if ($commentRank > 5) {?>
                                                <span class="admin">Admin</span>
                                            <?php }?>
                                        </div>
                                        <div class="comment-date"><?php echo $commentDate;?></div>
                                    </div>
                                    <div class="comment-content">
                                        <?php echo $commentContent;?>
                                    </div>
                                </div>
                                <?php
                            }
                        } else {
                            ?>
                            <div class="comment">
                                <div class="comment-content">
                                    No comments yet
                                </div>
                            </div>
                            <?php
                        }
                        ?>
                        <div class="comment-form">
                            <textarea id="comment-content-<?php echo $post_id;?>" placeholder="Write a comment"></textarea>
                            <button onclick="postComment(<?php echo $post_id;?>)">Post</button>
                        </div>
                    </div>
                    <?php
                } else {
                    ?>
                    <div class="post">
                        <div class="post-content">
                            Post not found
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
        <script>
            function likePost(post) {
                $.ajax({
                    type: "POST",
                    url: "functions.php",
                    data: {
                        like: post
                    },
                    success: function (message) {
                        alert(message);
                    }
                });
            }

            function commentPost(post) {
                var comments = document.getElementById("comments-" + post);
                if (comments.style.display == "block") {
                    comments.style.display = "none";
                } else {
                    comments.style.display = "block";
                }
            }

            function postComment(post) {
                var content = document.getElementById("comment-content-" + post).value;
                $.ajax({
                    type: "POST",
                    url: "functions.php",
                    data: {
                        comment: post,
                        content: content
                    },
                    success: function (message) {
                        alert(message);
                    }
                });
            }
        </script>
    </div>
<?php }?>