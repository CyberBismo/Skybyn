<?php
include_once "../functions.php";

$text = strtoupper($_POST['text']);

if (strpos($text, "@") === 0) {
    // User search
    $username = substr($text, 1); // 1 is the length of "@"
    $stmt = $conn->prepare("SELECT * FROM `users` WHERE `username` LIKE UPPER(?)");
    $likeUsername = $username . '%';
    $stmt->bind_param("s", $likeUsername);
    $stmt->execute();
    $getUsers = $stmt->get_result();

    if ($getUsers->num_rows > 0) {
        $users = [];
        while ($user = $getUsers->fetch_assoc()) {
            $userid = $user['id'];
            $username = $user['username'];
            $avatar = "../" . $user['avatar'];

            if ($avatar == "../") {
                $avatar = "../assets/images/logo_faded_clean.png";
            }
            
            $users[] = [
                'id' => $userid,
                'username' => $username,
                'avatar' => $avatar
            ];
        }

        echo json_encode(['user' => $users]);
    } else {
        echo json_encode(['user' => []]);
    }
} else if (strpos($text, "page: ") === 0) {
    // Page search
    $pageName = substr($text, 6); // 6 is the length of "page: "
    $getPages = $conn->prepare("SELECT * FROM `pages` WHERE `name` LIKE ? OR `id` LIKE ?");
    $likePageName = $pageName . '%';
    $getPages->bind_param("ss", $likePageName, $likePageName);
    $getPages->execute();
    $getPages = $getPages->get_result();

    if ($getPages->num_rows > 0) {
        $pages = [];
        while ($page = $getPages->fetch_assoc()) {
            $pid = $page['id'];
            $name = $page['name'];
            $icon = "../" . $page['icon'];

            if ($icon == "../") {
                $icon = "../assets/images/logo_faded_clean.png";
            }
            
            $pages[] = [
                'pid' => $pid,
                'name' => $name,
                'icon' => $icon
            ];
        }

        echo json_encode(['page' => $pages]);
    } else {
        echo json_encode(['page' => []]);
    }
} else if (strpos($text, "group: ") === 0) {
    // Group search
    $groupName = substr($text, 6); // 6 is the length of "group: "
    $getGroups = $conn->prepare("SELECT * FROM `groups` WHERE `name` LIKE ? OR `id` LIKE ?");
    $likeGroupName = $groupName . '%';
    $getGroups->bind_param("ss", $likeGroupName, $likeGroupName);
    $getGroups->execute();
    $getGroups = $getGroups->get_result();

    if ($getGroups->num_rows > 0) {
        $groups = [];
        while ($group = $getGroups->fetch_assoc()) {
            $gid = $group['id'];
            $name = $group['name'];
            $icon = "../" . $group['icon'];

            if ($icon == "../") {
                $icon = "../assets/images/logo_faded_clean.png";
            }
            
            $groups[] = [
                'gid' => $gid,
                'name' => $name,
                'icon' => $icon
            ];
        }

        echo json_encode(['group' => $groups]);
    } else {
        echo json_encode(['group' => []]);
    }
} else {
    // Post search
    $getPost = $conn->prepare("SELECT * FROM `posts` WHERE `content` LIKE UPPER(?)");
    $likeContent = '%' . $text . '%';
    $getPost->bind_param("s", $likeContent);
    $getPost->execute();
    $getPost = $getPost->get_result();

    if ($getPost->num_rows > 0) {
        $posts = [];
        while ($post = $getPost->fetch_assoc()) {
            $pid = $post['id'];
            $uid = $post['uid'];
            $content = $post['content'];
            $time = $post['time'];

            $getUser = $conn->query("SELECT * FROM `users` WHERE `id` = '$uid'");
            $user = $getUser->fetch_assoc();
            $username = $user['username'];
            $avatar = "../" . $user['avatar'];

            if ($avatar == "../") {
                $avatar = "../assets/images/logo_faded_clean.png";
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
            
            $posts[] = [
                'pid' => $pid,
                'uid' => $uid,
                'username' => $username,
                'avatar' => $avatar,
                'content' => $content,
                'time' => $time,
                'likes' => $likes,
                'comments' => $comments,
                'shares' => $shares,
                'reposts' => $reposts,
                'views' => $views,
                'replies' => $replies
            ];
        }

        echo json_encode(['post' => $posts]);
    } else {
        echo json_encode(['post' => []]);
    }
}
?>
