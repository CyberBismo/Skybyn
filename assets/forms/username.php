                <h3>Create a username</h3>
                <div class="set_username_form">
                    <i class="fa-solid fa-user"></i>
                    <input type="text" id="username" onkeydown="hitEnterUsername(this)" placeholder="Choose a username" onkeyup="checkUsername()" title="" required autofocus>
                    <input type="submit" id="set_username" onclick="setUsername()" value="Continue" hidden>
                </div>
                <div class="links">
                    <button onclick="removeUsername()">Go back</a>
                </div>

                <script>
                    function hitEnterUsername(input) {
                        const button = document.getElementById('login');

                        function handleKeyPress(event) {
                            if (event.keyCode === 13) {
                                setUsername();
                            }
                        }

                        input.addEventListener('keydown', handleKeyPress, { once: true });
                    }
                    function checkUsername() {
                        const username = document.getElementById('username');
                        const update = document.getElementById('set_username');
                        if (username.value != "") {
                            $.ajax({
                                url: 'assets/check_username.php',
                                type: "POST",
                                data: {
                                    username : username.value
                                }
                            }).done(function(response) {
                                if (response == "available") {
                                    username.style.outline = "1px solid green";
                                    update.removeAttribute("hidden");
                                } else {
                                    username.style.outline = "1px solid red";
                                    update.setAttribute("hidden","");
                                }
                            });
                        } else {
                            username.style.outline = "none";
                            update.setAttribute("hidden","");
                        }
                    }

                    
                    function setUsername() {
                        let username = document.getElementById('username');
                        $.ajax({
                            url: 'assets/set_username.php',
                            type: "POST",
                            data: {
                                username : username.value
                            }
                        }).done(function(response) {
                            window.location.href = "./";
                        });
                    }

                    function removeUsername() {
                        document.cookie = "username=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
                        window.location.href = "./";
                    }
                </script>