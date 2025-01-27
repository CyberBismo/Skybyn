<?php include_once "assets/header.php";

if (!isset($_SESSION['user'])) {
    ?><meta http-equiv="Refresh" content="0; url='./'" /><?php
}

if (isset($_GET['new'])) {?>
    <div class="page-container">
        <div class="page-head">
            Create New Page
        </div>
        <div class="new-page-create">
            <div class="left" id="left">
                <h3>What are pages for<span onclick="showLeft()"><i class="fa-solid fa-angles-right"></i></span></h3>
                <ul>
                    <li>Something</li>
                    <li>Something</li>
                    <li>Something</li>
                    <li>Something</li>
                    <li>Something</li>
                </ul>
            </div>
            <div class="form">
                <i class="fa-solid fa-sign-hanging"></i>
                <input type="text" name="group_name" id="ng-name" placeholder="Name of group" autofocus required>
                <i class="fa-solid fa-quote-right"></i>
                <textarea name="group_desc" id="ng-desc" placeholder="Who is this group for"></textarea>
                <div class="new-group-privacy">
                    <span><input type="radio" name="group_privacy" value="open" id="ng-p-open" checked><i class="fa-regular fa-eye"></i> Open</span>
                    <span><input type="radio" name="group_privacy" value="locked" id="ng-p-locked"><i class="fa-solid fa-key"></i> Locked</span>
                    <span><input type="radio" name="group_privacy" value="private" id="ng-p-private"><i class="fa-solid fa-user-secret"></i> Private</span>
                </div>
                <div id="lock-options" style="display: none">
                    <select name="group_lock_type" id="ng-lt" onchange="lockType(this)">
                        <option value="" hidden>- Select lock type -</option>
                        <option value="password">Password</option>
                        <option value="pin">PIN code</option>
                    </select>
                    <input type="password" name="group_password" id="lt-password" placeholder="Password" title="Enter a password to access the group" autocomplete="new-password" style="display: none">
                    <input type="password" name="group_pin" id="lt-pin" pattern="[0-9]{4,}" placeholder="PIN" title="Enter a PIN code to access the group" autocomplete="new-password" style="display: none">
                </div>
                
                <input type="submit" onclick="createGroup()" value="Create">
            </div>
        </div>
    </div>

    <script>
        const privacyInputs = document.getElementsByName('group_privacy');
        privacyInputs.forEach(element => {
            element.addEventListener('change', function () {
                const lockOptions = document.getElementById('lock-options');
                if (element.value == "locked") {
                    lockOptions.style.display = "block";
                } else {
                    lockOptions.style.display = "none";
                }
            });
        });

        function showLeft() {
            const left = document.getElementById('left');
            if (left.style.height == "auto") {
                left.style.height = "80px";
            } else {
                left.style.height = "auto";
            }
        }
        function lockType(x) {
            const pw = document.getElementById('lt-password');
            const pin = document.getElementById('lt-pin');
            if (x.value == "password") {
                pw.style.display = "block";
                pin.style.display = "none";
            }
            if (x.value == "pin") {
                pin.style.display = "block";
                pw.style.display = "none";
            }
        }
        function createGroup() {
            const name = document.getElementById('ng-name');
            const desc = document.getElementById('ng-desc');
            const privacy = document.getElementsByName('privacy').value;
            const lockType = document.getElementById('ng-lt').value;
            const password = document.getElementById('lt-password').value;
            const pin = document.getElementById('lt-pin').value;
            
            if (privacy === 'locked') {

                if (lockType === 'password') {
                    password = password;
                } else if (lockType === 'pin') {
                    pin = pin;
                }
            }
            
            const data = {
                group_name: name.value,
                group_desc: desc.value,
                group_privacy: privacy,
                group_lock_type: lockType,
                group_password: password,
                group_pin: pin,
            };

            $.ajax({
                url: '../assets/group_new.php',
                type: "POST",
                data: data,
            }).done(function (response) {
                var result = JSON.parse(response);
                var response = result.response;
                var message = result.message;

                if (response === "ok") {
                    window.location.href = "./group?id=" + message;
                }
                if (response === "error") {
                    alert(message);
                }
            });
        }
    </script>
<?php } else {?>
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
                        $p_name = html_entity_decode($page['name'], ENT_QUOTES | ENT_HTML5, 'UTF-8');
                        $p_desc = html_entity_decode($page['description'], ENT_QUOTES | ENT_HTML5, 'UTF-8');
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
                    <div class="pb-intro">No public pages found at this moment.<br>Come back later.</div>
                    <?php
                }
                ?>
            </div>
        </div>

        <script>
        </script>
        <?php }?>
    </body>
</html>