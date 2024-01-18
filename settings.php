<?php include_once "assets/header.php";
if (!isset($_SESSION['user'])) {
    ?><meta http-equiv="Refresh" content="0; url='./'" /><?php
}

if (isset($_GET['security'])) {
    $general = "hidden";
    $security = "";
    $ip_history = "hidden";
} else
if (isset($_GET['ip_history'])) {
    $general = "hidden";
    $security = "hidden";
    $ip_history = "";
} else {
    $general = "";
    $security = "hidden";
    $ip_history = "hidden";
}
?>
        <div class="page-container">
            <div class="profile-wallpaper">
                <img src="<?=$wallpaper?>">
                <!--i class="fa-regular fa-pen-to-square" onclick="changeWallpaper()" hidden></i-->
            </div>
            <div class="profile">
                <div class="profile-left">
                    <i class="fa-regular fa-pen-to-square" onclick="changeWallpaper()"></i>
                    <div class="profile-left-user">
                        <div class="avatar" id="avatar">
                            <img src="<?=$avatar?>">
                            <i class="fa-regular fa-pen-to-square" onclick="changeAvatar()"></i>
                        </div>
                        <div class="username">
                            <?=$username?>
                            <span>@<?=$username?></span>
                        </div>
                    </div>
                    <hr>
                    <div class="profile-tabs">
                        <div class="settings-cat" onclick="setTab('general')">
                            <div class="settings-icon">
                                <i class="fa-solid fa-user"></i>
                            </div>
                            <div class="settings-name">General</div>
                        </div>
                        <div class="settings-cat" onclick="setTab('security')">
                            <div class="settings-icon">
                                <i class="fa-solid fa-shield-halved"></i>
                            </div>
                            <div class="settings-name">Security</div>
                        </div>
                        <div class="settings-cat" onclick="setTab('ip_history')">
                            <div class="settings-icon">
                                <i class="fa-solid fa-location-dot"></i>
                            </div>
                            <div class="settings-name">IP History</div>
                        </div>
                        <!--div class="settings-cat" onclick="setTab('visibility')">
                            <div class="settings-icon">
                                <i class="fa-solid fa-eye"></i>
                            </div>
                            <div class="settings-name">Visibility</div>
                        </div-->
                    </div>
                </div>
                <div class="profile-right form">
                    <div id="tab-general" <?=$general?>>
                        <form method="post">

                            <h3>Account</h3>
                            <i class="fa-solid fa-at"></i>
                            <input type="email" name="email" value="<?=$email?>" required>
                            
                            <i class="fa-solid fa-user"></i>
                            <input type="text" name="username" value="<?=$username?>" onkeyup="checkUsername(this)" required>
                            
                            <?php if ($rank > 0) {?>
                            <i class="fa-solid fa-calendar-days"></i>
                            <input type="date" name="dob" value="<?=$dob?>" min="1960-01-01" max="<?=date("Y")-15 ."-".date("m")."-".date("d")?>" title="Enter your date of birth"/>
                            <?php }?>
                            
                            <br><br>

                            <input type="submit" name="update_account" value="Update account">
                        </form>
                        <br><br>
                        <form method="post">

                            <h3>Name</h3>
                            <i class="fa-solid fa-user"></i>
                            <input type="text" name="title_name" value="<?=$title_name?>" placeholder="Title">
                            
                            <i class="fa-solid fa-user"></i>
                            <input type="text" name="first_name" value="<?=$first_name?>" placeholder="First name" required>
                            
                            <i class="fa-solid fa-user"></i>
                            <input type="text" name="middle_name" value="<?=$middle_name?>" placeholder="Middle name">
                            
                            <i class="fa-solid fa-user"></i>
                            <input type="text" name="last_name" value="<?=$last_name?>" placeholder="Last name" required>

                            <br><br>

                            <input type="submit" name="update_name" value="Update name">
                        </form>
                    </div>
                    
                    <div id="tab-security" <?=$security?>>
                        <form method="post">
                            <h3>Password</h3>
                            <i class="fa-solid fa-key"></i>
                            <input type="password" name="password" placeholder="Old password" required>
                            
                            <i class="fa-solid fa-key"></i>
                            <input type="password" name="password" placeholder="New password" required>
                            
                            <i class="fa-solid fa-key"></i>
                            <input type="password" name="password" placeholder="Confirm new password" required>

                            <br><br>

                            <input type="submit" name="update_password" value="Update password">
                        </form>
                        <?php if ($rank > 0) {?>
                        <form method="post">
                            <hr>

                            <h3>PIN code</h3>
                            <i class="fa-solid fa-key"></i>
                            <select name="pinv" style="width: 100%; margin-bottom: 5px; padding: 11px 20px; padding-left: 50px; background: white; border: none; border-radius: 10px; outline: none" onchange="setPIN(this)">
                                <option disabled selected>-- Select --</option>
                                <option value="4">4 digit</option>
                                <option value="6">6 digit</option>
                                <option value="8">8 digit</option>
                            </select>
                            
                            <div id="set_pin" hidden>
                                <i class="fa-solid fa-key"></i>
                                <input type="password" name="pin" id="pin" placeholder="Enter PIN code" required>
                                
                                <i class="fa-solid fa-key"></i>
                                <input type="password" name="cpin" id="cpin" placeholder="Confirm PIN code" required>
                            </div>
                            
                            <br><br>

                            <input type="submit" name="update_pin" value="Update PIN">
                        </form>
                        <?php }?>
                    </div>
                    
                    <div id="tab-ip_history" <?=$ip_history?>>
                        <h3>IP History</h3>
                        <?php
                        $getIPhistory = $conn->query("SELECT * FROM `ip_history` WHERE `user_id`='$uid' ORDER BY `date` DESC");
                        while($ipData = $getIPhistory->fetch_assoc()) {
                            $ip_date = $ipData['date'];
                            $ip_address = $ipData['ip'];
                            $ip_trusted = $ipData['trusted'];
                            $ip_code = $ipData['code'];
                            ?>
                            <div class="ip-log">
                                <div class="ip-address">
                                    <h3><?=$ip_address?></h3>
                                    <?=date("D d .M Y - h:i:s", $ip_date)?>
                                </div>
                                <?php if ($rank > 0) {?>
                                <div class="ip-actions">
                                    <?php if ($ip_trusted == "1") {?>
                                    <button class="ip-action" onclick="ipHistory('untrust','<?=$ip_address?>')" onmouseover="this.innerHTML='Untrust'" onmouseleave="this.innerHTML='Trusted'">Trusted</button>
                                    <?php } else {?>
                                    <button class="ip-action" onclick="ipHistory('trust','<?=$ip_address?>')">Trust</button>
                                    <?php }?>
                                    <button class="ip-action" onclick="ipHistory('remove','<?=$ip_address?>')">Remove</button>
                                </div>
                                <?php }?>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>

        <script>
            function avatarSize() {
                document.getElementById('avatar').style.width = window.innerWidth+"px";
            }
            //window.addEventListener("resize", avatarSize);

            function setTab(tab) {
                const general = document.getElementById('tab-general');
                const security = document.getElementById('tab-security');
                const iplog = document.getElementById('tab-ip_history');

                if (tab == "general") {
                    general.hidden = false;
                    security.hidden = true;
                    iplog.hidden = true;
                }
                if (tab == "security") {
                    general.hidden = true;
                    security.hidden = false;
                    iplog.hidden = true;
                }
                if (tab == "ip_history") {
                    general.hidden = true;
                    security.hidden = true;
                    iplog.hidden = false;
                }

                var updatedParameter = '?' + tab;
                window.location.href = updatedParameter;
            }

            function checkUsername(x) {
                if (x.value == "<?=$username?>") {
                    console.clear();
                    x.style.outline = "none";
                } else
                if (x.value.length >= 4) {
                    console.log("Checking username availability..");
                    $.ajax({
                        url: 'assets/check_username.php',
                        type: "POST",
                        data: {
                            username : x.value
                        }
                    }).done(function(response) {
                        if (response == "available") {
                            console.log("Username available");
                            x.style.outline = "1px solid green";
                        } else {
                            console.log("Username unavailable");
                            x.style.outline = "1px solid red";
                        }
                    });
                } else {
                    console.log("Username must be 4+ characters long");
                    x.style.outline = "none";
                }
            }

            function setPIN(x) {
                const setPIN = document.getElementById('set_pin');
                const pin = document.getElementById('pin');
                const cpin = document.getElementById('cpin');

                if (setPIN.hasAttribute('hidden')) {
                    setPIN.removeAttribute('hidden');
                }

                if (x.value === "4") {
                    pin.value = "";
                    cpin.value = "";
                    pin.maxLength = 4;
                    cpin.maxLength = 4;
                }
                if (x.value === "6") {
                    pin.value = "";
                    cpin.value = "";
                    pin.maxLength = 6;
                    cpin.maxLength = 6;
                }
                if (x.value === "8") {
                    pin.value = "";
                    cpin.value = "";
                    pin.maxLength = 8;
                    cpin.maxLength = 8;
                }
            }
            
            function ipHistory(x,ip) {
                if (x == "remove") {
                    $.ajax({
                        url: 'assets/ip_history.php',
                        type: "POST",
                        data: {
                            action: 'remove',
                            ip: ip
                        }
                    }).done(function(response) {
                        
                    });
                } else
                if (x == "trust") {
                    $.ajax({
                        url: 'assets/ip_history.php',
                        type: "POST",
                        data: {
                            action: 'trust',
                            ip: ip
                        }
                    }).done(function(response) {
                    });
                } else
                if (x == "untrust") {
                    $.ajax({
                        url: 'assets/ip_history.php',
                        type: "POST",
                        data: {
                            action: 'untrust',
                            ip: ip
                        }
                    }).done(function(response) {
                    });
                }
            }
        </script>
    </body>
</html>