<?php
$resetOK = false;
$expired = false;

if (isset($_GET['code'])) {
    $reset = $_GET['code'];
    $checkCode = $conn->query("SELECT * FROM `reset_codes` WHERE `code`='$reset'");
    if ($checkCode->num_rows == 1) {
        $code = $checkCode->fetch_assoc();
        $five_min = time() - 300;
        if ($code['expiration_date'] > $five_min) {
            $resetOK = true;
        } else {
            $expired = true;
        }
    }
}

?>
                <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
                <?php if ($expired) {?>
                <h2>This code has expired</h2>
                <?php } else {
                    if (!$resetOK) {?>
                <h2>Enter your reset code</h2>
                <?php } else {?>
                <h2>Set a new password</h2>
                <?php }?>
                <form method="post">
                    <?php if (!$resetOK) {?>
                    <i class="fa-solid fa-angle-right" id="ra"></i>
                    <input type="text" name="code" pattern="[0-9]*" onkeyup="checkCode(this)" placeholder="Enter it here..">
                    <?php } else {?>
                    <input name="code" value="<?=$_GET['code']?>" hidden>
                    <div class="form-inputs" id="inputs">
                        <i class="fa-solid fa-key"></i>
                        <input type="password" name="password" id="pw" placeholder="New password" autocomplete="new-password" required onkeyup="checkPassword()">

                        <i class="fa-solid fa-key"></i>
                        <input type="password" name="cpassword" id="cpw" placeholder="Confirm new password" required onkeyup="checkPassword()">

                        <input type="submit" name="reset" id="set_pw" value="Done" hidden>
                    </div>
                    <?php }?>
                </form>
                <?php }?>
                <div class="links">
                    <span onclick="window.location.href='../'">Go back</span>
                </div>

                <?php if (!$resetOK) {?>
                <script>
                    function checkCode(code) {
                        const ra = document.getElementById('ra');
                        const inputs = document.getElementById('inputs');
                        const pw = document.getElementById('pw');
                        $.ajax({
                            url: '../assets/verify/verify_reset_code.php',
                            type: "POST",
                            data: {
                                code : code.value
                            }
                        }).done(function(response) {
                            if (response == "ok") {
                                window.location.href = '../reset?code=' + code.value;
                            } else {
                                ra.classList.remove("fa-angle-right");
                                ra.classList.add("fa-xmark");
                                inputs.setAttribute("hidden","");
                            }
                        });
                    }
                </script>
                <?php } else {?>
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
                <?php }?>