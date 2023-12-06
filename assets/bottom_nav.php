<?php if (isset($_SESSION['user'])) {?>
        <div class="bottom-navigation">
            <div class="nav-holder">
                <div class="nav-button" onclick="showSideMenu()"><i class="fa-solid fa-bars"></i></div>
                <div class="nav-button" onclick="window.location.href='./home'"><i class="fa-solid fa-house"></i></div>
                <div class="nav-button center" onclick="window.location.href='./share'"><i class="fa-solid fa-keyboard"></i></div>
                <div class="nav-button" onclick="window.location.href='./messages'"><i class="fa-solid fa-comments"></i></div>
                <div class="nav-button" onclick="showFriends()"><i class="fa-solid fa-user-group"></i></div>
            </div>
        </div>

        <script>
            function showSideMenu() {
                const sideMenu = document.getElementById('side-menu');

                if (sideMenu.style.display == "block") {
                    sideMenu.style.display = "none";
                } else {
                    sideMenu.style.display = "block"
                }
            }
            function hideSideMenu(event, element) {
                const sideMenu = document.getElementById('side-menu');
                
                if (event.target == element) {
                    if (sideMenu.style.display == "none") {
                        sideMenu.style.display = "block";
                    } else {
                        sideMenu.style.display = "none"
                    }
                }
            }
            
            function showFriends() {
                const friends = document.getElementById('friend-list');

                if (friends.style.display == "block") {
                    friends.style.display = "none";
                } else {
                    friends.style.display = "block"
                }
            }
            function hideFriends(event, element) {
                const friends = document.getElementById('friend-list');
                
                if (event.target == element) {
                    if (friends.style.display == "none") {
                        friends.style.display = "block";
                    } else {
                        friends.style.display = "none"
                    }
                }
            }
        </script>
<?php }?>