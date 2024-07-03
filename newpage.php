<?php include_once "./assets/header.php";

if (!isset($_SESSION['user'])) {
    ?><meta http-equiv="Refresh" content="0; url='./'" /><?php
}
?>
        <div class="page-container">
            <div class="page-head">
                Create New Page
            </div>
            <div class="new-page-create">
                <div class="left" id="left">
                    <h3>What are pages for<span onclick="showLeft()"><i class="fa-solid fa-angles-right"></i></span></h3>
                    <ul>
                        <li>Something</li>
                        <li>Something</li>
                        <li>Something</li>
                        <li>Something</li>
                        <li>Something</li>
                    </ul>
                </div>
                <div class="form">
                    <i class="fa-solid fa-sign-hanging"></i>
                    <input type="text" name="group_name" id="ng-name" placeholder="Name of group" autofocus required>
                    <i class="fa-solid fa-quote-right"></i>
                    <textarea name="group_desc" id="ng-desc" placeholder="Who is this group for"></textarea>
                    <div class="new-group-privacy">
                        <span><input type="radio" name="group_privacy" value="open" id="ng-p-open" checked> Open</span>
                        <span><input type="radio" name="group_privacy" value="locked" id="ng-p-locked"> Locked</span>
                        <span><input type="radio" name="group_privacy" value="private" id="ng-p-private"> Private</span>
                    </div>
                    <div id="lock-options" style="display: none">
                        <select name="group_lock_type" id="ng-lt" onchange="lockType(this)">
                            <option value="" hidden>- Select lock type -</option>
                            <option value="password">Password</option>
                            <option value="pin">PIN code</option>
                        </select>
                        <input type="password" name="group_password" id="lt-password" placeholder="Password" title="Enter a password to access the group" autocomplete="new-password" style="display: none">
                        <input type="password" name="group_pin" id="lt-pin" pattern="[0-9]{4,}" placeholder="PIN" title="Enter a PIN code to access the group" autocomplete="new-password" style="display: none">
                    </div>
                    
                    <input type="submit" onclick="createGroup()" value="Create">
                </div>
            </div>
        </div>

        <script>
            const privacyInputs = document.getElementsByName('group_privacy');
            privacyInputs.forEach(element => {
                element.addEventListener('change', function () {
                    const lockOptions = document.getElementById('lock-options');
                    if (element.value == "locked") {
                        lockOptions.style.display = "block";
                    } else {
                        lockOptions.style.display = "none";
                    }
                });
            });

            function showLeft() {
                const left = document.getElementById('left');
                if (left.style.height == "auto") {
                    left.style.height = "80px";
                } else {
                    left.style.height = "auto";
                }
            }
            function lockType(x) {
                const pw = document.getElementById('lt-password');
                const pin = document.getElementById('lt-pin');
                if (x.value == "password") {
                    pw.style.display = "block";
                    pin.style.display = "none";
                }
                if (x.value == "pin") {
                    pin.style.display = "block";
                    pw.style.display = "none";
                }
            }
            function createGroup() {
                const name = document.getElementById('ng-name');
                const desc = document.getElementById('ng-desc');
                const privacy = document.getElementsByName('privacy').value;
                const lockType = document.getElementById('ng-lt').value;
                const password = document.getElementById('lt-password').value;
                const pin = document.getElementById('lt-pin').value;
                
                if (privacy === 'locked') {

                    if (lockType === 'password') {
                        password = password;
                    } else if (lockType === 'pin') {
                        pin = pin;
                    }
                }
                
                const data = {
                    group_name: name.value,
                    group_desc: desc.value,
                    group_privacy: privacy,
                    group_lock_type: lockType,
                    group_password: password,
                    group_pin: pin,
                };

                $.ajax({
                    url: '../assets/group_new.php',
                    type: "POST",
                    data: data,
                }).done(function (response) {
                    var result = JSON.parse(response);
                    var response = result.response;
                    var message = result.message;

                    if (response === "ok") {
                        window.location.href = "./group?id=" + message;
                    }
                    if (response === "error") {
                        alert(message);
                    }
                });
            }
        </script>
    </body>
</html>