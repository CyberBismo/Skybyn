<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Skybyn App Dev</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="">
        <style>
            html,
            body {
                margin: 0 auto;
                font-family: Arial, Helvetica, sans-serif;
                color: white;
                background: #232325;
            }

            .header {
                width: 100%;
                padding: 0 20px;
                box-sizing: border-box;
            }

            .container {
                display: flex;
                flex-wrap: wrap;
                gap: 10px;
                padding: 20px;
                box-sizing: border-box;
            }

            .part {
                max-width: 400px;
                padding: 20px;
                background: rgba(0,0,0,.1);
                border-radius: 20px;
            }
            .part h2 {
                margin: 0;
            }

            ul {
                list-style: none;
                padding: 0 0 0 10px;
            }
            li {
                padding: 10px 0;
            }

            a {
                color: grey;
                text-decoration: none;
            }
        </style>
    </head>
    <body>
        <div class="header">
            <h1>Skybyn - The Mobile App</h1>
        </div>
        <div class="container">
            <div class="part">
                <h2># Colors</h2>
                <p>
                    <h3>Dark Theme</h3>
                    Main color:
                    <ul>
                        <li>HEX: #232325</li>
                        <li>RGB: 35, 35, 37</li>
                        <li><a href="https://g.co/kgs/bzuiSgQ" target="_blank">Google color picker</a></li>
                    </ul>
                    Other colors:
                    <ul>
                        <li>RGBA: 0,0,0,.# (The # may vary for each element)</li>
                    </ul>
                </p>
            </div>
            <div class="part">
                <h2>Features</h2>
                <ul>
                    <li>QR (to be scanned)</li>
                    <ul>
                        <li>Login to website</li>
                    </ul>
                </ul>
            </div>
            <div class="part">
                <h2>Security</h2>
                <p>
                    All credentials are encrypted using a one-way encryption method.<br>
                    <br>
                    Email are only available for you.
                </p>
            </div>
            <div class="part">
                <h2>Design</h2>
                <p>
                    <h3>Corners</h3>
                    Most elements should have rounded corners. The corner radius may vary. Inner elements should have a fitted radius for it's parent.
                    <h3>Element Backgrounds</h3>
                    Use alpha/transparency and blur/focus difference to overlapping layers/elements. 
                </p>
            </div>
            <div class="part">
                <h2>Layout - Logged out</h2>
                <p>
                    <ul>
                        <li>Loading screen (Splash screen)</li>
                        <hr>
                        <li>Login</li>
                        <li>Register</li>
                        <li>Forgot</li>
                        <li>Reset</li>
                    </ul>
                </p>
            </div>
            <div class="part">
                <h2>Layout - Logged in</h2>
                <p>
                    <ul>
                        <li>Loading screen (Splash screen)</li>
                        <hr>
                        <li>Feed (main screen)</li>
                        <li>Profile</li>
                        <li>Friends</li>
                        <li>Messages</li>
                        <li>Notifications</li>
                        <li>Group chats</li>
                        <li>Pages</li>
                        <li>Markets</li>
                        <li>Games</li>
                    </ul>
                </p>
            </div>
            <div class="part">
                <h2>API usage</h2>
                <p>
                    <h3>Defaults</h3>
                    <ul>
                        <li>URL: https://api.skybyn.com/< request >.php</li>
                        <li>Method: POST</li>
                        <li>Output: JSON</li>
                    </ul>
                    <ul>
                        <li>Response</li>
                        <li>
                            <ul>
                                <li>responseCode: integer (0 / 1)</li>
                                <li>message: string (value)</li>
                                <li>[data]: [value]</li>
                            </ul>
                        </li>
                    </ul>
                </p>
            </div>
            <div class="part">
                <h2>Login API</h2>
                <p>
                    <h3>Request: login</h3>
                    <ul>
                        <li>Post variables</li>
                        <li>
                            <ul>
                                <li>user = [string]</li>
                                <li>password = [string]</li>
                            </ul>
                        </li>
                    </ul>
                    <ul>
                        <li>Success response</li>
                        <li>
                            <ul>
                                <li>responseCode: 1</li>
                                <li>message: "Welcome!"</li>
                                <li>userID: [int]</li>
                            </ul>
                        </li>
                    </ul>
                </p>
            </div>
            <div class="part">
                <h2>Register API</h2>
                <p>Coming soon..</p>
            </div>
            <div class="part">
                <h2>Forgot API</h2>
                <p>Coming soon..</p>
            </div>
            <div class="part">
                <h2>AI</h2>
                <p>Future feature</p>
            </div>
        </div>
    </body>
</html>