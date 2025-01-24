                <h3>Verify your email with the code we sent you</h3>
                <div class="verify_form">
                    <i class="fa-solid fa-angle-right" id="ra"></i>
                    <input type="number" id="code" onkeydown="hitEnterVerify(this)" placeholder="Enter the confirmation code" title="We've sent you an e-mail containing a code to verify your account." required autofocus>
                    <input type="submit" onclick="checkCode()" value="Confirm">
                </div>
                <div class="links">
                    <span onclick="removeVerify()">Go back</span>
                    <span onclick="resendVerify(this)">Resend verification code</span>
                </div>

                <script>
                    function hitEnterVerify(input) {
                        const button = document.getElementById('login');

                        function handleKeyPress(event) {
                            if (event.keyCode === 13) {
                                checkCode();
                            }
                        }

                        input.addEventListener('keydown', handleKeyPress, { once: true });
                    }
                    function checkCode() {
                        const code = document.getElementById('code');
                        $.ajax({
                            url: '../assets/verify/verify_email.php',
                            type: "POST",
                            data: {
                                code : code.value
                            }
                        }).done(function(response) {
                            if (response == "verified") {
                                window.location.href = "./";
                            } else {
                                code.value = "";
                                code.placeholder = "Wrong code! Please try again.";
                            }
                        });
                    }
                    function resendVerify(x) {
                        const cookieName = "verify";
                        const cookies = document.cookie.split(";");

                        for (let i = 0; i < cookies.length; i++) {
                            const cookie = cookies[i].trim();
                            if (cookie.startsWith(cookieName + "=")) {
                                const cookieValue = cookie.slice((cookieName + "=").length, cookie.length);
                                console.log(cookieValue);
                                $.ajax({
                                    url: '../assets/resendVerify.php',
                                    type: "POST",
                                    data: {
                                        user : cookieValue
                                    }
                                }).done(function(response) {
                                    console.log(response);
                                    x.disabled = true;
                                    setTimeout(() => {
                                        x.disabled = false;
                                    }, 30000);
                                });
                            }
                        }
                    }
                    function removeVerify() {
                        document.cookie = "verify=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
                        window.location.href = "./";
                    }
                </script>