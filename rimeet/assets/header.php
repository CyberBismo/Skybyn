<?php require_once "assets/functions.php";

if (isset($_SESSION['driver']) || isset($_SESSION['passenger'])) {
    $_COOKIE['start'] = true;
}
?>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="theme-color" content="#000000">
        <link rel="icon" href="assets/images/rim.png" type="image/png">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="white-translucent">
        <title>Rimeet</title>
        <style>
            html, body {
                margin: 0;
                padding: 0;
                height: 100%;
                font-family: Arial, sans-serif;
            }
            .bg {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: black;
                background: url("assets/images/meet.jpg") no-repeat center center/cover;
                filter: blur(5px);
                z-index: -1;
            }
            .error {
                position: fixed;
                top: 50%;
                left: 5%;
                width: 90%;
                padding: 20px;
                border-radius: 10px;
                background: rgba(255, 0, 0, 0.7);
                color: white;
                font-size: 1.5em;
                transition: opacity 1s ease-in-out;
                box-sizing: border-box;
                z-index: 1000;
            }
            .error::before {
                content: "\f071";
                font-family: "Font Awesome 5 Free";
                font-weight: 900;
                margin-right: 10px;
            }

            .dashboard {
                display: flex;
                flex-direction: column;
                gap: 20px;
                align-items: center;
                width: 100%;
                padding-top: 20px;
                padding-bottom: 120px;
            }
            .card {
                width: 90%;
                max-width: 600px;
                max-height: 300px;
                margin: 0 auto;
                padding: 20px;
                border-radius: 10px;
                text-align: center;
                box-sizing: border-box;
                overflow: auto;
            }
            .card::-webkit-scrollbar {
                display: none;
            }
            .passengers .split {
                display: flex;
                justify-content: space-between;
            }
            .passengers img {
                width: 30px;
                height: 30px;
                border-radius: 10px;
                object-fit: cover;
            }
            .passengers p {
                height: 30px;
                margin: 0;
                padding: 0;
                line-height: 30px;
            }
            .passengers form {
                margin: 0;
                padding: 0;
            }
            .passengers button[type=submit] {
                color: black;
                background: rgba(0, 0, 0, 0);
            }
            .card .btns {
                display: flex;
                justify-content: space-between;
                gap: 5px;
            }
            .card .btns img {
                width: 50px;
                height: 50px;
                border-radius: 10px;
                object-fit: cover;
            }
            .card .btns h2 {
                margin: 0;
                padding: 0;
                line-height: 50px;
            }
            .card button,
            .card .btns button {
                width: 100%;
                padding: 10px;
                border: none;
                border-radius: 10px;
                background: black;
                color: white;
                font-size: 1em;
            }
            .red {
                color: white;
                background: linear-gradient(to bottom, rgba(255, 0, 0, 0.3), rgba(255, 0, 0, 0.7));
            }
            .blue {
                color: white;
                background: linear-gradient(to bottom, rgba(0, 0, 255, 0.3), rgba(0, 0, 255, 0.7));
            }
            .green {
                background: linear-gradient(to bottom, rgba(0, 255, 0, 0.3), rgba(0, 255, 0, 0.7));
            }
            .yellow {
                color: white;
                background: linear-gradient(to bottom, rgba(255, 255, 0, 0.3), rgba(255, 255, 0, 0.7));
            }
            .white {
                color: black;
                background: linear-gradient(to bottom, rgba(200, 200, 200, 0.3), rgba(200, 200, 200, 0.7));
            }
            .black {
                color: white;
                background: linear-gradient(to bottom, rgba(0, 0, 0, 0.3), rgba(0, 0, 0, 0.7));
            }
            
            .meet {
                width: 100%;
                max-width: 600px;
                margin: 0 auto;
                border-radius: 10px;
                color: white;
                overflow: auto;
            }
            .create_meet {
                padding-bottom: 50px;
            }
            .meet h1 {
                text-align: center;
            }
            .meet form {
                margin: 0 20px;
                padding: 20px;
                background: rgba(0, 0, 0, 0.7);
                border-radius: 10px;
                box-sizing: border-box;
            }
            .meet label {
                font-size: 1.5em;
            }
            .meet input {
                width: 100%;
                padding: 10px;
                border: none;
                border-radius: 10px;
                font-size: 1.5em;
                outline: none;
                box-sizing: border-box;
            }
            .meet button {
                width: 100%;
                padding: 10px;
                border: none;
                border-radius: 10px;
                background: black;
                color: white;
                font-size: 1.5em;
                cursor: pointer;
            }
            .meet button:disabled {
                background: grey;
            }
            .meet .split {
                display: flex;
                justify-content: space-between;
            }
            .meet .split input[type=checkbox] {
                width: 20px;
                height: 20px;
            }
            .meet .split input {
                width: calc(100% - 50px);
            }
            .meet .split button {
                width: 50px;
            }
            .meet .meet_info {
                display: flex;
                flex-direction: column;
                padding: 20px;
                border-radius: 10px;
            }
            .meet .meet_info .address {
                display: flex;
                justify-content: space-between;
            }
            .meet .meet_options {
                display: block;
                width: 100%;
                margin: 20px 0;
            }
            .meet .meet_options .meet_btns {
                display: flex;
                flex-direction: row;
                justify-content: space-between;
                gap: 5px;
            }
            .meet .meet_options .meet_btns button {
                padding: 10px;
                border: none;
                cursor: pointer;
            }
            .meet .meet_options .meet_btns button:hover {
                background: black;
                color: white;
            }
            .meet .meet_options form {
                display: flex;
                flex-direction: column;
                gap: 10px;
            }
            .meet .meet_options form input {
                padding: 10px;
                border: none;
                border-radius: 10px;
                font-size: 1.5em;
                outline: none;
            }
            .meet .meet_options form button {
                padding: 10px;
                border: none;
                border-radius: 10px;
                background: black;
                color: white;
                font-size: 1.5em;
                cursor: pointer;
            }
            .meet .meet_options form button:hover {
                background: black;
                color: white;
            }
            .meet .meet_options form hr {
                border: 1px solid #000;
            }
            .meet .meet_options form button[name="cancel_meet"] {
                background: red;
            }
            .meet .joiners {
                display: flex;
                flex-direction: column;
                padding: 0 20px;
                background-color: rgba(0, 0, 0, 0.7);
                border-radius: 10px;
            }
            .meet .joiners .joiner {
                display: flex;
                justify-content: space-between;
                margin: 10px 0;
                padding: 10px;
                border: 1px solid #000;
            }
            
            .car {
                width: 100%;
                max-width: 600px;
                margin: 0 auto;
                padding: 10px;
                border-radius: 10px;
                color: white;
                box-sizing: border-box;
            }
            .car .car-carousel {
                display: block;
                width: 100%;
                margin: 0 auto;
            }
            .car .car-carousel .vehicle {
                display: block;
                width: 100%;
                height: 100px;
                background: #f00;
                color: #fff;
                text-align: center;
                line-height: 100px;
            }
            .car .car_search {
                display: flex;
                justify-content: center;
                margin-bottom: 20px;
            }
            .car .car_search form {
                width: 100%;
                max-width: 400px;
            }
            .car .car_search label {
                display: block;
                margin-bottom: 10px;
                font-size: 1.5em;
                text-align: center;
            }
            .car .car_search input {
                width: 100%;
                max-width: 400px;
                padding: 10px;
                border: none;
                border-radius: 10px;
                font-size: 1em;
                text-align: center;
                outline: none;
            }
            .car .car_search input::placeholder {
                color: grey;
                text-transform: none;
            }
            .car h1 {
                text-align: center;
            }
            .car p {
                text-align: center;
                padding: 20px;
                border-radius: 20px;
            }
            .car .driver {
                width: 100%;
                max-width: 600px;
                margin: 0 auto;
                padding: 10px;
                border-radius: 10px;
                background: rgba(0, 0, 0, 0.3);
                color: white;
                box-sizing: border-box;
                overflow: hidden;
            }
            .car .driver .split {
                display: flex;
            }
            .car .driver .split img {
                width: 50px;
                height: 50px;
                border-radius: 20px;
                object-fit: cover;
            }
            .car .driver .split b {
                width: calc(100% - 50px);
                line-height: 50px;
                text-align: center;
            }
            .profile {
                width: 100%;
                max-width: 600px;
                margin: 0 auto;
                font-size: 1em;
                color: white;
            }
            .profile form {
                padding: 20px;
                background: rgba(0, 0, 0, 0.3);
                border-radius: 10px;
                box-sizing: border-box;
            }
            .profile h1 {
                font-size: 2em;
            }
            .profile b {
                padding: 0 5px;
                font-size: 1.5em;
            }
            .profile .profile_car {
                display: flex;
                justify-content: space-between;
            }
            .profile ul {
                list-style-type: none;
                padding: 0;
            }
            .profile ul li {
                padding: 5px;
            }
            .profile .add_vehicle {
                margin-top: 5px;
                padding: 0px;
                background: rgba(0, 0, 0, 0);
            }
            .profile .add_vehicle input {
                padding: 0px;
                color: white;
                background: rgba(0, 0, 0, 0);
                border: none;
                font-size: 1em;
                outline: none;
            }
            .profile .add_vehicle button {
                display: none;
            }
            .profile .buttons {
                display: flex;
                justify-content: space-between;
                margin-left: -10px;
                margin-right: -10px;
                margin-bottom: -10px;
            }
            .profile .buttons button {
                border: none;
                color: white;
                padding: 15px;
                text-align: center;
                text-decoration: none;
                display: inline-block;
                font-size: 16px;
                margin: 4px 2px;
                cursor: pointer;
            }
            .profile .buttons .meet_btn {
                background-color: rgba(0, 100, 255, 0.7);
                border-radius: 20px 20px 20px 5px;
            }
            .profile .buttons .profile_btn {
                color: black;
                background-color: rgba(255, 255, 255, 0.7);
                border-radius: 20px 20px 5px 20px;
            }
            .profile input {
                padding: 10px;
                border: none;
                border-radius: 10px;
                font-size: 1em;
                outline: none;
            }
            .profile input[type=checkbox] {
                width: 20px;
                height: 20px;
            }
            .car .drivers {
                margin-bottom: 20px;
            }
            .car .drivers .profile {
                padding: 20px;
                border-radius: 10px;
                background: rgba(0, 0, 0, 0.5);
                color: white;
                box-sizing: border-box;
            }
            .car .drivers .profile .details {
                display: flex;
                flex-direction: row;
                justify-content: space-between;
            }
            .car .drivers .profile .details img {
                width: 100px;
                height: 100px;
                border-radius: 20px;
                object-fit: cover;
            }
            .car .drivers .profile .details .contact p {
                text-align: left;
            }
            .car .drivers .profile form {
                width: 100%;
                margin-top: 10px;
            }
            .car .drivers .profile form button {
                width: 100%;
                padding: 10px;
                color: white;
                background: black;
                border: none;
                border-radius: 10px;
                font-size: 1em;
                cursor: pointer;
            }
            .car .drivers .profile .driver_cars {
                height: 50px;
                overflow: hidden;
            }
            .car .drivers .profile .driver_cars h3 {
                text-align: center;
                cursor: pointer;
            }
            .driver_cars ul li {
                display: flex;
                justify-content: space-between;
                line-height: 30px;
                font-size: .7em;
            }
            .driver_cars ul li img {
                width: 30px;
                height: 30px;
                border-radius: 5px;
                object-fit: cover;
            }
            .car .cars .vehicle {
                padding: 20px;
                border-radius: 10px;
                background: rgba(0, 0, 0, 0.5);
                color: white;
                overflow: hidden;
            }
            .car .cars .vehicle .split {
                display: flex;
                justify-content: space-around;
                vertical-align: top;
            }
            .car .cars .vehicle .vehicle_photo {
                width: 30%;
                text-align: center;
            }
            .car .cars .vehicle .vehicle_photo img {
                width: 100px;
                height: 100px;
                border-radius: 20px;
                object-fit: cover;
            }
            .car .cars .vehicle .vehicle_info {
                width: 70%;
            }
            .car .cars .vehicle .vehicle_info p {
                text-align: left;
            }
            .car .cars .vehicle .btns {
                display: flex;
                justify-content: space-between;
            }
            .car .cars .vehicle button {
                padding: 10px;
                border: none;
                border-radius: 10px;
                font-size: 1em;
                cursor: pointer;
            }
            .car .cars .vehicle button.remove {
                color: white;
                background: red;
            }
            .car .cars .vehicle button.stolen {
                color: white;
                background: orange;
            }
            .car .cars .vehicle button.found {
                color: white;
                background: green;
            }
            .car .cars .vehicle.stolen {
                background: red;
            }
            .car .car_btns {
                width: 100%;
                display: flex;
                justify-content: center;
                gap: 10px;
                align-items: center;
            }
            .car .car_btns button {
                padding: 20px;
                border: none;
                border-radius: 10px;
                font-size: 1.5em;
            }
            .car .car_btns button.login_btn {
                color: white;
                font-size: 1.5em;
                background: linear-gradient(to bottom, rgba(0, 200, 255, 1), rgba(0, 0, 139, 1));
            }
            .car .car_btns button.signup_btn {
                color: black;
                font-size: 1.5em;
                font-weight: bold;
                background: linear-gradient(to bottom, rgba(255, 255, 0, 1), rgba(255, 165, 0, 1));
            }
            .car .car_btns button:hover {
                cursor: pointer;
            }
            .car .car_btns button:active {
                filter: brightness(0.8);
            }

            .login {
                display: flex;
                flex-direction: column;
                gap: 10px;
                width: 100%;
                max-width: 400px;
                margin: 0 auto;
                padding: 20px;
                border-radius: 10px;
                background: black;
                color: white;
                box-sizing: border-box;
            }
            .login a {
                color: white;
                text-decoration: none;
            }
            .login input {
                padding: 10px;
                border: none;
                border-radius: 10px;
                font-size: 1.5em;
                text-align: center;
                outline: none;
            }
            .login input:focus {
                color: black;
            }
            .login input::placeholder {
                color: grey;
            }
            .login button {
                padding: 20px;
                border: none;
                border-radius: 10px;
                font-size: 1.5em;
                background: linear-gradient(to bottom, rgba(0, 200, 255, 1), rgba(0, 0, 139, 1));
                color: white;
            }
            .login button.signup_btn {
                padding: 10px;
                background: linear-gradient(to bottom, rgba(255, 255, 0, 1), rgba(255, 165, 0, 1));
                color: black;
            }
            .login button:hover {
                cursor: pointer;
            }
            .login button:active {
                filter: brightness(0.8);
            }

            .register {
                display: flex;
                flex-direction: column;
                gap: 10px;
                width: 100%;
                max-width: 400px;
                margin: 0 auto;
                padding: 20px;
                border-radius: 10px;
                background: black;
                color: white;
                box-sizing: border-box;
            }
            .register input {
                padding: 10px;
                border: none;
                border-radius: 10px;
                font-size: 1.5em;
                text-align: center;
                outline: none;
            }
            .register input:focus {
                color: black;
            }
            .register input::placeholder {
                color: grey;
            }
            .register button {
                padding: 20px;
                border: none;
                border-radius: 10px;
                font-size: 1.5em;
                background: linear-gradient(to bottom, rgba(255, 255, 0, 1), rgba(255, 165, 0, 1));
                color: black;
            }
            .register button.login_btn {
                padding: 10px;
                background: linear-gradient(to bottom, rgba(0, 200, 255, 1), rgba(0, 0, 139, 1));
                color: white;
            }
            .register button:hover {
                cursor: pointer;
            }
            .register button:active {
                filter: brightness(0.8);
            }
            
            .videos {
                grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
                width: 90%;
                max-width: 600px;
                margin: 0 auto;
                padding-bottom: 120px;
                color: white;
                box-sizing: border-box;
            }
            .videos h1 {
                width: 100%;
                text-align: center;
                margin: 0;
                padding: 20px 0px;
                font-size: 20px;
                box-sizing: border-box;
            }
            .video_gallery {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
                gap: 20px;
            }
            .video_gallery .video {
                width: 90%;
                max-width: 600px;
                margin: 10px 5%;
                border-radius: 10px;
                color: white;
                text-align: center;
                box-sizing: border-box;
            }
            
            .nav {
                display: flex;
                justify-content: space-evenly;
                position: fixed;
                bottom: 0;
                width: 100%;
                height: 40px;
                margin-top: 20px;
                padding: 10px 0;
                background: black;
                border-top: 1px solid grey;
                z-index: 100;
            }
            .nav-item {
                width: 80px;
                margin: 0;
                padding: 10px;
                text-align: center;
                background: black;
            }
            .nav-link {
                font-size: 1.5em;
                color: white;
                text-decoration: none;
            }
            .nav-link:hover {
                text-decoration: underline;
            }
            .nav-center {
                width: 80px;
                margin-top: -50px;
                font-size: 2em;
                border-top: 2px solid grey;
                border-top-left-radius: 50px;
                border-top-right-radius: 50px;
                display: flex;
                justify-content: center;
                align-items: center;
                background: black;
            }
            
            .start {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: black;
                color: white;
                z-index: 1;
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                z-index: 1000;
            }
            .start_logo {
                position: absolute;
                top: 30px;
                left: 50%;
                transform: translateX(-50%);
                width: 70%;
                max-width: 300px;
                transition: opacity 1s ease-in-out;
            }
            #start-btn {
                position: absolute;
                top: 50%;
                left: 50%;
                width: 70%;
                max-width: 300px;
                transform: translateX(-50%) translateY(-50%);
                border: none;
                cursor: pointer;
                transition: opacity 1s ease-in-out;
                -webkit-tap-highlight-color: transparent; /* For Safari and Chrome */
                -webkit-touch-callout: none; /* For Safari */
                user-select: none; /* For most modern browsers */
            }
        </style>
        <script src="https://kit.fontawesome.com/bafdb5f0e9.js" crossorigin="anonymous"></script>
    </head>
    <body>
        <div class="bg"></div>

        <?php if (isset($_COOKIE['error'])) {?>
        <div class="error">
            <?=$_COOKIE['error']?>
        </div>
        <script>
            setTimeout(() => {
                document.querySelector('.error').style.opacity = '0';
                setTimeout(() => {
                    document.querySelector('.error').style.display = 'none';
                }, 1000);
            }, 5000);
        </script>
        <?php }?>
        <?php if (isset($_COOKIE['success'])) {?>
        <div class="error" style="background: rgba(0, 255, 0, 0.7)">
            <?=$_COOKIE['success']?> <i class="fas fa-check"></i>
        </div>
        <script>
            setTimeout(() => {
                document.querySelector('.error').style.opacity = '0';
                setTimeout(() => {
                    document.querySelector('.error').style.display = 'none';
                }, 1000);
            }, 5000);
        </script>
        <?php }?>