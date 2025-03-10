<?php include_once "assets/header.php";

if (!isset($_SESSION['user'])) {
    include_once "assets/forms/login-popup.php";
    return;
}

$checkMyGroups = $conn->prepare("SELECT * FROM group_members WHERE user = ?");
$checkMyGroups->bind_param("i", $uid);
$checkMyGroups->execute();
$myGroups = $checkMyGroups->get_result();
$checkMyGroups->close();

?>
    <div class="page-container">
        <div class="page-head">
            My Groups
        </div>
        <?php
        if ($myGroups->num_rows > 0) {
            ?><div class="group-list"><?php
            while($group = $myGroups->fetch_assoc()){
                $g_id = $group['group_id'];
                $g_name = html_entity_decode($group['name'], ENT_QUOTES | ENT_HTML5, 'UTF-8');
                $g_desc = html_entity_decode($group['description'], ENT_QUOTES | ENT_HTML5, 'UTF-8');
                $g_icon = $group['icon'];
                $g_wallpaper = $group['wallpaper'];
                $g_owner = $group['owner'];
                $g_locked = $group['locked'];

                $g_lock;

                if ($g_locked == "1") {
                    $g_lock = '<i class="fa-solid fa-lock"></i>';
                }
                ?>
                <div class="group-box" onclick="window.location.href='/group?id=<?=$g_id?>'">
                    <div class="group-wallpaper"><img src="<?=$g_wallpaper?>"></div>
                    <div class="group-icon"><img src="<?=$g_icon?>"></div>
                    <div class="group-info">
                        <span><?=$g_name.$g_lock?></span>
                        <p><?=$g_desc?></p>
                    </div>
                </div>
                <?php
            }
            ?></div><?php
        } else {
            ?>
            <div class="group-intro">
                <p>You are not in any groups</p>
                <button class="btn" onclick="window.location.href='?new'">Click here to create a group</button>
            </div>
            <?php
        }
        ?>
    </div>