<?php include_once "../assets/header.php"?>
        <div class="page-container">
            <div class="page-head">
                Groups
            </div>
            <div class="groups-browse">
                <?php $getGroups = $conn->query("SELECT * FROM `groups` WHERE `privacy`!='private'");
                if ($getGroups->num_rows > 0) {
                    while($group = $getGroups->fetch_assoc()){
                        $g_id = $group['id'];
                        $g_name = $group['name'];
                        $g_desc = $group['description'];
                        $g_icon = $group['icon'];
                        $g_wallpaper = $group['wallpaper'];
                        $g_owner = $group['owner'];
                        $g_locked = $group['locked'];

                        $g_lock;

                        if ($g_locked == "1") {
                            $g_lock = '<i class="fa-solid fa-lock"></i>';
                        }
                        ?>
                        <div class="gb-box" onclick="window.location.href='../group/<?=$g_id?>'">
                            <div class="gb-wallpaper"><img src="<?=$g_wallpaper?>"></div>
                            <div class="gb-icon"><img src="<?=$g_icon?>"></div>
                            <div class="gb-info">
                                <span><?=$g_name.$g_lock?></span>
                                <p><?=$g_desc?></p>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    ?>
                    <div class="gb-intro" onclick="window.location.href='../newgroup'">Create a group</div>
                    <?php
                }
                ?>
            </div>
        </div>

        <script>
        </script>
    </body>
</html>