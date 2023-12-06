<!DOCTYPE html>
<html>
    <head>
        <title>Test Token Email</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <style>
            :root {
                --lightblue: rgba(183,231,236,1);
                --blue: rgba(35,176,255,1);
                --greyblue: rgba(42,106,133,1);
            }

            body {
                margin: 0;
                padding: 20px;
                font-family: Arial, sans-serif;
                color: white;
                background: rgb(42,106,133);
                background-size: cover;
                background-position-y: bottom;
                background-position-x: center;
                background-attachment: fixed;
                font-size: 16px;
                line-height: 1.5;
            }

            .container {
                max-width: 600px;
                margin: 0 auto;
                color: white;
                text-align: center;
                background: rgba(0,0,0,0.1);
                padding: 30px;
                border-radius: 5px;
                box-shadow: 0px 0px 10px rgba(0,0,0,0.1);
            }

            .logo {
                display: block;
                width: 100px;
                height: auto;
                margin: 0 auto;
                padding: 0;
            }

            .logo img {
                display: block;
                width: 100%;
                height: auto;
                margin: 0;
                padding: 0;
            }

            h1 {
                font-size: 24px;
                margin-top: 20px;
                margin-bottom: 20px;
                text-align: center;
            }

            p {
                margin-top: 0;
                margin-bottom: 20px;
            }

            button {
                background-color: #4CAF50;
                color: #fff;
                border: none;
                padding: 10px 20px;
                border-radius: 5px;
                font-size: 16px;
                cursor: pointer;
                transition: background-color 0.3s ease;
            }

            button:hover {
                background-color: #3e8e41;
            }

            .code-box {
                margin: 40px 0;
            }
            .code-box code {
                width: auto;
                background-color: rgba(0,0,0,0.1);
                border: 1px solid rgba(0,0,0,0.1);
                padding: 20px 40px;
                overflow: auto;
                font-size: 14px;
                line-height: 1.5;
                font-size: 36px;
                letter-spacing: 10px;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <img src="https://skybyn.no/assets/images/logo_clean.png" alt="Skybyn logo" class="logo">
            <h1>New IP</h1>
            <p>We detected a login from a new IP address, was this you?<br><br> If this was you, enter the code below.</p>
            <div class="code-box">
                <code>123456</code>
            </div>
        </div>
    </body>
</html>