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
    }
}
?>
