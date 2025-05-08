<div class="login-popup">
        <div class="login-popup-box">
            <div class="center_form" id="log_reg_form">
                <center><img src="../assets/images/logo_faded_clean.png" onclick="window.location.href='/'" alt="Skybyn Logo" class="logo"></center>
                <div class="form">
                    <?php include_once "assets/forms/login.php";?>
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
        </div>
    </div>