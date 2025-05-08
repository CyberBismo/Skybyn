<?php include_once "./assets/header.php";

if ($devDomain == true) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

if (!isset($_SESSION['user'])) {
    $currentURl = $_SERVER['REQUEST_URI'];
    createCookie("redirect", $currentURl, time() + 3600, "/");
    include "./assets/forms/login-popup.php";
    return;
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

$avatar_bg = "background: black";
?>
        <div class="page-container">
            <div class="wallpaper">
                <img src="<?=$wallpaper?>">
            </div>
            <div class="profile">
                <div class="profile-left">
                    <div class="profile-left-user">
                        <div class="avatar" style="<?=$avatar_bg?>" id="avatar">
                            <img src="<?=$avatar?>">
                        </div>
                        <i class="fa-regular fa-pen-to-square" onclick="changeAvatar()"></i>
                        <div class="username">
                            <?=$username?>
                            <span>@<?=$username?></span>
                        </div>
                    </div>
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
                        <form method="post" enctype="multipart/form-data">

                            <label class="wallpaper_select_area" for="wallpaper_select" id="wallpaper_preview">
                                <i class="fa-solid fa-camera"></i>
                            </label>
                            <input type="file" name="wallpaper" id="wallpaper_select" hidden required>
                            
                            <br>

                            <input type="submit" name="update_wallpaper" value="Update wallpaper">
                        </form>
                        <br><br>
                        <form method="post">

                            <h3>Account</h3>
                            <i class="fa-solid fa-at"></i>
                            <input type="email" name="email" value="<?=$email?>" required>
                            
                            <i class="fa-solid fa-user"></i>
                            <input type="text" name="username" value="<?=$username?>" onkeyup="checkUsername(this)" required>
                            
                            <i class="fa-solid fa-calendar-days"></i>
                            <input type="date" name="dob" value="<?=$dob?>" min="1960-01-01" max="<?=date("Y")-15 ."-".date("m")."-".date("d")?>"<?php if ($rank < 1) {?> disabled<?php }?> title="Enter your date of birth"/>
                            
                            <br><br>

                            <input type="submit" name="update_account" value="Update account">
                        </form>
                        <br><br>
                        <form method="post">

                            <h3>Name</h3>
                            <i class="fa-solid fa-user"></i>
                            <input type="text" name="title_name" value="<?=$title_name?>" placeholder="Title name" required>
                            
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
                        $getIPhistory = $conn->query("SELECT * FROM `ip_logs` WHERE `user`='$uid' ORDER BY `date` DESC");
                        while($ipData = $getIPhistory->fetch_assoc()) {
                            $ip_date = $ipData['date'];
                            $ip_address = $ipData['ip'];
                            ?>
                            <div class="ip-log">
                                <div class="ip-address">
                                    <h3><?=$ip_address?></h3>
                                    <?=date("D d .M Y - h:i:s", $ip_date)?>
                                </div>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener("DOMContentLoaded", function() {
                var wallpaperSelect = document.getElementById('wallpaper_select');
                var wallpaperPreview = document.getElementById('wallpaper_preview');

                // Function to update the preview with the selected image
                function updatePreview(file) {
                    if (file.type.startsWith('image/')) {
                        var reader = new FileReader();
                        reader.onload = function(e) {
                            wallpaperPreview.innerHTML = '<img src="' + e.target.result + '" alt="wallpaper">';
                        };
                        reader.readAsDataURL(file);
                    } else {
                        alert("Please select an image file.");
                    }
                }

                // Handling file selection via input
                wallpaperSelect.addEventListener('change', function() {
                    if (this.files.length > 0) {
                        updatePreview(this.files[0]);
                    }
                });

                // Handling drag and drop
                wallpaperPreview.addEventListener('dragover', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    wallpaperPreview.style.backgroundColor = '#f0f0f0'; // Optional: visual feedback
                });

                wallpaperPreview.addEventListener('dragleave', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    wallpaperPreview.style.backgroundColor = ''; // Optional: reset visual feedback
                });

                wallpaperPreview.addEventListener('drop', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    wallpaperPreview.style.backgroundColor = ''; // Optional: reset visual feedback
                    var dt = e.dataTransfer;
                    var file = dt.files[0];
                    updatePreview(file);
                    wallpaperSelect.files = dt.files; // Update the file input
                });
            });
            document.getElementById('wallpaper_select').addEventListener('change', function(event) {
                // Get the file list from the input
                var files = event.target.files;

                // Check if any files are selected
                if (files.length > 0) {
                    var file = files[0];

                    // Check if the file is an image
                    if (file.type.startsWith('image/')) {
                        // Create a FileReader to read the file
                        var reader = new FileReader();

                        // Define the onload event handler for the FileReader
                        reader.onload = function(e) {
                            // Set the preview's background to the image
                            var preview = document.getElementById('wallpaper_preview');
                            preview.style.backgroundImage = 'url(' + e.target.result + ')';
                            preview.style.backgroundSize = 'cover';
                            preview.style.backgroundPosition = 'center';
                            
                            // Optionally, remove the icon if you want
                            var icon = preview.querySelector('i.fa-camera');
                            if(icon) {
                                icon.style.display = 'none';
                            }
                        };

                        // Read the file as a Data URL
                        reader.readAsDataURL(file);
                    } else {
                        // Handle non-image file types or alert the user
                        alert('Please select an image file.');
                    }
                }
            });

            function wallpaperSize() {
                document.getElementById('wallpaper').style.width = window.innerWidth+"px";
            }
            //window.addEventListener("resize", wallpaperSize);

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

        <div class="changeAvatar" hidden>
            <i class="fa-solid fa-xmark" onclick="changeAvatar()"></i>
            <form method="post" enctype="multipart/form-data">
                <h3>Change avatar</h3>
                <img src="<?=$Pavatar?>" id="previewavatar">
                <div class="changeBtns">
                    <input type="file" name="avatar" id="setavatar" accept="image/png, image/jpeg, image/gif" onchange="preViewAvatar(this)">
                    <input type="submit" name="update_avatar" value="Update">
                </div>
            </form>
        </div>
    
        <script>
            function preViewAvatar(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        document.getElementById('previewavatar').src = e.target.result;
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            }
            function stickyProfile() {
                const avatar = document.getElementById('profile-left');
                avatar.style.height = window.innerHeight - 125 +"px";
            }

            function avatarSize() {
                document.getElementById('avatar').style.width = window.innerWidth+"px";
            }
            //window.addEventListener("resize", avatarSize);

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
        <?php if (isMobile($userAgent) == false) {?>
        <script>stickyProfile();</script>
        <?php }?>
    </body>
</html>