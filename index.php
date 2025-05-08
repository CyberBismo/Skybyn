<?php include_once "assets/header.php";?>

<?php if (isset($_SESSION['user'])) {?>
<div class="page-container" id="feed">
    <div class="posts" id="posts">
        <?php include('assets/feed.php');?>
    </div>
</div>
<?php } else {
if (isset($_COOKIE['logged'])) {
    $logged_in = substr($_COOKIE['logged'], 4);
    $checkUserCookie = $conn->query("SELECT `id` FROM `users` WHERE `id`='$logged_in'");
    if ($checkUserCookie->num_rows == 1) {
        $_SESSION['user'] = $logged_in;
        ?><script>window.location.href = '../';</script><?php
    } else {
        ?><script>window.location.href = '../logout';</script><?php
    }
}
?>

<div class="start">
    <div class="welcome_information" id="welcome_info" onclick="showInfo(this)">
        <div class="info_text" id="info_text">
            <?php include_once "assets/intro.php"?>
        </div>
        <div class="reg_info" id="reg_info">
            <h2>Your information:</h2>
            <table id="reg_table"></table>
        </div>
    </div>
    <?php if (isset($_GET['reset'])) {
        $code = $_GET['reset'];?>
        <div class="center_form">
            <div class="form">
                <?php include("assets/forms/reset.php");?>
            </div>
        </div>
    <?php } else
    if (isset($_GET['forgot'])) {?>
    <div class="center_form">
        <div class="form">
            <?php include("assets/forms/forgot.php");?>
        </div>
    </div>
    <?php } else
    if (isset($_COOKIE['new_IP'])) {?>
    <div class="center_form">
        <div class="form">
            <?php include("assets/forms/new_ip.php");?>
        </div>
    </div>
    <?php } else {?>
    <div class="center_form" id="log_reg_form">
        <div class="form">
            <div class="login" id="login-form" <?php if ($signup == true) {?>hidden<?php }?>>
                <?php include("assets/forms/login.php");?>
            </div>
            <div class="register" id="register-form" <?php if ($signup == false) {?>hidden<?php }?>>
                <?php include("assets/forms/register.php");?>
            </div>
        </div>
        <?php if (skybyn('register') == "1" || $beta == true) {
            if ($signup == false) {?>
        <div class="reg-button" id="signup-btn">
            <span onclick="window.location.href='../register'">Sign up</span>
            <span onclick="window.location.href='../forgot'">Forgot password?</span>
        </div>
        <?php } else {?>
        <div class="reg-button" id="signup-btn">
            <span onclick="window.location.href='../'" id="login-btn">Login here</span>
            <span onclick="window.location.href='../forgot'">Forgot password?</span>
        </div>
        <?php }}?>
    </div>

    <script>
        function showInfo(x) {
            <?php if (isMobile($userAgent) == true) {?>
            if (x.style.height == "50%") {
                x.style.height = "75px";
            } else {
                x.style.height = "50%";
            }
            <?php }?>
        }
    </script>

    <div class="popup" id="terms_popup">
        <div class="popup-close" onclick="showTerms()">
            <i class="fa-solid fa-xmark"></i>
        </div>
        <h2>Terms and Conditions</h2>
        <p class="terms">
            <?php
            $file = fopen("assets/terms.txt", "r");
            while(!feof($file)) {
            echo fgets($file) . "<br>";
            }
            fclose($file);
            ?>
        </p>
    </div>

    <script>
        function showRegister(btn) {
            const register = document.getElementById('register-form');

            if (register.style.display == "block") {
                window.location.href= './';
            } else {
                window.location.href= './register';
            }
        }
    </script>

    <script>
        function showTerms() {
            const terms = document.getElementById('terms_popup');
            const popup = document.getElementById('forgot_popup');
            if (terms.style.display == "block") {
                terms.style.display = "none";
            } else {
                terms.style.display = "block";
                popup.style.display = "none";
            }
        }
    </script>
    <?php }?>

    <?php if (isset($_COOKIE['msg'])) { ?>
        <div class="msg">
            <span><?php echo $_COOKIE['msg']; ?></span>
        </div>
    <?php } ?>
    <script>
        setTimeout(function() {
            const msgElement = document.querySelector('.msg');
            if (msgElement) {
                msgElement.style.display = 'none';
            }
        }, 5000);
    </script>
</div>

<div class="reg-packs" id="reg_packs" style="display: none">
    <h3>Select one of following options</h3>
    <div class="reg-packs-box">
        <div class="reg-pack">
            <div class="reg-pack-box">
                <h2>Open Profile</h2>
                <ul>
                    <li>You appear in search</li>
                    <li>Your profile is visible</li>
                    <li>Anyone can message you</li>
                    <li>You appear for new users</li>
                </ul>
                <p id="open-set-btn"><button onclick="selectPackage('op')">Select</button></p>
            </div>
        </div>
        <div class="reg-pack">
            <div class="reg-pack-box">
                <h2>Private Profile</h2>
                <ul>
                    <li>You do not appear in search</li>
                    <li>Your profile is invisible</li>
                    <li>Only friends can message you</li>
                </ul>
                <p id="private-set-btn"><button onclick="selectPackage('pp')">Select</button></p>
            </div>
        </div>
        <div class="reg-pack" id="reg-pack-custom">
            <div class="reg-pack-box" onclick="showCustom()">
                <h2>Custom</h2>
                <span id="custom-text">Set each setting manually</span>
                <div class="reg-pack-box-custom" id="custom-set" style="display: none">

                    <div class="rpbcb">
                        <p><b>Privacy</b><br>
                            A private profile will only show your picture and display name.
                        </p>
                        <table>
                            <tr onclick="document.getElementById('ppr').click();customSelect('ppr')" id="tr_ppr">
                                <td><input type="radio" name="private" value="1" id="ppr"></td>
                                <td><label for="ppr">Private</label></td>
                            </tr>
                            <tr onclick="document.getElementById('ppu').click();customSelect('ppu')" id="tr_ppu">
                                <td><input type="radio" name="private" value="0" id="ppu"></td>
                                <td><label for="ppu">Public</label></td>
                            </tr>
                        </table>
                    </div>

                    <div class="rpbcb">
                        <p><b>Visibility</b><br>
                            As visible you will appear in search results.
                        </p>
                        <table>
                            <tr onclick="document.getElementById('vv').click();customSelect('vv')" id="tr_vv">
                                <td><input type="radio" name="visible" value="1" id="vv"></td>
                                <td><label for="vv">Visible</label></td>
                            </tr>
                            <tr onclick="document.getElementById('vi').click();customSelect('vi')" id="tr_vi">
                                <td><input type="radio" name="visible" value="0" id="vi"></td>
                                <td><label for="vi">Invisible</label></td>
                            </tr>
                        </table>
                    </div>

                </div>
                <p id="custom-set-btn" style="display: none"><button onclick="selectPackage('cp')">Confirm and select</button></p>
            </div>
        </div>
    </div>
    <button onclick="stepBack()">Go back</button>

    <script>
        function customSelect(x) {
            if (x == "ppr") {
                document.getElementById('tr_ppr').style.background = "rgba(var(--mode), .2)";
                document.getElementById('tr_ppu').style.background = "none";
            } else if (x == "ppu") {
                document.getElementById('tr_ppu').style.background = "rgba(var(--mode), .2)";
                document.getElementById('tr_ppr').style.background = "none";
            } else if (x == "vv") {
                document.getElementById('tr_vv').style.background = "rgba(var(--mode), .2)";
                document.getElementById('tr_vi').style.background = "none";
            } else if (x == "vi") {
                document.getElementById('tr_vi').style.background = "rgba(var(--mode), .2)";
                document.getElementById('tr_vv').style.background = "none";
            }
        }
    </script>
</div>
<?php }?>