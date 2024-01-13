<?php
include "./functions.php";

$getPosts = $conn->query("SELECT p.*
    FROM posts p
    WHERE p.user = $uid OR p.user IN (
        SELECT f.friend_id
        FROM friendship f
        WHERE f.user_id = $uid AND f.status = 'accepted'
    )
    ORDER BY p.created DESC
    LIMIT 5
");

while ($post = $getPosts->fetch_assoc()) {
    $post_id = $post['id'];
    $post_user = $post['user'];
    $post_content = $post['content'];
    $post_created = date("d M. y H:i:s", $post['created']);
    $post_links = $post['urls'];

    $getComments = $conn->query("SELECT * FROM `comments` WHERE `post`='$post_id'");
    $comments = $getComments->num_rows;

    $getPostUser = $conn->query("SELECT * FROM `users` WHERE `id`='$post_user'");
    $postUser = $getPostUser->fetch_assoc();
    $post_user_name = $postUser['username'];
    $post_user_avatar = "./".$postUser['avatar'];
    if ($post_user_avatar == "./") {
        $post_user_avatar = "./assets/images/logo_faded_clean.png";
    }

    $post_youtube = convertYoutube($post_content);
    $post_content_res = str_replace('\r\n',"<br />",fixEmojis($post_content, 1));
    $post_links = makeClickable($post_links);
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
                    <div class="post_action" onclick="">
                        <i class="fa-solid fa-magnifying-glass-plus"></i> Show
                    </div>
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
            <div class="post_link">
                <?=$post_links?>
            </div>
            <?=$post_youtube?>
        </div>
        <div class="post_uploads">
            <?php $getUploads = $conn->query("SELECT * FROM `uploads` WHERE `post`='$post_id'");
            if ($getUploads->num_rows > 0) {
                while($upload = $getUploads->fetch_assoc()) {
                    $file = $upload['file_url'];?>
                <img src="<?=$file?>" onclick="showImage(<?=$post_id?>)">
            <?php }}?>
        </div>
        <i><?=$comments?> comment(s)</i>
        <div class="post_comments">
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
                        $commentUser = $commentData['user'];
                        $commentUsername = getUser("id",$commentData['user'],"username");
                        $commentAvatar = getUser("id",$commentData['user'],"avatar");
                        $commentText = $commentData['content'];
                        
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
                        <?php if ($rank > 0 || $commentUser == $uid) {?>
                        <div class="btn" onclick="delComment(<?=$commentID?>)"><i class="fa-solid fa-trash"></i></div>
                        <?php }?>
                    </div>
                </div>
                <?php }}?>
            </div>
        </div>
    </div>
</div>

<?php }?>

<script>
    let loading = false;
    const limit = 8;

    function loadMorePosts() {
        if (loading) {
            return;
        }

        const countPosts = document.querySelectorAll('div.post');
        const offset = countPosts.length;

        loading = true;
        $.ajax({
            url: 'assets/posts_load.php',
            type: 'POST',
            data: {
                offset: offset
            },
            success: function (response) {
                const postsContainer = document.getElementById('posts');
                postsContainer.insertAdjacentHTML('beforeend', response);
                loading = false;
            },
            error: function () {
                loading = false;
            }
        });
    }

    // Attach the scroll event listener to load more posts when scrolled to the bottom
    window.addEventListener('scroll', function () {
        const windowHeight = window.innerHeight;
        const documentHeight = document.documentElement.scrollHeight;
        const scrollPosition = window.scrollY;

        if (documentHeight - (scrollPosition + windowHeight) < 200) {
            loadMorePosts();
        }
    });

    function hitEnter(input,x) {
        const button = document.getElementById('login');

        function handleKeyPress(event) {
            if (event.keyCode === 13) {
                sendComment(x);
            }
        }

        input.addEventListener('keydown', handleKeyPress, { once: true });
    }
    function sendComment(x) {
        const input = document.getElementById('pc_'+x);

        if (input.value.length > 0) {
            $.ajax({
                url: 'assets/comment_new.php',
                type: "POST",
                data: {
                    post_id : x,
                    comment : input.value
                }
            }).done(function(response) {
                input.value = "";
                checkComments(x);
            });
        }
    }
    function checkComments(x) {
        const comments = document.getElementById('post_comments_'+x);
        const comment = comments.firstElementChild;
        $.ajax({
            url: 'assets/comments_check.php',
            type: "POST",
            data: {
                post : x
            }
        }).done(function(response) {
            if (response != "") {
                comments.insertAdjacentHTML('afterbegin', response);
                removeDuplicateIds();
            }
        });
    }
    function cleanComments() {
        let comments = document.querySelectorAll('.post_comment');
        let commentIds = [];
        for (var i = 0; i < comments.length; i++) {
            let commentId = comments[i].id.replace('comment_', '');
            commentIds.push(commentId);
        }

        if (commentIds.length > 0) {
            $.ajax({
                url: 'assets/comments_clean.php',
                type: "POST",
                data: {
                    ids: commentIds
                }
            }).done(function(response) {
                var nonExistingCommentIds = response.split(',');
                for (var i = 0; i < nonExistingCommentIds.length; i++) {
                    var commentId = nonExistingCommentIds[i];
                    var comment = document.getElementById('comment_' + commentId);
                    if (comment) {
                        comment.remove();
                    }
                }
            });
        }
    }
    function delComment(x) {
        const comment = document.getElementById('comment_'+x);
        $.ajax({
            url: 'assets/comment_delete.php',
            type: "POST",
            data: {
                comment_id : x
            }
        }).done(function(response) {
            comment.remove();
        });
    }
    function sharePost(x) {
        window.location.href="./post?p="+x;
    }
    function showPost(x) {
    }
    function editPost(x) {
        const post = document.getElementById('post_c_'+ x);
        const new_post_input = document.getElementById('new_post_input');
        new_post_input.value = post.innerHTML;
        new_post_input.focus();
        newPost();
    }
    function deletePost(x) {
        const post = document.getElementById('post_'+ x);
        $.ajax({
            url: 'assets/functions.php',
            type: "POST",
            data: {
                deletePost : null,
                post_id : x
            }
        }).done(function(response) {
            post.remove();
        });
    }
    function showPostActions(x) {
        const actionList = document.getElementById("pal_"+x);
        
        if (actionList.hidden == true) {
            actionList.hidden = false;
        } else {
            actionList.hidden = true;
        }
    }
    function checkPosts() {
        let posts = document.getElementById('posts');
        let post = posts.firstElementChild;
        let id = post.id.replace("post_", "");
        $.ajax({
            url: 'assets/posts_check.php',
            type: "POST",
            data: {
                last : id
            }
        }).done(function(response) {
            if (response != "") {
                posts.insertAdjacentHTML('afterbegin', response);
                removeDuplicateIds();
            }
        });
    }
    function cleanPosts() {
        let posts = document.querySelectorAll('.post');
        let postIds = [];
        for (var i = 0; i < posts.length; i++) {
            let postId = posts[i].id.replace('post_', '');
            postIds.push(postId);
        }

        if (postIds.length > 0) {
            $.ajax({
                url: 'assets/posts_clean.php',
                type: "POST",
                data: {
                    ids: postIds
                }
            }).done(function(response) {
                var nonExistingPostIds = response.split(',');
                for (var i = 0; i < nonExistingPostIds.length; i++) {
                    var postId = nonExistingPostIds[i];
                    var post = document.getElementById('post_' + postId);
                    if (post) {
                        post.remove();
                    }
                }
            });
        }
    }
    function removeDuplicateIds() {
        const elements = document.querySelectorAll('*');
        const idMap = new Map();
        elements.forEach(element => {
            const id = element.id;
            if (id) {
                if (idMap.has(id)) {
                    element.parentNode.removeChild(element);
                } else {
                    idMap.set(id, true);
                }
            }
        });
    }
    setInterval(() => {
        checkPosts();
        cleanPosts();
    }, 300000); // Every 5 minutes
    removeDuplicateIds();
</script>