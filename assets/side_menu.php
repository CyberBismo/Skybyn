<div class="side_menu_back" id="side-menu" onclick="hideSideMenu(event, this)" hidden>
    <div class="side_menu">
        <div class="menu_profile">
            <div class="profile_wallpaper">
                <img src="<?=$wallpaper?>">
            </div>
            <div class="profile_image">
                <img src="<?=$avatar?>">
            </div>
            <div class="profile_name"><?=$username?></div>
        </div>
        <div class="menu_item" onclick="window.location.href='./'">
            <i class="fa-solid fa-house"></i>
            <p>Home</p>
        </div>
        <div class="menu_item" onclick="window.location.href='./profile'">
            <i class="fa-solid fa-user"></i>
            <p>My profile</p>
        </div>
        <div class="menu_item" onclick="window.location.href='./settings'">
            <i class="fa-solid fa-gears"></i>
            <p>Settings</p>
        </div>
    </div>
</div>