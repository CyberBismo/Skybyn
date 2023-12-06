<?php include_once "assets/header.php";

if (!isset($_SESSION['user'])) {
    ?><meta http-equiv="Refresh" content="0; url='./'" /><?php
}
?>
        <div class="page-container">
            <div class="page-head">
                Browse Pages
            </div>
            <div class="pages-browse">
                <!--div class="pb-box">
                    <div class="pb-wallpaper"><img src="/assets/images/clouds-old.png"></div>
                    <div class="pb-icon"><img src="/assets/images/logo_fav.png"></div>
                    <div class="pb-info">
                        <span>Name</span>
                        <p>Description</p>
                    </div>
                </div-->
                <?php $getPages = $conn->query("SELECT * FROM `pages` WHERE `locked`='0'");
                if ($getPages->num_rows > 0) {
                    while($page = $getPages->fetch_assoc()){
                        $p_id = $page['id'];
                        $p_name = $page['name'];
                        $p_desc = $page['description'];
                        $p_icon = $page['icon'];
                        $p_wallpaper = $page['wallpaper'];
                        $p_owner = $page['owner'];
                        $p_locked = $page['locked'];

                        $g_lock;

                        if ($g_locked == "1") {
                            $g_lock = '<i class="fa-solid fa-lock"></i>';
                        }
                        ?>
                        <div class="pb-box" onclick="window.location.href='/page?id=<?=$p_id?>'">
                            <div class="pb-wallpaper"><img src="<?=$p_wallpaper?>"></div>
                            <div class="pb-icon"><img src="<?=$p_icon?>"></div>
                            <div class="pb-info">
                                <span><?=$p_name.$g_lock?></span>
                                <p><?=$p_desc?></p>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    ?>
                    <div class="pb-intro">Be first to create a page</div>
                    <?php
                }
                ?>
            </div>
        </div>

        <script>
        </script>
    </body>
</html>