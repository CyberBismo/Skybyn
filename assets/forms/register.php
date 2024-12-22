                <?php if (skybyn('register') == "1") {
                    if (isset($_GET['complete'])) {
                        if (isset($_GET['user']) && !empty($_GET['user'])) {
                            if (isset($_GET['token']) && !empty($_GET['token'])) {
                                $id = $_GET['user'];
                                $token = $_GET['token'];
                                $checkRegistration = $conn->query("SELECT * FROM `users` WHERE `id`='$id' AND `token`='$token'");
                                if ($checkRegistration->num_rows == 1) {
                                    $_SESSION['user'] = $id;
                                    $conn->query("UPDATE `users` SET `token`='' WHERE `id`='$id'");
                                    ?><script>window.location.href='../';</script><?php
                                    return false;
                                }
                            }
                        }
                    }
                ?>
                <h2>Sign up</h2>
                
                <div id="set_dob">
                    <p>Enter your date of birth to get started</p>
                    <i class="fa-solid fa-calendar-days"></i>
                    <input type="date" id="dob" min="1960-01-01" max="<?=date("Y")-15 ."-".date("m")."-".date("d")?>" title="Enter your date of birth" autofocus>
                </div>

                <div id="set_name" style="display: none">
                    <p>Your full name</p>
                    <div class="split">
                        <div class="split-box">
                            <i class="fa-solid fa-user"></i>
                            <input type="name" id="fname" placeholder="First name *" required>
                        </div>
                        <div class="split-box">
                            <i class="fa-solid fa-user"></i>
                            <input type="name" id="mname" placeholder="Middle name" autocomplete="new-password">
                        </div>
                    </div>
                    <i class="fa-solid fa-user"></i>
                    <input type="name" id="lname" placeholder="Last name *" required>
                </div>

                <div id="set_email" style="display: none">
                    <p>Your preferred email address</p>
                    <i class="fa-solid fa-at"></i>
                    <input type="email" id="email-check" placeholder="Email">
                    <input type="email" id="register-email" pattern="[a-zA-Z0-9._\-]+@[a-zA-Z0-9.\-]+\.[a-zA-Z]{2,}" placeholder="E-mail address" title="example@example.com" oninput="setCustomValidity('')" oninvalid="setCustomValidity('Please enter a valid e-mail address')" required>
                </div>

                <div id="set_email_verify" style="display: none">
                    <p>Enter the code we just sent you</p>
                    <i class="fa-solid fa-arrows-rotate" id="email-verify-status"></i>
                    <input type="number" id="email-verify" pattern="[a-zA-Z0-9]" placeholder="Enter code" title="example@example.com" oninput="setCustomValidity('')" oninvalid="setCustomValidity('Please enter a valid e-mail address')" autocomplete="new-password" required>
                </div>

                <div id="set_username" style="display: none">
                    <p>Set your username</p>
                    <i class="fa-solid fa-user"></i>
                    <input type="text" id="username" placeholder="Choose a username" title="" required>
                </div>

                <div id="set_password" style="display: none">
                    <p>Set a strong password</p>
                    <i class="fa-solid fa-key"></i>
                    <input type="password" id="register-password" placeholder="Password" autocomplete="new-password" required>
                    <i class="fa-regular fa-eye" onclick="showPassword('register-password')"></i>
                    
                    <i class="fa-solid fa-key"></i>
                    <input type="password" id="cpassword" placeholder="Confirm password" autocomplete="new-password" required>
                    <i class="fa-regular fa-eye" onclick="showPassword('cpassword')"></i>

                    <div class="password-strength">
                        <progress id="pw_p" max="100"></progress>
                    </div>

                    <div class="password-criteria">
                        <i id="pw_l" class="fa-solid fa-circle-xmark"></i> At least 8 characters.<br>
                        <i id="pw_a" class="fa-solid fa-circle-xmark"></i> Alphabetic character used.<br>
                        <i id="pw_n" class="fa-solid fa-circle-xmark"></i> Numeric character used.<br>
                        <i id="pw_s" class="fa-solid fa-circle-xmark"></i> Special character used.<br>
                        <i id="pw_m" class="fa-solid fa-circle-xmark"></i> Passwords match.
                    </div>
                </div>

                <div class="terms" id="set_terms" style="display: none">
                    <p>Got a friend code?</p>
                    <i class="fa-solid fa-bug"></i>
                    <input type="text" id="refer" pattern="[0-9]" onkeyup="checkRefCode()" placeholder="Enter here" autocomplete="new-password">
                    <div class="refer_user" id="refer-user"></div>
                    <div class="check">
                        <input type="checkbox" id="terms" required>
                        <label for="terms">I accept the <span onclick="showTerms()">terms and conditions</a>.</label>
                    </div>
                </div>

                <input type="submit" id="send_again" onclick="hitEnterRegister('resend')" style="display: none;margin-top: 3px">
                <input type="submit" id="register" onclick="hitEnterRegister(null)" value="Continue" style="margin-top: 3px">
                <input type="submit" id="step_back" onclick="stepBack()" value="Go back" style="display: none;margin-top: 3px">

                <div id="err_msg" style="display: none; margin-top: 20px">
                    <br>
                    <p style="text-align: center"></p>
                </div>
                <?php } else {?>
                <b><center>Registration is currently disabled</center></b>
                <?php }?>

                <script>
                    function calculateAge(dob) {
                        const birthDate = new Date(dob);
                        const today = new Date();
                        let age = today.getFullYear() - birthDate.getFullYear();
                        const monthDifference = today.getMonth() - birthDate.getMonth();

                        if (monthDifference < 0 || (monthDifference === 0 && today.getDate() < birthDate.getDate())) {
                            age--;
                        }

                        return age;
                    }

                    function checkRefCode() {
                        const refer = document.getElementById('refer');
                        const referRes = document.getElementById('refer-user');
                        if (refer.value.length >= 6) {
                            $.ajax({
                                url: '../assets/check_refer_code.php',
                                type: "POST",
                                data: {
                                    code: refer.value
                                }
                            }).done(function(response) {
                                if (response != null) {
                                    referRes.innerHTML = response;
                                } else {
                                    referRes.innerHTML = null;
                                }
                            });
                        }
                    }

                    function validateEmail(email) {
                        var emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
                        return emailPattern.test(email);
                    }
                    
                    function showCustom() {
                        const rpc = document.getElementById('reg-pack-custom');
                        const text = document.getElementById('custom-text');
                        const set = document.getElementById('custom-set');
                        const btn = document.getElementById('custom-set-btn');
                        rpc.style.width = "610px";
                        text.style.display = "none";
                        set.style.display = "flex";
                        btn.style.display = "block";
                    }

                    function stepBack() {
                        let set_dob = document.getElementById('set_dob');
                        let set_name = document.getElementById('set_name');
                        let set_email = document.getElementById('set_email');
                        let set_email_c = document.getElementById('set_email_verify');
                        let set_username = document.getElementById('set_username');
                        let set_pw = document.getElementById('set_password');
                        let set_terms = document.getElementById('set_terms');
                        let reg_packs = document.getElementById('reg_packs');
                        let send_again = document.getElementById('send_again');
                        let register = document.getElementById('register');
                        let step_back = document.getElementById('step_back');
                        let info_text = document.getElementById('info_text');
                        let intro = document.getElementById('intro');
                        let table = document.getElementById('reg_table');
                        let reg_info = document.getElementById('reg_info');
                        let reg_form = document.getElementById('log_reg_form');

                        if (reg_packs.style.display == "block") {
                            reg_packs.style.display = "none";
                            set_terms.style.display = "block";
                            reg_form.style.display = "block";
                            reg_info.style.display = "block";
                        } else
                        if (set_terms.style.display == "block") {
                            set_terms.style.display = "none";
                            set_pw.style.display = "block";
                            register.value = "Continue";
                            document.getElementById('reg-t-pw').style.opacity = 0;
                            table.style.height = (table.offsetHeight - 30) + "px";
                            setTimeout(() => {
                                document.getElementById('reg-t-pw').remove();
                            }, 100);
                        } else
                        if (set_pw.style.display == "block") {
                            set_pw.style.display = "none";
                            set_username.style.display = "block";
                            register.value = "Continue";
                            document.getElementById('reg-t-uname').style.opacity = 0;
                            table.style.height = (table.offsetHeight - 30) + "px";
                            setTimeout(() => {
                                document.getElementById('reg-t-uname').remove();
                            }, 100);
                        } else
                        if (set_username.style.display == "block") {
                            set_username.style.display = "none";
                            set_email.style.display = "block";
                            register.value = "Send code";
                            document.getElementById('reg-t-email').style.opacity = 0;
                            table.style.height = (table.offsetHeight - 30) + "px";
                            setTimeout(() => {
                                document.getElementById('reg-t-email').remove();
                            }, 100);
                        } else
                        if (set_email_verify.style.display == "block") {
                            set_email_verify.style.display = "none";
                            set_email.style.display = "block";
                            send_again.style.display = "none";
                            register.style.display = "block";
                            register.value = "Continue";
                            document.getElementById('reg-t-email').style.opacity = 0;
                            table.style.height = (table.offsetHeight - 30) + "px";
                            setTimeout(() => {
                                document.getElementById('reg-t-email').remove();
                            }, 100);
                        } else
                        if (set_email.style.display == "block") {
                            set_email.style.display = "none";
                            set_name.style.display = "block";
                            send_again.style.display = "none";
                            register.style.display = "block";
                            register.value = "Continue";
                            document.getElementById('reg-t-name').style.opacity = 0;
                            table.style.height = (table.offsetHeight - 30) + "px";
                            setTimeout(() => {
                                document.getElementById('reg-t-name').remove();
                            }, 100);
                        } else
                        if (set_name.style.display == "block") {
                            set_name.style.display = "none";
                            set_dob.style.display = "block";
                            register.value = "Continue";
                            step_back.style.display = "none";
                            document.getElementById('reg-t-age').style.opacity = 0;
                            table.style.height = (table.offsetHeight - 30) + "px";
                            setTimeout(() => {
                                document.getElementById('reg-t-age').remove();
                            }, 100);

                            info_text.style.display = "block";
                            intro.style.display = "block";
                            reg_info.style.display = "none";
                            <?php if (isMobile() == true) {?>
                            wel_inf.style.height = "200px";
                            <?php }?>
                        }
                    }

                    function hitEnterRegister(x) {
                        let set_dob = document.getElementById('set_dob');
                        let set_name = document.getElementById('set_name');
                        let set_email = document.getElementById('set_email');
                        let set_email_c = document.getElementById('set_email_verify');
                        let set_username = document.getElementById('set_username');
                        let set_pw = document.getElementById('set_password');
                        let set_terms = document.getElementById('set_terms');
                        let send_again = document.getElementById('send_again');
                        let register = document.getElementById('register');
                        let step_back = document.getElementById('step_back');
                        let err_msg = document.getElementById('err_msg');

                        let wel_inf = document.getElementById('welcome_info');
                        let info_text = document.getElementById('info_text');
                        let reg_info = document.getElementById('reg_info');
                        let table = document.getElementById('reg_table');
                        let reg_packs = document.getElementById('reg_packs');
                        let reg_form = document.getElementById('log_reg_form');

                        const dob_v = document.getElementById('dob').value;
                        const age = calculateAge(dob_v);
                        
                        const fname = document.getElementById('fname'); // First name
                        const mname = document.getElementById('mname'); // Middle name
                        const lname = document.getElementById('lname'); // Last name

                        const email_c = document.getElementById('email-check'); // Email check (special)
                        const email = document.getElementById('register-email'); // Email
                        const email_verify = document.getElementById('email-verify'); // Email code verify
                        const username = document.getElementById('username'); // Username
                        const pw = document.getElementById('register-password'); // Password
                        const cpw = document.getElementById('cpassword'); // Confirm password
                        const refer = document.getElementById('refer').value; // Referral code
                        
                        // Verify date of birth value and enter full name
                        if (set_dob.style.display != "none") {
                            let step_one = false;
                            if (dob_v) {
                                step_one = true;
                            }
                            if (step_one == true) {
                                info_text.style.display = "none";
                                reg_info.style.display = "block";

                                <?php if (isMobile() == true) {?>
                                wel_inf.style.height = "auto";
                                <?php }?>
                                
                                set_dob.style.display = "none";
                                set_name.style.display = "block";
                                step_back.style.display = "block";
                                intro.style.display = "none";
                                reg_info.style.display = "block";
                                fname.focus();
                                // Adding age to table
                                tr = document.createElement('tr');
                                tr.id = "reg-t-age";
                                tr.style.opacity = 0;
                                setTimeout(() => {
                                    tr.style.opacity = 1;
                                    table.style.height = (table.offsetHeight + 30) + "px";
                                }, 300);
                                table.appendChild(tr);
                                td = document.createElement('td');
                                td.innerHTML = "Age:";
                                td_v = document.createElement('td');
                                td.style.textAlign = "right";
                                td.style.width = "80.25px";
                                td_v.style.textAlign = "left";
                                td_v.innerHTML = age;
                                tr.appendChild(td);
                                tr.appendChild(td_v);
                            }
                        } else
                        // Check full name and move to email
                        if (set_name.style.display != "none") {
                            if (fname.value != "" && lname.value != "") {
                                set_name.style.display = "none";
                                set_email.style.display = "block";
                                email.focus();
                                register.value = "Send code";
                                // Adding name to table
                                tr = document.createElement('tr');
                                tr.id = "reg-t-name";
                                tr.style.opacity = 0;
                                setTimeout(() => {
                                    tr.style.opacity = 1;
                                    table.style.height = (table.offsetHeight + 30) + "px";
                                }, 300);
                                table.appendChild(tr);
                                td_name = document.createElement('td');
                                td_name.innerHTML = "Name:";
                                td_name_v = document.createElement('td');
                                td_name.style.textAlign = "right";
                                td_name_v.style.textAlign = "left";
                                td_name_v.innerHTML = fname.value+" "+mname.value+" "+lname.value;
                                tr.appendChild(td_name);
                                tr.appendChild(td_name_v);
                            }
                        } else
                        // Check email and send verification code
                        if (set_email.style.display != "none") {
                            if (email.value != "") {
                                function convertUnix(unix) {
                                    const minutes = Math.floor(unix / 60);
                                    const seconds = unix % 60;
                                    return minutes + "m " + seconds + "s";
                                }
                                
                                // Send an email verification code
                                if (validateEmail(email.value)) {
                                    $.ajax({
                                        url: '../assets/check_email.php',
                                        type: "POST",
                                        data: {
                                            email: email.value
                                        }
                                    }).done(function(response) {
                                        if (response === "sent") {
                                            updateUIForEmailSent();
                                        } else
                                        if (response === "sent_before") {
                                            updateUIForEmailSent();
                                        } else
                                        if (response === "verified") {
                                            updateUIForEmailSent('v');
                                        } else {
                                            register.value = "This email cannot be used.";
                                            setTimeout(() => {
                                                register.value = "Send code";
                                            }, 3000);
                                        }
                                    });
                                }
                                function updateUIForEmailSent(x) {
                                    if (x != "v") {
                                        set_email.style.display = "none";
                                        set_email_verify.style.display = "block";
                                        email_verify.focus();
                                        send_again.style.display = "block";
                                        send_again.value = "Check your inbox/spam folder.";
                                        register.value = "Verify code";
                                        td_email_v = document.createElement('td');
                                        td_email_v.id = "email-s";
                                        td_email_v.style.textAlign = "left";
                                        td_email_v.innerHTML = "Verifying..";
                                        setTimeout(() => {
                                            $.ajax({
                                                url: '../assets/check_email.php',
                                                type: "POST",
                                                data: {
                                                    email: email.value,
                                                    resend: "1"
                                                }
                                            }).done(function(response) {
                                                var remainingTime = parseInt(response); // Ensure it's a number
                                                const timer = setInterval(function() {
                                                    remainingTime--;
                                                    if (remainingTime <= 0) {
                                                        clearInterval(timer);
                                                        send_again.disabled = false;
                                                        send_again.value = "Send again";
                                                    } else {
                                                        send_again.disabled = true;
                                                        send_again.value = convertUnix(remainingTime);
                                                    }
                                                }, 1000);
                                            });
                                        }, 3000);
                                    } else {
                                        set_email.style.display = "none";
                                        set_username.style.display = "block";
                                        send_again.style.display = "none";
                                        username.focus();
                                        register.value = "Continue";
                                        td_email_v = document.createElement('td');
                                        td_email_v.id = "email-s";
                                        td_email_v.style.textAlign = "left";
                                        td_email_v.innerHTML = email.value;
                                    }
                                    // Adding email to table
                                    tr = document.createElement('tr');
                                    tr.id = "reg-t-email";
                                    tr.style.opacity = 0;
                                    setTimeout(() => {
                                        tr.style.opacity = 1;
                                        table.style.height = (table.offsetHeight + 30) + "px";
                                    }, 300);
                                    table.appendChild(tr);
                                    td_email = document.createElement('td');
                                    td_email.style.textAlign = "right";
                                    td_email.innerHTML = "Email:";
                                    tr.appendChild(td_email);
                                    tr.appendChild(td_email_v);
                                }
                            }
                        } else
                        // Verify email
                        if (set_email_verify.style.display != "none") {
                            // Resend code
                            if (x == "resend") {
                                if (validateEmail(email.value)) {
                                    function convertUnix(unix) {
                                        const minutes = Math.floor(unix / 60);
                                        const seconds = unix % 60;
                                        return minutes + "m " + seconds + "s";
                                    }
                                    $.ajax({
                                        url: '../assets/check_email.php',
                                        type: "POST",
                                        data: {
                                            email: email.value,
                                            resend: "1"
                                        }
                                    }).done(function(response) {
                                        // Parse response here if necessary
                                        var remainingTime = parseInt(response); // Ensure it's a number
                                        const timer = setInterval(function() {
                                            remainingTime--;
                                            if (remainingTime <= 0) {
                                                clearInterval(timer);
                                                send_again.disabled = false;
                                                send_again.value = "Send again";
                                            } else {
                                                send_again.disabled = true;
                                                send_again.value = convertUnix(remainingTime);
                                            }
                                        }, 1000);
                                    });
                                }
                            } else {
                                if (email_verify.value != "") {
                                    // Checking code for verification
                                    $.ajax({
                                        url: '../assets/register_email_verify.php',
                                        type: "POST",
                                        data: {
                                            code: email_verify.value
                                        }
                                    }).done(function(response) {
                                        if (response === "ok") {
                                            document.getElementById('email-s').innerHTML = email.value;
                                            set_email_verify.style.display = "none";
                                            set_username.style.display = "block";
                                            username.focus();
                                            send_again.style.display = "none";
                                            register.value = "Continue";
                                            step_back.value = "Go back";
                                        } else {
                                            console.log(response);
                                            register.value = "Wrong code";
                                            setTimeout(() => {
                                                register.value = "Verify code";
                                            }, 3000);
                                        }
                                    });
                                }
                            }
                        } else
                        // Set username
                        if (set_username.style.display != "none") {
                            if (username.value != "") {
                                let available = false;
                                $.ajax({
                                    url: '../assets/check_username.php',
                                    type: "POST",
                                    data: {
                                        username: username.value
                                    }
                                }).done(function(response) {
                                    if (response == "available") {
                                        username.style.outline = "1px solid green";
                                        usernameAvailable();
                                    } else {
                                        username.style.outline = "1px solid red";
                                        usernameUnavailable();
                                    }
                                });
                                function usernameAvailable() {
                                    set_username.style.display = "none";
                                    set_pw.style.display = "block";
                                    register.style.display = "none";
                                    step_back.style.display = "block";
                                    // Adding username to table
                                    tr = document.createElement('tr');
                                    tr.id = "reg-t-uname";
                                    tr.style.opacity = 0;
                                    setTimeout(() => {
                                        tr.style.opacity = 1;
                                        table.style.height = (table.offsetHeight + 30) + "px";
                                    }, 300);
                                    table.appendChild(tr);
                                    td_uname = document.createElement('td');
                                    td_uname.innerHTML = "Username:";
                                    td_uname_v = document.createElement('td');
                                    td_uname.style.textAlign = "right";
                                    td_uname_v.style.textAlign = "left";
                                    td_uname_v.innerHTML = username.value;
                                    tr.appendChild(td_uname);
                                    tr.appendChild(td_uname_v);
                                    pw.focus();
                                }
                                function usernameUnavailable() {
                                    username.focus();
                                    register.value = "Unavailable";
                                    setTimeout(() => {
                                        register.value = "Continue";
                                    }, 3000);
                                }
                            }
                        } else
                        // Set a password
                        if (set_pw.style.display != "none") {
                            if (pw.value != "" && pw.value === cpw.value && /\d/.test(pw.value) && /[!"#¤%&/()=?`^*_:;><,.\-\\'+¨]/.test(pw.value)) {
                                set_pw.style.display = "none";
                                set_terms.style.display = "block";

                                pwl = pw.value;
                                x = "*";
                                for (i = 0; i <= pwl.length; i++) {
                                    if (x.length < i) {
                                        x = x + "*";
                                    }
                                }
                                // Adding password to table
                                tr = document.createElement('tr');
                                tr.id = "reg-t-pw";
                                tr.style.opacity = 0;
                                setTimeout(() => {
                                    tr.style.opacity = 1;
                                    table.style.height = (table.offsetHeight + 30) + "px";
                                }, 300);
                                table.appendChild(tr);
                                td_pw = document.createElement('td');
                                td_pw.innerHTML = "Password:";
                                td_pw_v = document.createElement('td');
                                td_pw.style.textAlign = "right";
                                td_pw_v.style.textAlign = "left";
                                td_pw_v.innerHTML = x;
                                tr.appendChild(td_pw);
                                tr.appendChild(td_pw_v);
                            }
                        } else
                        // Accept terms
                        if (set_terms.style.display != "none") {
                            if (terms.checked) {
                                info_text.style.display = "none";
                                reg_form.style.display = "none";
                                reg_info.style.display = "none";
                                reg_packs.style.display = "block";
                            }
                        }

                        //if (age <= 14) {
                        //     Action for age group 0-14
                        //    youngChildAccount();
                        //} else if (age >= 15 && age <= 17) {
                        //     Action for age group 15-17
                        //    childAccount();
                        //} else if (age >= 18) {
                        //     Action for age group 18+
                        //    normalAccount();
                        //}

                        //input.addEventListener('keydown', handleKeyPress, { once: true });
                    }
                    
                    document.getElementById('register-password').addEventListener('keyup', function(event) {
                        checkPw();
                    });
                    document.getElementById('cpassword').addEventListener('keyup', function(event) {
                        checkPw();
                    });
                    function checkPw() {
                        const pw = document.getElementById('register-password');
                        const cpw = document.getElementById('cpassword');
                        const pwp = document.getElementById('pw_p');
                        const reg_btn = document.getElementById('register');

                        let pw_length = 0;
                        let pw_alpha = 0;
                        let pw_digit = 0;
                        let pw_special = 0;

                        if (pw.value.length > 0 || cpw.value.length > 0) {
                            if (pw.value.length > 7) {
                                document.getElementById('pw_l').classList.remove('fa-circle-xmark');
                                document.getElementById('pw_l').classList.add('fa-circle-check');
                                document.getElementById('pw_l').classList.add('ok');
                                pw_length = 1;
                            } else {
                                document.getElementById('pw_l').classList.remove('fa-circle-check');
                                document.getElementById('pw_l').classList.add('fa-circle-xmark');
                                document.getElementById('pw_l').classList.remove('ok');
                                pw_length = 0;
                            }

                            if (/\d/.test(pw.value)) {
                                document.getElementById('pw_n').classList.remove('fa-circle-xmark');
                                document.getElementById('pw_n').classList.add('fa-circle-check');
                                document.getElementById('pw_n').classList.add('ok');
                                pw_digit = 1;
                            } else {
                                document.getElementById('pw_n').classList.remove('fa-circle-check');
                                document.getElementById('pw_n').classList.add('fa-circle-xmark');
                                document.getElementById('pw_n').classList.remove('ok');
                                pw_digit = 0;
                            }

                            if (/[!"#¤%&/()=?`^*_:;><,.\-\\'+¨]/.test(pw.value)) {
                                document.getElementById('pw_s').classList.remove('fa-circle-xmark');
                                document.getElementById('pw_s').classList.add('fa-circle-check');
                                document.getElementById('pw_s').classList.add('ok');
                                pw_special = 1;
                            } else {
                                document.getElementById('pw_s').classList.remove('fa-circle-check');
                                document.getElementById('pw_s').classList.add('fa-circle-xmark');
                                document.getElementById('pw_s').classList.remove('ok');
                                pw_special = 0;
                            }

                            if (/[a-zA-Z]/.test(pw.value)) {
                                document.getElementById('pw_a').classList.remove('fa-circle-xmark');
                                document.getElementById('pw_a').classList.add('fa-circle-check');
                                document.getElementById('pw_a').classList.add('ok');
                                pw_alpha = 1;
                            } else {
                                document.getElementById('pw_a').classList.remove('fa-circle-check');
                                document.getElementById('pw_a').classList.add('fa-circle-xmark');
                                document.getElementById('pw_a').classList.remove('ok');
                                pw_alpha = 0;
                            }

                            pw_strength = pw_length + pw_digit + pw_special + pw_alpha;
                            if (pw_strength == 0) {
                                pwp.value = 0;
                            }
                            if (pw_strength == 1) {
                                pwp.value = 25;
                            }
                            if (pw_strength == 2) {
                                pwp.value = 50;
                            }
                            if (pw_strength == 3) {
                                pwp.value = 75;
                            }
                            if (pw_strength == 4) {
                                pwp.value = 100;
                                if (pw.value === cpw.value) {
                                    document.getElementById('pw_m').classList.remove('fa-circle-xmark');
                                    document.getElementById('pw_m').classList.add('fa-circle-check');
                                    document.getElementById('pw_m').classList.add('ok');
                                    reg_btn.style.display = "block";
                                } else {
                                    document.getElementById('pw_m').classList.remove('fa-circle-check');
                                    document.getElementById('pw_m').classList.add('fa-circle-xmark');
                                    document.getElementById('pw_m').classList.remove('ok');
                                    reg_btn.style.display = "none";
                                }
                            } else {
                                reg_btn.style.display = "none";
                            }
                        } else {
                            document.getElementById('pw_l').classList.remove('fa-circle-check');
                            document.getElementById('pw_l').classList.add('fa-circle-xmark');
                            document.getElementById('pw_a').classList.remove('fa-circle-check');
                            document.getElementById('pw_a').classList.add('fa-circle-xmark');
                            document.getElementById('pw_n').classList.remove('fa-circle-check');
                            document.getElementById('pw_n').classList.add('fa-circle-xmark');
                            document.getElementById('pw_m').classList.remove('fa-circle-check');
                            document.getElementById('pw_m').classList.add('fa-circle-xmark');
                            document.getElementById('pw_s').classList.remove('fa-circle-check');
                            document.getElementById('pw_s').classList.add('fa-circle-xmark');

                            document.getElementById('pw_l').classList.remove('ok');
                            document.getElementById('pw_a').classList.remove('ok');
                            document.getElementById('pw_n').classList.remove('ok');
                            document.getElementById('pw_m').classList.remove('ok');
                            document.getElementById('pw_s').classList.remove('ok');

                            reg_btn.style.display = "none";
                        }
                    };

                    function selectPackage(x) {
                        let set_dob = document.getElementById('set_dob');
                        let set_name = document.getElementById('set_name');
                        let set_email = document.getElementById('set_email');
                        let set_email_c = document.getElementById('set_email_verify');
                        let set_username = document.getElementById('set_username');
                        let set_pw = document.getElementById('set_password');
                        let set_terms = document.getElementById('set_terms');
                        let send_again = document.getElementById('send_again');
                        let register = document.getElementById('register');
                        let step_back = document.getElementById('step_back');
                        let err_msg = document.getElementById('err_msg');

                        const dob_v = document.getElementById('dob').value;
                        const age = calculateAge(dob_v);
                        
                        const fname = document.getElementById('fname'); // First name
                        const mname = document.getElementById('mname'); // Middle name
                        const lname = document.getElementById('lname'); // Last name

                        const email_c = document.getElementById('email-check'); // Email check (special)
                        const email = document.getElementById('register-email'); // Email
                        const email_verify = document.getElementById('email-verify'); // Email code verify
                        const username = document.getElementById('username'); // Username
                        const pw = document.getElementById('register-password'); // Password
                        const cpw = document.getElementById('cpassword'); // Confirm password
                        const refer = document.getElementById('refer').value; // Referral code
                        
                        if (reg_packs.style.display != "none") {
                            const ppr = document.getElementById('ppr');
                            const ppu = document.getElementById('ppu');
                            const vv = document.getElementById('vv');
                            const vi = document.getElementById('vi');
                            if (x == "op") {
                                pack = "op";
                            } else 
                            if (x == "pp") {
                                pack = "pp";
                            } else {
                                pack = "cp";
                            }
                            
                            var private;
                            var public;
                            var visible;
                            var invisible;

                            if (pack == "cp") {
                                private = ppr.value;
                                public = ppu.value;
                                visible = vv.value;
                                invisible = vi.value;
                            }

                            // Disable all buttons and inputs, and show a loading animation while waiting for registration status.
                            const reg_packs = document.getElementById('reg_packs');
                            const reg_form = document.getElementById('log_reg_form');
                            const reg_info = document.getElementById('reg_info');
                            const welcomeScreen = document.getElementById('welcome-screen');
                            reg_packs.style.display = "none";
                            welcomeScreen.style.display = "block";

                            $.ajax({
                                url: '../assets/signup.php',
                                type: "POST",
                                data: {
                                    username: username.value,
                                    fname: fname.value,
                                    mname: mname.value,
                                    lname: lname.value,
                                    email: email.value,
                                    email_c: email_c.value,
                                    password: pw.value,
                                    dob: dob.value,
                                    pack: pack,
                                    private: private,
                                    public: public,
                                    visible: visible,
                                    invisible: invisible,
                                    refer: refer
                                }
                            }).done(function(response) {
                                response = JSON.parse(response);
                                if (response.responseCode === "ok") {
                                    window.location.href='../register?complete&user='+response.user+'&token='+response.token;
                                } else {
                                    welcomeScreen.style.display = "none";
                                    reg_packs.style.display = "block";
                                    err_msg.style.display = "block";
                                    err_msg.innerHTML = response.responseMessage;
                                    setTimeout(() => {
                                        err_msg.style.display = "none";
                                    }, 3000);
                                }
                            }).fail(function(response) {
                                console.log(response);
                            });
                        }
                    }

                    function showPassword(x) {
                        const password = document.getElementById(x);
                        if (password.type == "password") {
                            password.type = "text";
                        } else {
                            password.type = "password";
                        }
                    }

                    function handleKeyPress(event) {
                        if (event.keyCode === 13) { // ENTER key pressed
                            //register();
                        }
                    }

                    function tglErmsg(x) {
                        if (x.hasAttribute("hidden")) {
                            x.removeAttribute("hidden");
                            setTimeout(() => {
                                x.setAttribute("hidden","");
                            }, 3000);
                        } else {
                            x.setAttribute("hidden","");
                        }
                    }
                </script>
