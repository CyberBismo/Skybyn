                <div class="qr_login">
                    <div class="qr_login_text">
                        <h2>Scan QR code</h2>
                        <p>Scan the QR code with your phone to sign in.</p>
                    </div>
                    <div class="qr_login_img" id="qr_login_img">
                        <img src="#" alt="" id="login_qr">
                    </div>
                    <script>
                        function getLoginQR() {
                            let code;
                            if (cookieExists('qr')) {
                                code = getCookieValue('qr');
                                if (code.length == 0) {
                                    code = generateRandomString(10);
                                    setCookie('qr', code, 1);
                                }
                            } else {
                                code = generateRandomString(10);
                                setCookie('qr', code, 1);
                            }
                            $.ajax({
                                url: './qr/api.php',
                                type: "POST",
                                data: {
                                    data : code
                                }
                            }).done(function(response) {
                                if (response === "404") {
                                    setTimeout(() => {
                                        getLoginQR();
                                    }, 1000);
                                } else {
                                    document.getElementById('login_qr').src = "../qr/temp/" + response + ".png";
                                    setTimeout(() => {
                                        checkQR(code);
                                    }, 3000);
                                }
                            });
                        }
                        function checkQR(code) {
                            console.log('Checking QR code');
                            $.ajax({
                                url: './qr/api.php',
                                type: "POST",
                                data: {
                                    check : code
                                }
                            }).done(function(response) {
                                if (response === "pending") {
                                    setTimeout(() => {
                                        checkQR(code);
                                    }, 1000);
                                } else
                                if (response === "404") {
                                    return;
                                } else
                                if (response === "expired") {
                                    getLoginQR();
                                } else
                                if (response === "success"){
                                    window.location.href = "./";
                                }
                            });
                        }

                        function setQRSize() {
                            const qrImage = document.getElementById('qr_login');
                            const qrWidth = qrImage.style.width;
                            qrImage.style.height = qrWidth + 'px';
                        }
                        setQRSize();
                        window.addEventListener('resize', setQRSize);
                    </script>
                </div>
                <div class="normal_login">
                    <h2>Sign in</h2>

                    <i class="fa-solid fa-at"></i>
                    <input type="email" id="login-email" onkeydown="hitEnterLogin(this)" pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$" required placeholder="E-mail address" title="example@example.com" oninput="setCustomValidity('')" oninvalid="setCustomValidity('Please enter a valid email address')" autofocus>

                    <i class="fa-solid fa-key"></i>
                    <input type="password" pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$" id="login-password" onkeydown="hitEnterLogin(this)" placeholder="Password" title="Password must be at least 8 characters long, with at least one lowercase letter, one uppercase letter, and one digit." required>
                    <i class="fa-regular fa-eye" onclick="showPassword('login-password')"></i>

                    <input type="checkbox" id="login-remember" onkeydown="hitEnterLogin(this)"><label for="login-remember">Remember me</label>
                    
                    <input type="submit" id="login" onclick="login()" value="Sign in">

                    <center><p id="login_msg"></p></center>

                    <script>
                        function showPassword(x) {
                            const password = document.getElementById(x);
                            if (password.type == "password") {
                                password.type = "text";
                            } else {
                                password.type = "password";
                            }
                        }
                        function hitEnterLogin(input) {
                            const button = document.getElementById('login');

                            function handleKeyPress(event) {
                                if (event.keyCode === 13) {
                                    login();
                                }
                            }

                            input.addEventListener('keydown', handleKeyPress, { once: true });
                        }

                        function login() {
                            let email = document.getElementById('login-email');
                            let password = document.getElementById('login-password');
                            let lmsg = document.getElementById('login_msg');
                            let remember = document.getElementById('login-remember');

                            if (remember.checked) {
                                remember = "true";
                            } else {
                                remember = "false";
                            }

                            $.ajax({
                                url: './assets/login.php',
                                type: "POST",
                                data: {
                                    email : email.value,
                                    password : password.value,
                                    remember : remember
                                }
                            }).done(function(response) {
                                if (response === "login_ok" || response === "new_ip" || response === "verify") {
                                    window.location.href = "./";
                                } else {
                                    lmsg.innerHTML = response;
                                    setTimeout(() => {
                                        lmsg.innerHTML = null;
                                    }, 3000);
                                }
                            });
                        }
                    </script>
                </div>
                <div class="links">
                    <?php if (skybyn('login-form') == "login") {
                        if ($signup == false) {?>
                    <span onclick="window.location.href='/forgot'">Forgot password?</span>
                    <?php }}?>
                    <?php if (isMobile() === false) { ?>
                    <span class="show_qr_login" onclick="tglLogin()" id="qr_tgl">Sign in with <i class="fa-solid fa-qrcode"></i></span>
                    <?php } ?>
                </div>
                <script>
                    function deleteCookie(name) {
                        document.cookie = name + '=; expires=Thu, 01 Jan 1970 00:00:01 GMT;';
                        console.clear();
                    }
                    function tglLogin() {
                        let qr_tgl = document.getElementById('qr_tgl');
                        let qr = document.querySelector('.qr_login');
                        let normal = document.querySelector('.normal_login');
                        if (qr.style.display === "flex") {
                            qr.style.display = "none";
                            normal.style.display = "block";
                            if (cookieExists('qr')) {
                                let code = getCookieValue('qr');
                                deleteCookie('qr');
                                document.getElementById('login_qr').src = "#";
                                qr_tgl.innerHTML = "Sign in with <i class='fa-solid fa-qrcode'></i>";
                                $.ajax({
                                    url: './qr/api.php',
                                    type: "POST",
                                    data: {
                                        delete : code
                                    }
                                }).done(function(response) {
                                    console.clear();
                                });
                            }
                        } else {
                            qr.style.display = "flex";
                            normal.style.display = "none";
                            qr_tgl.innerHTML = "Sign in with <i class='fa-solid fa-at'></i>";
                            getLoginQR();
                        }
                    }

                    function setCookie(name, value, days) {
                        const date = new Date();
                        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
                        const expires = `expires=${date.toUTCString()}`;
                        document.cookie = `${name}=${value};${expires};path=/`;
                    }

                    function cookieExists(cookieName) {
                        const pattern = new RegExp('(^|; )' + encodeURIComponent(cookieName) + '=([^;]*)');
                        return pattern.test(document.cookie);
                    }

                    function getCookieValue(cookieName) {
                        const pattern = new RegExp('(^|; )' + encodeURIComponent(cookieName) + '=([^;]*)');
                        const match = document.cookie.match(pattern);
                        return match ? decodeURIComponent(match[2]) : null;
                    }

                    function generateRandomString(length) {
                        const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
                        let result = '';
                        const charactersLength = characters.length;
                        for (let i = 0; i < length; i++) {
                            result += characters.charAt(Math.floor(Math.random() * charactersLength));
                        }
                        return result;
                    }
                </script>