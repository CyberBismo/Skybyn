                <div class="qr_login">
                    <div class="qr_login_text">
                        <h2>Scan QR code</h2>
                        <p>Scan the QR code using the Skybyn app to sign in.</p>
                    </div>
                    <div class="qr_login_img" id="qr_login_img">
                        <img src="#" alt="" id="login_qr">
                    </div>
                    <script>
                        function getLoginQR() {
                            let code;
                            if (cookieExists('qr')) {
                                code = getCookieValue('qr');
                                if (code.length < 10) {
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
                                if (response != null) {
                                    document.getElementById('login_qr').src = "../qr/temp/" + response + ".png";
                                }
                            });
                        }

                        function setQRSize() {
                            const qrImage = document.getElementById('qr_login');
                            const qrWidth = qrImage.style.width;
                            qrImage.style.height = qrWidth + 'px';
                        }
                    </script>
                </div>
                <div class="normal_login" id="normal_login">
                    <center><p id="login_msg"></p></center>
                    <?php if (!isMobile()) { ?>
                        <div class="login_qr" onclick="tglLogin()" id="qr_tgl"><i class="fa-solid fa-qrcode"></i></div>
                    <?php } ?>
                    
                    <h2 id="normal_login_header">Sign in</h2>

                    <i class="fa-solid fa-at"></i>
                    <input type="email" id="login-email" onkeydown="hitEnterLogin(this)" pattern="[a-zA-Z0-9._%+\-]+@[a-zA-Z0-9.\-]+\.[a-zA-Z]{2,}$" required placeholder="E-mail address" title="example@example.com" oninput="setCustomValidity('')" oninvalid="setCustomValidity('Please enter a valid email address')" autofocus>
                    <i class="fa-solid fa-key"></i>
                    <input type="password" pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{4,}$" id="login-password" onkeydown="hitEnterLogin(this)" placeholder="Password" oninvalid="setCustomValidity('Password must be at least 8 characters long, with at least one lowercase letter, one uppercase letter, and one digit.')" required>
                    <i class="fa-regular fa-eye" onclick="showPassword('login-password')"></i>

                    <input type="checkbox" id="login-remember"><label for="login-remember">Remember me</label>
                    
                    <input type="submit" id="login" onclick="login()" value="Sign in">

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
                            let normal_login = document.querySelector('.normal_login');
                            let nlh = document.getElementById('normal_login_header');
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
                                    email: email.value,
                                    password: password.value,
                                    remember: remember
                                },
                                beforeSend: function() {
                                    nlh.innerHTML = "Logging in...";
                                    normal_login.style.opacity = "0.5";
                                    normal_login.style.pointerEvents = "none";
                                    normal_login.style.userSelect = "none";
                                    normal_login.style.cursor = "wait";
                                },
                                success: function(response) {
                                    if (response.responseCode === "ok") {
                                        nlh.innerHTML = response.message;
                                        window.location.href = "./";
                                    } else {
                                        nlh.innerHTML = response.message;
                                        setTimeout(() => {
                                            nlh.innerHTML = "Sign in";
                                        }, 3000);
                                        normal_login.style.opacity = "1";
                                        normal_login.style.pointerEvents = "auto";
                                        normal_login.style.userSelect = "auto";
                                        normal_login.style.cursor = "auto";
                                    }
                                },
                                error: function() {
                                    nlh.innerHTML = "An error occurred. Please try again.";
                                    setTimeout(() => {
                                        nlh.innerHTML = "Sign in";
                                    }, 3000);
                                    normal_login.style.opacity = "1";
                                    normal_login.style.pointerEvents = "auto";
                                    normal_login.style.userSelect = "auto";
                                    normal_login.style.cursor = "auto";
                                },
                                complete: function() {
                                    normal_login.style.opacity = "1";
                                    normal_login.style.pointerEvents = "auto";
                                    normal_login.style.userSelect = "auto";
                                    normal_login.style.cursor = "auto";
                                }
                            });
                        }
                    </script>
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
                                qr_tgl.innerHTML = "<i class='fa-solid fa-qrcode'></i>";
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
                            
                            setQRSize();
                            window.addEventListener('resize', setQRSize);
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