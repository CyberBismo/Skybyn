<h3>New IP detected!</h3>
                <div class="set_username_form">
                    <i class="fa-solid fa-user"></i>
                    <input type="number" id="code" onkeydown="hitEnter(this);checkCode(this)" pattern="\d{0,6}" title="We sent the code to your registered email." placeholder="Enter login code" style="-webkit-appearance: none; -moz-appearance: textfield;" autofocus>
                    <input type="submit" onclick="verifyCode()" value="Login">
                </div>
                <div class="links">
                    <button onclick="removeNewIP()">Go back</a>
                </div>

                <script>
                    function hitEnter(input) {
                        const button = document.getElementById('login');

                        function handleKeyPress(event) {
                            if (event.keyCode === 13) {
                                verifyCode();
                            }
                        }

                        input.addEventListener('keydown', handleKeyPress, { once: true });
                    }

                    function checkCode(x) {
                        const input = x.value;

                        if (input.length > 5) {
                            verifyCode();
                        }
                    }
                    
                    function verifyCode() {
                        const code = document.getElementById('code');
                        if (code.value != "") {
                            $.ajax({
                                url: 'assets/check_code_new_ip.php',
                                type: "POST",
                                data: {
                                    code : code.value,
                                    ip : '<?=$_COOKIE['new_IP']?>'
                                }
                            }).done(function() {
                                window.location.href = "./";
                            });
                        }
                    }

                    function removeNewIP() {
                        document.cookie = "newIP=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
                        window.location.href = "./";
                    }
                </script>