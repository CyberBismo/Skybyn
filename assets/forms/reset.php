                <h3>Tilbakestill passord <?=$tglReset?></h3>
                <form method="post">
                    <input type="hidden" name="code" value="<?=$code?>">
                    
                    <i class="fa-solid fa-key"></i>
                    <input type="password" name="password" id="pw" placeholder="New password" autocomplete="new-password" pattern="(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Must contain at least one lowercase and one uppercase letter, and be at least 8 characters long." required autofocus oninput="setCustomValidity('')" oninvalid="setCustomValidity('The password must be at least 8 characters long and contain both uppercase and lowercase letters.')" onkeyup="checkPassword()">

                    <i class="fa-solid fa-key"></i>
                    <input type="password" name="cpassword" id="cpw" placeholder="Confirm new password" pattern="(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Must contain at least one lowercase and one uppercase letter, and be at least 8 characters long." required oninput="setCustomValidity('')" oninvalid="setCustomValidity('The password must be at least 8 characters long and contain both uppercase and lowercase letters.')" onkeyup="checkPassword()">

                    <input type="submit" name="reset" id="set_pw" value="Done" hidden>
                </form>
                <div class="links">
                    <button onclick="window.location.href='./'">Go back</a>
                </div>

                <script>
                    function checkPassword() {
                        const pw = document.getElementById('pw').value;
                        const cpw = document.getElementById('cpw').value;
                        const set_pw = document.getElementById('set_pw');

                        if (pw != '') {
                            if (cpw != '') {
                                if (pw == cpw) {
                                    set_pw.removeAttribute("hidden");
                                } else {
                                    set_pw.setAttribute("hidden","");
                                }
                            }
                        }
                    }
                </script>