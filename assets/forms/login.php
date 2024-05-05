                <div class="qr_login">
                    <img src="./assets/images/logo_faded_clean.png" alt="" id="login_qr">
                    <?php
                    if (!isset($_SESSION['qr_session'])) {
                        $_SESSION['qr_session'] = hash("sha256",rand(1000000,9999999));
                    }
                    ?>
                    <script>
                        function getLoginQR() {
                            $.ajax({
                                url: './qr/api.php',
                                type: "POST",
                                data: {
                                    data : null
                                }
                            }).done(function(response) {
                                if (response === "repeat") {
                                    setTimeout(() => {
                                        getLoginQR();
                                    }, 1000);
                                } else {
                                    document.getElementById('login_qr').src = "./qr/temp/"+response+".png";
                                    checkQR(response);
                                }
                            });
                        }
                        function checkQR(code) {
                            $.ajax({
                                url: './qr/api.php',
                                type: "POST",
                                data: {
                                    check : code
                                }
                            }).done(function(response) {
                                if (response === "repeat") {
                                    setTimeout(() => {
                                        getLoginQR();
                                    }, 1000);
                                } else {
                                    window.location.href = "./";
                                }
                            });
                        }
                        getLoginQR();
                    </script>
                </div>
                <!--h2>Sign in</h2>

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
                </script-->