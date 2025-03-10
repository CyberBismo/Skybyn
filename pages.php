<?php include_once "assets/header.php";

if (!isset($_SESSION['user'])) {
    include_once "assets/forms/login-popup.php";
}

$getPages = $conn->prepare("SELECT * FROM pages WHERE locked = '0'");
$getPages->execute();
$pages = $getPages->get_result();
$getPages->close();
?>
    <div class="page-container">
        <div class="page-head">
            Public Pages
        </div>
        <?php
        if ($pages->num_rows > 0) {
            ?><div class="group-list"><?php
            while($page = $pages->fetch_assoc()){
                $p_id = $page['page_id'];
                $p_name = html_entity_decode($page['name'], ENT_QUOTES | ENT_HTML5, 'UTF-8');
                $p_desc = html_entity_decode($page['description'], ENT_QUOTES | ENT_HTML5, 'UTF-8');
                $p_icon = $page['icon'];
                $p_wallpaper = $page['wallpaper'];
                $p_owner = $page['owner'];
                $p_locked = $page['locked'];

                $p_lock;

                if ($p_locked == "1") {
                    $p_lock = '<i class="fa-solid fa-lock"></i>';
                }
                ?>
                <div class="group-box" onclick="window.location.href='/page?id=<?=$p_id?>'">
                    <div class="group-wallpaper"><img src="<?=$p_wallpaper?>"></div>
                    <div class="group-icon"><img src="<?=$p_icon?>"></div>
                    <div class="group-info">
                        <span><?=$p_name.$p_lock?></span>
                        <p><?=$p_desc?></p>
                    </div>
                </div>
                <?php
            }
            ?></div><?php
        } else {
            ?>
            <div class="group-intro">
                <p>Be the first to create a page</p>
                <button class="btn" onclick="window.location.href='?new'">Click here to create a page</button>
            </div>
            <?php
        }
        ?>
    </div>