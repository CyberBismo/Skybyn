                <?php if (skybyn('register') == "1") {?>
                <h2>Sign up</h2>
                
                <div id="set_dob">
                    <p>Enter your date of birth to get started</p>
                    <i class="fa-solid fa-calendar-days"></i>
                    <input type="date" id="dob" min="1960-01-01" max="<?=date("Y")-15 ."-".date("m")."-".date("d")?>" title="Enter your date of birth" autofocus>
                </div>

                <div id="set_name" style="display: none">
                    <p>Now enter your full name</p>
                    <div class="split">
                        <div class="split-box">
                            <i class="fa-solid fa-user"></i>
                            <input type="name" id="fname" placeholder="First name *" autocomplete="new-password" required>
                        </div>
                        <div class="split-box">
                            <i class="fa-solid fa-user"></i>
                            <input type="name" id="mname" placeholder="Middle name" autocomplete="new-password">
                        </div>
                    </div>
                    <i class="fa-solid fa-user"></i>
                    <input type="name" id="lname" placeholder="Last name *" autocomplete="new-password" required>
                </div>

                <div id="set_email" style="display: none">
                    <p>Great! Now enter your preferred email address</p> 
                    <i class="fa-solid fa-at"></i>
                    <input type="email" id="register-email" pattern="[a-zA-Z0-9._\-]+@[a-zA-Z0-9.\-]+\.[a-zA-Z]{2,}" placeholder="E-mail address" title="example@example.com" oninput="setCustomValidity('')" oninvalid="setCustomValidity('Please enter a valid e-mail address')" autocomplete="new-password" required>
                </div>

                <div id="set_email_verify" style="display: none">
                    <p>Okey, let's verify. Enter the code we just sent you.</p>
                    <i class="fa-solid fa-arrows-rotate" id="email-verify-status"></i>
                    <input type="number" id="email-verify" pattern="[a-zA-Z0-9]" placeholder="Enter code" title="example@example.com" oninput="setCustomValidity('')" oninvalid="setCustomValidity('Please enter a valid e-mail address')" autocomplete="new-password" required>
                </div>

                <div id="set_username" style="display: none">
                    <p>Now make a username for yourself.</p>
                    <i class="fa-solid fa-user"></i>
                    <input type="text" id="username" placeholder="Choose a username" title="" required>
                </div>

                <div id="set_password" style="display: none">
                    <i class="fa-solid fa-key"></i>
                    <input type="password" id="register-password" placeholder="Password" autocomplete="new-password" required>
                    <i class="fa-regular fa-eye" onclick="showPassword('register-password')"></i>
                    
                    <i class="fa-solid fa-key"></i>
                    <input type="password" id="cpassword" placeholder="Confirm password" autocomplete="new-password" required>
                    <i class="fa-regular fa-eye" onclick="showPassword('cpassword')"></i>
                </div>

                <div class="terms" id="set_terms" style="display: none">
                    <input type="checkbox" id="terms" required>
                    <label for="terms">I accept the <span onclick="showTerms()">terms and conditions</a>.</label>
                </div>

                <input type="submit" id="send_again" onclick="hitEnterRegister('resend')" style="display: none;margin-top: 3px">
                <input type="submit" id="register" onclick="hitEnterRegister('')" value="Continue" style="margin-top: 3px">
                <input type="submit" id="step_back" onclick="stepBack()" value="Go back" style="display: block;margin-top: 3px">

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
                        let reg_form = document.getElementById('log_reg_form');

                        if (reg_packs.style.display == "block") {
                            reg_packs.style.display = "none";
                            set_terms.style.display = "block";
                            info_text.style.display = "block";
                            reg_form.style.display = "block";
                        } else
                        if (set_terms.style.display == "block") {
                            set_terms.style.display = "none";
                            set_pw.style.display = "block";
                            register.value = "Continue";
                            document.getElementById('reg-t-pw').remove();
                        } else
                        if (set_pw.style.display == "block") {
                            set_pw.style.display = "none";
                            set_username.style.display = "block";
                            register.value = "Continue";
                            document.getElementById('reg-t-uname').remove();
                        } else
                        if (set_username.style.display == "block") {
                            set_username.style.display = "none";
                            set_email_c.style.display = "block";
                            register.value = "Verify code";
                            document.getElementById('reg-t-email').remove();
                        } else
                        if (set_email_c.style.display == "block") {
                            set_email_c.style.display = "none";
                            set_email.style.display = "block";
                            send_again.style.display = "block";
                            register.value = "Continue";
                            step_back.value = "Go back";
                            document.getElementById('reg-t-email').remove();
                        } else
                        if (set_email.style.display == "block") {
                            set_email.style.display = "none";
                            set_name.style.display = "block";
                            send_again.style.display = "none";
                            register.style.display = "block";
                            register.value = "Continue";
                            document.getElementById('reg-t-name').remove();
                        } else
                        if (set_name.style.display == "block") {
                            set_name.style.display = "none";
                            set_dob.style.display = "block";
                            register.value = "Continue";
                            step_back.style.display = "none";
                            document.getElementById('reg-t-age').remove();
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

                        let wel_inf = document.getElementById("welcome_info");
                        let info_text = document.getElementById('info_text');
                        let intro = document.getElementById('intro');
                        let info_right = document.getElementById('info_right');
                        let table = document.getElementById('info-table');
                        let reg_packs = document.getElementById('reg_packs');
                        let reg_form = document.getElementById('log_reg_form');

                        const dob_v = document.getElementById('dob').value;
                        const age = calculateAge(dob_v);
                        
                        const fname = document.getElementById('fname'); // First name
                        const mname = document.getElementById('mname'); // Middle name
                        const lname = document.getElementById('lname'); // Last name

                        const email = document.getElementById('register-email'); // Email
                        const email_verify = document.getElementById('email-verify'); // Email code verify
                        const username = document.getElementById('username'); // Username
                        const pw = document.getElementById('register-password'); // Password
                        const cpw = document.getElementById('cpassword'); // Confirm password

                        autoInfo();
                        
                        // Verify date of birth value and enter full name
                        if (set_dob.style.display != "none") {
                            if (dob_v != "") {
                                set_dob.style.display = "none";
                                set_name.style.display = "block";
                                step_back.style.display = "block";
                                intro.style.display = "none";
                                info_right.style.display = "block";
                                fname.focus();
                                // Adding age to table
                                tr_age = document.createElement('tr');
                                tr_age.id = "reg-t-age";
                                table.appendChild(tr_age);
                                td_age = document.createElement('td');
                                td_age.innerHTML = "Age:";
                                td_age_v = document.createElement('td');
                                td_age.style.textAlign = "right";
                                td_age.style.width = "80.25px";
                                td_age_v.style.textAlign = "left";
                                td_age_v.innerHTML = age;
                                tr_age.appendChild(td_age);
                                tr_age.appendChild(td_age_v);
                            }
                        } else
                        // Check full name and enter email
                        if (set_name.style.display != "none") {
                            if (fname.value != "" && lname.value != "") {
                                set_name.style.display = "none";
                                set_email.style.display = "block";
                                email.focus();
                                register.value = "Send code";
                                // Adding name to table
                                tr_name = document.createElement('tr');
                                tr_name.id = "reg-t-name";
                                table.appendChild(tr_name);
                                td_name = document.createElement('td');
                                td_name.innerHTML = "Name:";
                                td_name_v = document.createElement('td');
                                td_name.style.textAlign = "right";
                                td_name_v.style.textAlign = "left";
                                td_name_v.innerHTML = fname.value+" "+mname.value+" "+lname.value;
                                tr_name.appendChild(td_name);
                                tr_name.appendChild(td_name_v);
                            }
                        } else
                        // Verify email and create a username
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
                                    tr_email = document.createElement('tr');
                                    tr_email.id = "reg-t-email";
                                    table.appendChild(tr_email);
                                    td_email = document.createElement('td');
                                    td_email.style.textAlign = "right";
                                    td_email.innerHTML = "Email:";
                                    tr_email.appendChild(td_email);
                                    tr_email.appendChild(td_email_v);
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
                                        username : username.value
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
                                    // Adding username to table
                                    tr_uname = document.createElement('tr');
                                    tr_uname.id = "reg-t-uname";
                                    table.appendChild(tr_uname);
                                    td_uname = document.createElement('td');
                                    td_uname.innerHTML = "Username:";
                                    td_uname_v = document.createElement('td');
                                    td_uname.style.textAlign = "right";
                                    td_uname_v.style.textAlign = "left";
                                    td_uname_v.innerHTML = username.value;
                                    tr_uname.appendChild(td_uname);
                                    tr_uname.appendChild(td_uname_v);
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
                            if (pw.value != "" && pw.value === cpw.value) {
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
                                tr_pw = document.createElement('tr');
                                tr_pw.id = "reg-t-pw";
                                table.appendChild(tr_pw);
                                td_pw = document.createElement('td');
                                td_pw.innerHTML = "Password:";
                                td_pw_v = document.createElement('td');
                                td_pw.style.textAlign = "right";
                                td_pw_v.style.textAlign = "left";
                                td_pw_v.innerHTML = x;
                                tr_pw.appendChild(td_pw);
                                tr_pw.appendChild(td_pw_v);
                            }
                        } else
                        // Accept terms
                        if (set_terms.style.display != "none") {
                            if (terms.checked) {
                                info_text.style.display = "none";
                                reg_form.style.display = "none";
                                $.ajax({
                                    url: '../assets/signup.php',
                                    type: "POST",
                                    data: {
                                        username: username.value,
                                        fname: fname.value,
                                        mname: mname.value,
                                        lname: lname.value,
                                        email: email.value,
                                        password: pw.value,
                                        dob: dob.value
                                    }
                                }).done(function(response) {
                                    if (response === "signup_complete") {
                                        reg_packs.style.display = "block";
                                    }
                                });
                            }
                        } else
                        // Select package
                        if (reg_packs.style.display != "none") {
                            window.location.href='./';
                            $.ajax({
                                url: '../assets/reg_pack_sel.php',
                                type: "POST",
                                data: {
                                    pack : input
                                }
                            }).done(function(response) {
                                window.location.href='../';
                            });
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
