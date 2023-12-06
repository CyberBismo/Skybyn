                <h3>Create a username</h3>
                <div class="set_username_form">
                    <input type="text" id="username" placeholder="Choose a username" onkeyup="checkUsername()" title="" required autofocus>
                    <input type="submit" id="set_username" onclick="setUsername()" value="Continue" hidden>
                </div>

                <script>
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
                        const username = document.getElementById('username');
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
                </script>