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
                    <input type="email" id="register-email" pattern="[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}" placeholder="E-mail address" title="example@example.com" oninput="setCustomValidity('')" oninvalid="setCustomValidity('Please enter a valid e-mail address')" autocomplete="new-password" required>
                </div>

                <div id="set_email_verify" style="display: none">
                    <p>Okey, let's verify. Enter the code we just sent you.</p>
                    <i class="fa-solid fa-arrows-rotate" id="email-verify-status"></i>
                    <input type="number" id="email-verify" pattern="[a-zA-Z0-9]" placeholder="Enter code" title="example@example.com" oninput="setCustomValidity('')" oninvalid="setCustomValidity('Please enter a valid e-mail address')" autocomplete="new-password" required>
                </div>

                <div id="set_username" style="display: none">
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

                <input type="submit" id="register" onclick="hitEnterRegister(this)" value="Continue">
                <input type="submit" id="step_back" onclick="hitEnterRegister(this)" value="Go back" style="display: none;margin-top: 3px">

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
                    
                    function showCustom() {
                        const text = document.getElementById('custom-text');
                        const set = document.getElementById('custom-set');
                        const btn = document.getElementById('custom-set-btn');
                        text.style.display = "none";
                        set.style.display = "flex";
                        btn.style.display = "block";
                    }

                    function hitEnterRegister(input) {
                        let set_dob = document.getElementById('set_dob');
                        let set_name = document.getElementById('set_name');
                        let set_email = document.getElementById('set_email');
                        let set_email_c = document.getElementById('set_email_verify');
                        let set_username = document.getElementById('set_username');
                        let set_pw = document.getElementById('set_password');
                        let set_terms = document.getElementById('set_terms');
                        let register = document.getElementById('register');
                        let step_back = document.getElementById('step_back');
                        let err_msg = document.getElementById('err_msg');

                        let wel_inf = document.getElementById("welcome_info");
                        let info_text = document.getElementById('info_text');
                        let intro = document.getElementById('intro');
                        let info_right = document.getElementById('info_right');
                        let table = document.getElementById('info-table');
                        let reg_packs = document.getElementById('reg_packs');
                        let reg_form = document.getElementById('reg_form');

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
                        
                        if (input.id == "step_back") {
                            if (set_name.style.display == "block") {
                                set_name.style.display = "none";
                                set_email.style.display = "block";
                                register.value = "Continue";
                                step_back.style.display = "none";
                            } else 
                            if (set_email.style.display == "block") {
                                set_email.style.display = "none";
                                set_email_verify.style.display = "block";
                                register.value = "Send code";
                            } else 
                            if (set_email_verify.style.display == "block") {
                                set_email_verify.style.display = "none";
                                set_username.style.display = "block";
                                register.value = "Verify";
                            } else 
                            if (set_username.style.display == "block") {
                                set_username.style.display = "none";
                                set_pw.style.display = "block";
                                register.value = "Continue";
                            }
                            if (set_pw.style.display == "block") {
                                set_pw.style.display = "none";
                                set_name.style.display = "block";
                                register.value = "Complete";
                            }
                        } else {
                            // Verify date of birth value and enter full name
                            if (set_dob.style.display != "none") {
                                if (dob_v != "") {
                                    set_dob.style.display = "none";
                                    set_name.style.display = "block";
                                    step_back.style.display = "block";
                                    intro.style.display = "none";
                                    info_right.style.display = "block";
                                    fname.focus();
                                    // Display the right info box
                                    //table.style.padding = "0 5%";
                                    //table.classList.toggle('split-box');
                                    //intro.classList.toggle('split-box');
                                    //info_text.classList.toggle('split');
                                    // Adding age to table
                                    tr_age = document.createElement('tr');
                                    table.appendChild(tr_age);
                                    td_age = document.createElement('td');
                                    td_age.innerHTML = "Age:";
                                    td_age_v = document.createElement('td');
                                    td_age.style.textAlign = "right";
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
                                    // Adding email to table
                                    tr_email = document.createElement('tr');
                                    table.appendChild(tr_email);
                                    td_email = document.createElement('td');
                                    td_email.innerHTML = "Email:";
                                    td_email_v = document.createElement('td');
                                    td_email_v.id = "email-s";
                                    td_email.style.textAlign = "right";
                                    td_email_v.style.textAlign = "left";
                                    td_email_v.innerHTML = "Verifying..";
                                    tr_email.appendChild(td_email);
                                    tr_email.appendChild(td_email_v);
                                    // Send an email verification code
                                    <?php if ($dev_access == true) {?>
                                    set_email.style.display = "none";
                                    set_email_verify.style.display = "block";
                                    email_verify.focus();
                                    register.value = "Verify code";
                                    <?php } else {?>
                                    $.ajax({
                                        url: './assets/check_email.php',
                                        type: "POST",
                                        data: {
                                            email: email
                                        }
                                    }).done(function(response) {
                                        if (response === "sent") {
                                            set_email.style.display = "none";
                                            set_email_verify.style.display = "block";
                                            email_verify.focus();
                                            register.value = "Verify code";
                                        }
                                    });
                                    <?php }?>
                                }
                            } else
                            // Verify email
                            if (set_email_verify.style.display != "none") {
                                if (email_verify.value != "") {
                                    // Checking code for verification
                                    <?php if ($dev_access == true) {?>
                                    document.getElementById('email-s').innerHTML = email.value;
                                    set_email_verify.style.display = "none";
                                    set_username.style.display = "block";
                                    username.focus();
                                    register.value = "Continue";
                                    <?php } else {?>
                                    $.ajax({
                                        url: './assets/register_email_verify.php',
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
                                            register.value = "Continue";
                                        }
                                    });
                                    <?php }?>
                                }
                            } else
                            // Set username
                            if (set_username.style.display != "none") {
                                if (username.value != "") {
                                    let available = false;
                                    <?php if ($dev_access == true) {?>
                                    username.style.outline = "1px solid green";
                                    available = true;
                                    <?php } else {?>
                                    $.ajax({
                                        url: 'assets/check_username.php',
                                        type: "POST",
                                        data: {
                                            username : username.value
                                        }
                                    }).done(function(response) {
                                        if (response == "available") {
                                            username.style.outline = "1px solid green";
                                            available = true;
                                        } else {
                                            username.style.outline = "1px solid red";
                                            available = false;
                                        }
                                    });
                                    <?php }?>
                                    if (available == true) {
                                        set_username.style.display = "none";
                                        set_pw.style.display = "block";
                                        // Adding username to table
                                        tr_uname = document.createElement('tr');
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
                                }
                            } else
                            // Set a password
                            if (set_pw.style.display != "none") {
                                if (pw.value != "" && pw.value === cpw.value) {
                                    set_pw.style.display = "none";
                                    set_terms.style.display = "block";
                                    // Adding password to table
                                    tr_pw = document.createElement('tr');
                                    table.appendChild(tr_pw);
                                    td_pw = document.createElement('td');
                                    td_pw.innerHTML = "Password:";
                                    td_pw_v = document.createElement('td');
                                    td_pw.style.textAlign = "right";
                                    td_pw_v.style.textAlign = "left";
                                    td_pw_v.innerHTML = "Secure";
                                    tr_pw.appendChild(td_pw);
                                    tr_pw.appendChild(td_pw_v);
                                }
                            } else
                            // Accept terms
                            if (set_terms.style.display != "none") {
                                if (terms.checked) {
                                    info_text.style.display = "none";
                                    reg_form.style.display = "none";
                                    reg_packs.style.display = "block";
                                    $.ajax({
                                        url: './assets/signup.php',
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
                                    url: 'assets/reg_pack_sel.php',
                                    type: "POST",
                                    data: {
                                        pack : input
                                    }
                                }).done(function(response) {
                                    window.location.href='./';
                                });
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

                    function register() {
                        let fname = document.getElementById('fname').value;
                        let mname = document.getElementById('mname').value;
                        let lname = document.getElementById('lname').value;
                        let username = document.getElementById('username').value;
                        let email = document.getElementById('register-email').value;
                        let password = document.getElementById('register-password').value;
                        let cpassword = document.getElementById('cpassword').value;
                        let errmsg = document.getElementById('err_msg');
                        let dob = document.getElementById('dob').value;
                        let terms = document.getElementById('terms');

                        $.ajax({
                            url: './assets/signup.php',
                            type: "POST",
                            data: {
                                username: username,
                                fname: fname,
                                mname: mname,
                                lname: lname,
                                email: email,
                                password: password,
                                dob: dob
                            }
                        }).done(function(response) {
                            if (response === "signup_complete") {
                                window.location.href = "./";
                            }
                        });
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
