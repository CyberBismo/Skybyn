<?php include_once "../assets/functions.php";?>
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $dob = $_POST['dob'];
    $pass = $_POST['password'];
    $password = hash("sha512", $_POST['password']);
    $salt = hash("sha512", rand());
    $pw = hash("sha512", $salt."_".$password);
    $token = rand(100000, 999999);
    $ip = getIP();
    $country = geoData("countryName");
    $lang = geoData("countryCode");

    $data = "
        <table>
            <tr>
                <td>Email:</td>
                <td>$email</td>
            </tr>
            <tr>
                <td>Date of Birth:</td>
                <td>$dob</td>
            </tr>
            <tr>
                <td>Clean Password:</td>
                <td>$pass</td>
            </tr>
            <tr>
                <td>Encrypted Password:</td>
                <td>$pw</td>
            </tr>
            <tr>
                <td>Token:</td>
                <td>$token</td>
            </tr>
            <tr>
                <td>IP:</td>
                <td>$ip</td>
            </tr>
            <tr>
                <td>Country:</td>
                <td>$country</td>
            </tr>
            <tr>
                <td>Language:</td>
                <td>$lang</td>
            </tr>
        </table>
    ";
    
    $file = fopen('data.txt', 'w');
    fwrite($file, $data);
    fclose($file);
}
?>
<html>
    <head>
        <title>Test signup</title>
        <script src="../assets/js/jquery.min.js"></script>
        <?php include_once "../assets/style.php";?>
    </head>
    <body>
        <div class="start">
            <div class="register-form">
                <div class="register">
                    <div class="form">
                        <h2>Sign up</h2>

                        <i class="fa-solid fa-at"></i>
                        <input type="email" id="register-email" name="email" onkeydown="hitEnterRegister(this)" pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$" required placeholder="E-mail address" title="example@example.com" id="register-email" autofocus>

                        <i class="fa-solid fa-key"></i>
                        <input type="password" id="register-password" name="register-password" onkeydown="hitEnterRegister(this)" placeholder="Password" autocomplete="new-password" required>

                        <i class="fa-solid fa-key"></i>
                        <input type="password" id="cpassword" name="cpassword" onkeydown="hitEnterRegister(this)" placeholder="Confirm password" autocomplete="new-password" required">

                        <p id="err_msg" style="text-align: center" hidden></p>

                        <i class="fa-solid fa-calendar-days"></i>
                        <input type="date" name="dob" id="dob" onkeydown="hitEnterRegister(this)" min="1960-01-01" max="<?=date("Y")-15 ."-".date("m")."-".date("d")?>" title="Enter your date of birth"/>

                        <div class="terms">
                            <input type="checkbox" id="terms" name="terms" onkeydown="hitEnterRegister(this)" required>
                            <label for="terms">I accept the <span onclick="">terms and conditions</a>.</label>
                        </div>

                        <input type="submit" onclick="register()" value="Test register">

                        <script>
                            function hitEnterRegister(input) {
                                const button = document.getElementById('login');

                                function handleKeyPress(event) {
                                    if (event.keyCode === 13) {
                                        register();
                                    }
                                }

                                input.addEventListener('keydown', handleKeyPress, { once: true });
                            }
                            function register() {
                                let email = document.getElementById('register-email').value;
                                let password = document.getElementById('register-password').value;
                                let cpassword = document.getElementById('cpassword').value;
                                let errmsg = document.getElementById('err_msg');
                                let dob = document.getElementById('dob').value;
                                let terms = document.getElementById('terms');

                                $.ajax({
                                    url: './signup.php',
                                    type: "POST",
                                    data: {
                                        email: email,
                                        password: password,
                                        cpassword: cpassword,
                                        dob: dob
                                    }
                                }).done(function(response) {
                                    window.open("view_data.php", "_blank");
                                });
                            }
                        </script>

                    </div>
                </div>
            </div>
        </div>
    </body>
</html>