<?php include_once "assets/header.php";

if (!isset($_SESSION['user'])) {
    include_once "assets/forms/login-popup.php";
}

if (isset($_GET['new'])) {?>
    <div class="page-container">
        <div class="page-head">
            Create New Page
        </div>
        <div class="new-page-create">
            <div class="page-intro">
                <h3>What are pages for?</h3>
                <p>Pages are a way to share your interests with others.<br>
                You can create a page for a group, a project, or anything else you want to share.<br>
                Pages can be public or private, and you can choose who can see them.</p>
            </div>
            <div class="form">
                <i class="fa-solid fa-sign-hanging"></i>
                <input type="text" name="page_name" id="ng-name" placeholder="Name of page" autofocus required>
                <i class="fa-solid fa-quote-right"></i>
                <textarea name="page_desc" id="ng-desc" placeholder="Who is this page for"></textarea>
                <div class="new-page-privacy">
                    <input type="radio" name="page_privacy" value="open" id="np-p-open" checked>
                    <input type="radio" name="page_privacy" value="locked" id="np-p-locked">
                    <input type="radio" name="page_privacy" value="private" id="np-p-private">
                    <label for="np-p-open" onclick="selectPrivacy()"><i class="fa-solid fa-globe"></i> Public</label>
                    <label for="np-p-locked" onclick="selectPrivacy()"><i class="fa-solid fa-key"></i> Locked</label>
                    <label for="np-p-private" onclick="selectPrivacy()"><i class="fa-solid fa-user-secret"></i> Private</label>
                </div>
                <div id="lock-options" style="display: none">
                    <select name="page_lock_type" id="ng-lt" onchange="lockType(this)">
                        <option value="" hidden>- Select lock type -</option>
                        <option value="password">Password</option>
                        <option value="pin">PIN code</option>
                    </select>
                    <input type="password" name="page_password" id="lt-password" placeholder="Set Password" title="Enter a password to access the page" autocomplete="new-password" style="display: none">
                    <input type="password" name="page_pin" id="lt-pin" pattern="[0-9]{4,}" placeholder="Set PIN code" title="Enter a PIN code to access the page" autocomplete="new-password" style="display: none">
                </div>
                
                <input type="submit" onclick="createPage()" value="Create">
            </div>
        </div>
    </div>

    <script>
        const privacyInputs = document.getElementsByName('page_privacy');
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
        function selectPrivacy() {
            const privacyInputs = document.getElementsByName('page_privacy');
            privacyInputs.forEach(element => {
                if (element.checked) {
                    element.checked = false;
                }
            });
            this.children[0].checked = true;
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
        function createPage() {
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
                page_name: name.value,
                page_desc: desc.value,
                page_privacy: privacy,
                page_lock_type: lockType,
                page_password: password,
                page_pin: pin,
            };

            $.ajax({
                url: '../assets/page_new.php',
                type: "POST",
                data: data,
            }).done(function (response) {
                var result = JSON.parse(response);
                var response = result.response;
                var message = result.message;

                if (response === "ok") {
                    window.location.href = "./page?id=" + message;
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
                    <div class="pb-intro" onclick="window.location.href='?new'">Be the first to create a page</div>
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