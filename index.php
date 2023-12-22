<?php include_once "assets/header.php";

if (isset($_GET['signup'])) {
	if (skybyn('register') == "1") {
		$signup = true;
	} else {
		$signup = false;
	}
} else {
	$signup = false;
}

?>
<?php if (isset($_SESSION['user'])) {?>
<div class="page-container" id="feed">
    <div class="posts" id="posts">
        <?php include('assets/feed.php');?>
    </div>
</div>

<script>
    function showPost(x) {
        window.location.href = "./post.php?id="+x;
    }
</script>
<?php } else {
    
if (isset($_COOKIE['logged'])) {
    $logged_in = substr($_COOKIE['logged'], 4);
    $checkUserCookie = $conn->query("SELECT `id` FROM `users` WHERE `id`='$logged_in'");
    if ($checkUserCookie->num_rows == 1) {
        $_SESSION['user'] = $logged_in;
        ?><meta http-equiv="refresh" content="0; URL='./'" /><?php
    } else {
        ?><meta http-equiv="refresh" content="0; URL='./logout'" /><?php
    }
}?>
<div class="reg-packs" id="reg_packs" style="display: none">
    <h3>Select one of following options</h3>
    <div class="reg-packs-box">
        <div class="reg-pack" onclick="hitEnterRegister('op')">
            <div class="reg-pack-box">
                <h2>Open Profile</h2>
                <ul>
                    <li>You appear in search</li>
                    <li>Your profile is visible</li>
                    <li>Anyone can message you</li>
                    <li>You appear for new users</li>
                </ul>
                <p>Click to Select</p>
            </div>
        </div>
        <div class="reg-pack" onclick="hitEnterRegister('pp')">
            <div class="reg-pack-box">
                <h2>Private Profile</h2>
                <ul>
                    <li>You do not appear in search</li>
                    <li>Your profile is invisible</li>
                    <li>Only friends can message you</li>
                </ul>
                <p>Click to Select</p>
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
                            <tr>
                                <td style="text-align: right"><input type="radio" name="private" value="1" id="ppr"></td>
                                <td><label for="ppr">Private</label></td>
                            </tr>
                            <tr>
                                <td style="text-align: right"><input type="radio" name="private" value="0" id="ppu"></td>
                                <td><label for="ppu">Public</label></td>
                            </tr>
                        </table>
                    </div>

                    <div class="rpbcb">
                        <p><b>Visibility</b><br>
                            As visible you will appear in search results.
                        </p>
                        <table>
                            <tr>
                                <td style="text-align: right"><input type="radio" name="visible" value="1" id="vv"></td>
                                <td><label for="vv">Visible</label></td>
                            </tr>
                            <tr>
                                <td style="text-align: right"><input type="radio" name="visible" value="0" id="vi"></td>
                                <td><label for="vi">Invisible</label></td>
                            </tr>
                        </table>
                    </div>

                </div>
                <p id="custom-set-btn" style="display: none"><button onclick="hitEnterRegister('cp')">Confirm</button></p>
            </div>
        </div>
    </div>
    <button onclick="stepBack()">Go back</button>
</div>

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
        $code = $_GET['reset'];
        
        if (!empty($code)) {
            $checkReset = mysqli_query($conn, "SELECT * FROM `users` WHERE `reset`='$code'");
            $countReset = mysqli_num_rows($checkReset);

            if ($countReset == 0) {
                ?><meta http-equiv="refresh" content="0; URL='./'" /><?php
            } else {
                $resetData = mysqli_fetch_assoc($checkReset);
                $tglReset = $resetData['username'];
                ?>
                <div class="center_form">
                    <div class="form">
                        <?php include("assets/forms/reset.php");?>
                    </div>
                </div>
                <?php
            }
        } else {
            ?>
            <div class="center_form">
                <div class="form">
                    <?php include("assets/forms/resetCode.php");?>
                </div>
            </div>
            <?php
        }
    } else
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
        <?php if (isset($dev_access) && $dev_access == true) {?>
        <div class="form">
            <div class="login" id="login-form" <?php if ($signup == true) {?>hidden<?php }?>>
                <?php include("assets/forms/login.php");?>
            </div>
            <div class="register" id="register-form" <?php if ($signup == false) {?>hidden<?php }?>>
                <?php include("assets/forms/register.php");?>
            </div>
            <div class="links">
                <?php if (skybyn('login-form') == "login") {
                    if ($signup == false) {?>
                <span onclick="window.location.href='/forgot'">Forgot password?</span>
                <?php }}?>
            </div>
        </div>
        <?php if (skybyn('login-form') == "login") {
            if ($signup == false) {?>
        <div class="reg-button" id="signup-btn">
            <span onclick="window.location.href='./register'">Sign up</span>
        </div>
        <?php } else {?>
        <div class="log-button split" id="signup-btn">
            <span onclick="window.location.href='./'" id="login-btn">Login here</span>
            <span onclick="window.location.href='/forgot'">Forgot password?</span>
        </div>
        <?php }}?>

        <?php } else {?>
        <div class="form">
            <div class="login" id="login-form" <?php if ($signup == true) {?>hidden<?php }?>>
                <?php include("assets/forms/login.php");?>
            </div>
            <div class="register" id="register-form" <?php if ($signup == false) {?>hidden<?php }?>>
                <?php include("assets/forms/register.php");?>
            </div>
            <div class="links">
                <?php if (skybyn('login-form') == "login") {
                    if ($signup == false) {?>
                <span onclick="window.location.href='/forgot'">Forgot password?</span>
                <?php }}?>
            </div>
        </div>
        <?php if (skybyn('register') == "1") {
            if ($signup == false) {?>
        <div class="reg-button" id="signup-btn">
            <span onclick="window.location.href='./register'">Sign up</span>
        </div>
        <?php } else {?>
        <div class="reg-button" id="signup-btn">
            <span onclick="window.location.href='./'" id="login-btn">Login here</span>
            <span onclick="window.location.href='/forgot'">Forgot password?</span>
        </div>
        <?php }}}?>
    </div>

<script>
    function showInfo(x) {
        <?php if (isMobile() == true) {?>
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
    <?php }}?>
</div>