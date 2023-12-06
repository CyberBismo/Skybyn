                <h3>Verify your email with the code we sent you</h3>
                <div class="verify_form">
                    <input type="number" id="code" placeholder="Enter the confirmation code" title="We've sent you an email containing a code to verify your account." required autofocus>
                    <input type="submit" onclick="checkCode()" value="Confirm">
                </div>

                <script>
                    function checkCode() {
                        const code = document.getElementById('code');
                        $.ajax({
                            url: 'assets/verify_email.php',
                            type: "POST",
                            data: {
                                code : code.value
                            }
                        }).done(function(response) {
                            if (response == "verified") {
                                window.location.href = "./?username";
                            } else {
                                code.value = "";
                                code.placeholder = "Wrong code! Please try again.";
                            }
                        });
                    }
                </script>