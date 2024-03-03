<?php
include_once "./functions.php";

$text = $_POST['text'];

if (strpos($text, "@user ") === 0) {
    // User search
    $username = substr($text, 6); // 6 is the length of "@user "
    $getUsers = $conn->query("SELECT * FROM `users` WHERE `username` LIKE '$username%'");

    if ($getUsers->num_rows > 0) {
        while ($user = $getUsers->fetch_assoc()) {
            $username = $user['username'];
            $avatar = "./" . $user['avatar'];

            if ($avatar == "./") {
                $avatar = "./assets/images/logo_faded_clean.png";
            }
?>
            <div class="search_res_user" onclick="window.location.href='./profile?u=<?= $username ?>'">
                <div class="search_res_user_avatar">
                    <img src="<?= $avatar ?>">
                </div>
                <?= $username ?>
            </div>
<?php
        }
    } else {
        ?>
        <div class="search_res_user">
            No users found
        </div>
        <?php
    }
} elseif (strpos($text, "/page ") === 0) {
    // Page search
    $pageName = substr($text, 6); // 6 is the length of "/page "
    $getPages = $conn->query("SELECT * FROM `pages` WHERE `name` LIKE '$pageName%' OR `id` LIKE '$pageName%'");

    if ($getPages->num_rows > 0) {
        while ($page = $getPages->fetch_assoc()) {
            $pid = $page['id'];
            $name = $page['name'];
            $icon = "./" . $page['icon'];

            if ($icon == "./") {
                $icon = "./assets/images/logo_faded_clean.png";
            }
?>
            <div class="search_res_page" onclick="window.location.href='./page?id=<?= $pid ?>'">
                <div class="search_res_page_icon">
                    <img src="<?= $icon ?>">
                </div>
                <?= $name ?>
            </div>
<?php
        }
    } else {
        ?>
        <div class="search_res_page">
            No pages found
        </div>
        <?php
    }
} elseif (strpos($text, "/group ") === 0) {
    // Group search
    $groupName = substr($text, 6); // 6 is the length of "/group "
    $getGroups = $conn->query("SELECT * FROM `groups` WHERE `name` LIKE '$groupName%' OR `id` LIKE '$groupName%'");

    if ($getGroups->num_rows > 0) {
        while ($group = $getGroups->fetch_assoc()) {
            $gid = $group['id'];
            $name = $group['name'];
            $icon = "./" . $group['icon'];

            if ($icon == "./") {
                $icon = "./assets/images/logo_faded_clean.png";
            }
?>
            <div class="search_res_group" onclick="window.location.href='./group?id=<?= $gid ?>'">
                <div class="search_res_group_icon">
                    <img src="<?= $icon ?>">
                </div>
                <?= $name ?>
            </div>
<?php
        }
    } else {
        ?>
        <div class="search_res_group">
            No groups found
        </div>
        <?php
    }
} else {
    // Post search
    $getPost = $conn->query("SELECT * FROM `posts` WHERE `content` LIKE '%$text%'");

    if ($getPost->num_rows > 0) {
        while ($post = $getPost->fetch_assoc()) {
            $pid = $post['id'];
            $uid = $post['uid'];
            $content = $post['content'];
            $time = $post['time'];

            $getUser = $conn->query("SELECT * FROM `users` WHERE `id` = '$uid'");
            $user = $getUser->fetch_assoc();
            $username = $user['username'];
            $avatar = "./" . $user['avatar'];

            if ($avatar == "./") {
                $avatar = "./assets/images/logo_faded_clean.png";
            }

            $content = str_replace($text, "<span class='search_res_post_highlight'>$text</span>", $content);

            $time = timeAgo($time);

            $getLikes = $conn->query("SELECT * FROM `likes` WHERE `pid` = '$pid'");
            $likes = $getLikes->num_rows;

            $getComments = $conn->query("SELECT * FROM `comments` WHERE `pid` = '$pid'");
            $comments = $getComments->num_rows;

            $getShares = $conn->query("SELECT * FROM `shares` WHERE `pid` = '$pid'");
            $shares = $getShares->num_rows;

            $getReposts = $conn->query("SELECT * FROM `reposts` WHERE `pid` = '$pid'");
            $reposts = $getReposts->num_rows;

            $getViews = $conn->query("SELECT * FROM `views` WHERE `pid` = '$pid'");
            $views = $getViews->num_rows;

            $getReplies = $conn->query("SELECT * FROM `replies` WHERE `pid` = '$pid'");
            $replies = $getReplies->num_rows;
            ?>
                        <div class="search_res_group" onclick="window.location.href='./group?id=<?= $gid ?>'">
                            <div class="search_res_group_icon">
                                <img src="<?= $icon ?>">
                            </div>
                            <?= $name ?>
                        </div>
            <?php
        }
    } else {
        ?>
        <div class="search_res_post">
            No posts found
        </div>
        <?php
    }
}
?>
