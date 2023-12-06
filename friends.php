<?php include_once "assets/header.php";

if (!isset($_SESSION['user'])) {
    ?><meta http-equiv="Refresh" content="0; url='./'" /><?php
}
?>
        <div class="page-container">
            <div class="friend-list">
                <h3>Friends</h3>
                <div id="friend-list">
                    <?php
                    $myFriends = $conn->query("SELECT * FROM `friendship` WHERE `user_id`='$uid' AND `status`='accepted'");
                    $countFriends = $myFriends->num_rows;
                    if ($countFriends > 0) {
                        while($friendId = $myFriends->fetch_assoc()) {
                            $fid = $friendId['friend_id'];
                            $getFriendData = $conn->query("SELECT * FROM `users` WHERE `id`='$fid'");
                            $friendData = $getFriendData->fetch_assoc();
        
                            $friend_username = $friendData['username'];
                            $friend_avatar = "./".$friendData['avatar'];
                            
                            if ($friend_avatar == "./") {
                                $friend_avatar = "./assets/images/logo_faded_clean.png";
                            }
                            ?>
                            <div class="friend">
                                <div class="friend-user">
                                    <div class="friend-avatar"><img src="<?=$friend_avatar?>"></div>
                                    <div class="friend-name"><?=$friend_username?></div>
                                </div>
                                <div class="friend-actions">
                                    <div class="friend-action" onclick="window.location.href='./profile?u=<?=$friend_username?>'">
                                        <i class="fa-solid fa-user"></i>
                                    </div>
                                    <div class="friend-action" onclick="window.location.href='./chat?u=<?=$friend_username?>'">
                                        <i class="fa-solid fa-comments"></i>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                    }
                    ?>
                </div>
            </div>
        </div>

        <script>
            function friendship(friend,action) {
                const actions = document.getElementById('friend_action');
                $.ajax({
                    url: 'assets/friendship.php',
                    type: "POST",
                    data: {
                        friend : friend,
                        action : action
                    }
                }).done(function(response) {
                    window.location.reload();
                });
            }

            function avatarSize() {
                document.getElementById('avatar').style.width = window.innerWidth+"px";
            }
            //window.addEventListener("resize", avatarSize);

            function changeWallpaper() {
                const changeWallpaperElements = document.getElementsByClassName("changeWallpaper");
                const changeAvatarElements = document.getElementsByClassName("changeAvatar");

                for (let i = 0; i < changeWallpaperElements.length; i++) {
                    const element = changeWallpaperElements[i];

                    if (element.hasAttribute("hidden")) {
                        element.removeAttribute("hidden");
                    } else {
                        element.setAttribute("hidden", "");
                    }
                }
                for (let i = 0; i < changeAvatarElements.length; i++) {
                    const element = changeAvatarElements[i];

                    element.setAttribute("hidden", "");
                }
            }

            function changeAvatar() {
                const changeWallpaperElements = document.getElementsByClassName("changeWallpaper");
                const changeAvatarElements = document.getElementsByClassName("changeAvatar");

                for (let i = 0; i < changeAvatarElements.length; i++) {
                    const element = changeAvatarElements[i];

                    if (element.hasAttribute("hidden")) {
                        element.removeAttribute("hidden");
                    } else {
                        element.setAttribute("hidden", "");
                    }
                }
                for (let i = 0; i < changeWallpaperElements.length; i++) {
                    const element = changeWallpaperElements[i];

                    element.setAttribute("hidden", "");
                }
            }
        </script>
    </body>
</html>