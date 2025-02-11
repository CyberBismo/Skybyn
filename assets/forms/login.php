                <div class="qr_login">
                    <div class="qr_login_text">
                        <h2>Sign in</h2>
                        <p>Scan the QR code using the Skybyn app to sign in.</p>
                        <div class="btn" onclick="tglLogin()"><i class="fa-solid fa-arrow-left"></i> Sign in with email</div>
                    </div>
                    <div class="qr_login_img" id="qr_login_img">
                        <img src="#" alt="" id="login_qr">
                    </div>
                </div>
                <div class="normal_login" id="normal_login">
                    <center><p id="login_msg"></p></center>
                    <?php if (!isMobile($userAgent) && $beta == true) { ?>
                        <div class="login_qr" onclick="tglLogin()" id="qr_tgl"><i class="fa-solid fa-qrcode"></i></div>
                    <?php } ?>
                    
                    <h2 id="normal_login_header">Sign in</h2>

                    <i class="fa-solid fa-at"></i>
                    <input type="email" id="login-email" onkeydown="hitEnterLogin(this)" pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$" required placeholder="E-mail address" title="example@example.com" oninput="setCustomValidity('')" oninvalid="setCustomValidity('Please enter a valid email address')" autofocus>

                    <i class="fa-solid fa-key"></i>
                    <input type="password" pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{4,}$" id="login-password" onkeydown="hitEnterLogin(this)" placeholder="Password" oninvalid="setCustomValidity('Password must be at least 8 characters long, with at least one lowercase letter, one uppercase letter, and one digit.')" required>
                    <i class="fa-regular fa-eye" onclick="showPassword('login-password')"></i>

                    <input type="checkbox" id="login-remember"><label for="login-remember">Remember me</label>
                    
                    <button id="login" onclick="login()">Sign in <i class="fa-solid fa-right-to-bracket"></i></button>
                </div>