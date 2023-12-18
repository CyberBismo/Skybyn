        <style>
            :root {
                --black: 0,0,0;
                --black-light: 25,25,25;
                --black-dark: 0,0,5;

                --white: 255,255,255;
                --white-light: 240,240,240;
                --white-dark: 220,220,220;

                --lightblue: 183,231,236;
                --lightblue-light: 200,240,245;
                --lightblue-dark: 150,210,215;

                --blue: 0,123,233;
                --blue-light: 0,160,255;
                --blue-dark: 0,90,180;

                --greyblue: 42,106,133;
                --greyblue-light: 60,130,155;
                --greyblue-dark: 30,80,105;

                --dark: 72,71,84;
                --dark-light: 80,80,95;
                --dark-dark: 60,60,75;

                --darker: 23,23,28;
                --darker-light: 30,30,35;
                --darker-dark: 15,15,20;

                --red: 255,0,0;
                --red-light: 255,80,80;
                --red-dark: 180,0,0;

                --green: 0,255,0;
                --green-light: 80,255,80;
                --green-dark: 0,180,0;

                --yellow: 255,255,0;
                --yellow-light: 255,255,80;
                --yellow-dark: 180,180,0;

                --orange: 255,165,0;
                --orange-light: 255,200,80;
                --orange-dark: 180,120,0;

                --purple: 128,0,128;
                --purple-light: 150,0,150;
                --purple-dark: 100,0,100;

                --pink: 255,182,193;
                --pink-light: 255,200,210;
                --pink-dark: 220,150,160;

                --brown: 165,42,42;
                --brown-light: 180,80,80;
                --brown-dark: 120,30,30;

                --cyan: 0,255,255;
                --cyan-light: 80,255,255;
                --cyan-dark: 0,180,180;

                --magenta: 255,0,255;
                --magenta-light: 255,80,255;
                --magenta-dark: 180,0,180;

                --lime: 0,255,0;
                --lime-light: 80,255,80;
                --lime-dark: 0,180,0;

                --gold: 255,215,0;
                --gold-light: 255,230,80;
                --gold-dark: 180,150,0;

                --indigo: 75,0,130;
                --indigo-light: 90,0,150;
                --indigo-dark: 60,0,100;

                --turquoise: 64,224,208;
                --turquoise-light: 80,240,220;
                --turquoise-dark: 50,200,180;

                --lavender: 230,230,250;
                --lavender-light: 240,240,255;
                --lavender-dark: 210,210,240;

                --olive: 128,128,0;
                --olive-light: 150,150,0;
                --olive-dark: 100,100,0;

                --maroon: 128,0,0;
                --maroon-light: 150,0,0;
                --maroon-dark: 100,0,0;

                --teal: 0,128,128;
                --teal-light: 0,150,150;
                --teal-dark: 0,100,100;
            }

            html {
                font-family: Arial, Helvetica, sans-serif;
            }
            body {
                width: 100%;
                margin: 0 auto;
                background: linear-gradient(180deg, rgba(var(--dark),1) 0%, rgba(var(--darker),1) 80%, rgba(var(--black),1) 100%);
                background-attachment: fixed;
            }
            *::-webkit-scrollbar {
                display: none;
            }

            #install-button {
                display: block;
                position: fixed;
                top: 0;
                right: 0;
                margin: 10px;
                padding: 20px;
                color: white;
                background: rgba(0,0,0,.5);
                border: none;
                border-radius: 5px;
                z-index: 999;
            }

            .msg {
                position: fixed;
                bottom: 50px;
                left: 0;
                width: 60%;
                margin: 0 20%;
                padding: 20px;
                text-align: center;
                background: white;
                box-sizing: border-box;
                border-radius: 20px;
                z-index: 3;
            }

            .new_users {
                position: fixed;
                bottom: 10px;
                left: 10px;
                color: white;
                z-index: 3;
            }
            .new_user {
                display: flex;
                align-items: center;
                opacity: 0;
                margin: 2px 0;
                background: rgba(255,255,255,.1);
                border-radius: 10px 30px 30px 10px;
            }
            .new_user_left {
                padding: 5px 10px;
                font-size: 12px;
            }
            .new_user_right {
                padding: 10px;
                font-size: 40px;
            }

            #welcome-screen {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: linear-gradient(180deg, rgba(var(--dark),1) 0%, rgba(var(--darker),1) 100%);
                background-size: cover;
                background-position: right;
                transition: all 0.5s ease-in-out;
                opacity: 1;
                z-index: 10;
            }
            #welcome-screen.hide {
                opacity: 0;
            }
            #welcome-inner {
                opacity: 0;
            }
            #welcome-inner.show {
                opacity: 1;
            }
            #welcome-inner {
                position: fixed;
                text-align: center;
                top: 40%;
                left: 50%;
                transform: translate(-50%, -50%);
                font-size: 28px;
                color: white;
                z-index: 9999;
                transition: all 0.5s ease-in-out;
            }
            #welcome-inner img {
                height: 200px;
            }
            #welcome-inner h1,
            #welcome-inner h3 {
                margin: 0;
            }
            #welcome-click {
                text-align: center;
                position: absolute;
                width: 100%;
                margin: 0 auto;
                padding: 20px;
                bottom: 0;
                left: 0;
                color: white;
                box-sizing: border-box;
                z-index: 999;
            }
            
            /** First time message */
            .first-time {
                display: none;
                position: absolute;
                top: 200px;
                left: 5%;
                width: 90%;
                margin: 0 auto;
                padding: 20px;
                text-align: center;
                background: white;
                border-radius: 40px;
                box-sizing: border-box;
            }

            .clouds {
                position: absolute;
                bottom: 0;
                left: 0;
                width: 100%;
                height: 150px;
                background: url('https://skybyn.no/assets/images/clouds.png');
                background-position: top;
                background-size: cover;
                z-index: 10;
            }
            
            /** Form */
            .form {
                width: 100%;
                padding: 30px;
                box-sizing: border-box;
                overflow: hidden;
            }
            .form h3 {
                margin: 0;
                margin-bottom: 20px;
            }
            .form .links {
                display: flex;
                justify-content: space-between;
                margin-top: 20px;
            }
            .form .links button {
                padding: 5px 10px;
                border: none;
                border-radius: 10px;
            }
            .form .links button:hover {
                cursor: pointer;
            }
            .form .links span {
                display: block;
                cursor: pointer;
            }
            .form .links a {
                padding: 5px 10px;
                text-decoration: none;
                color: white;
            }
            .form i {
                position: relative;
                float: left;
                line-height: 40px;
                margin-left: 15px;
                margin-bottom: -40px;
                color: white;
            }
            .form i.fa-eye {
                float: right;
                margin: 0;
                margin-top: -45px;
                margin-right: 10px;
            }
            .form input,
            .form input[type=date] {
                width: 100%;
                height: 40px;
                margin-bottom: 5px;
                padding: 0 10px;
                padding-left: 50px;
                color: white;
                background: rgba(0,0,0,.1);
                border-radius: 10px;
                border: none;
                box-sizing: border-box;
                outline: none;
            }
            .form input[type=date]::-webkit-calendar-picker-indicator {
                filter: invert(1);
            }
            .form textarea {
                width: 100%;
                max-width: 100%;
                min-height: 40px;
                height: 40px;
                max-height: 200px;
                margin-bottom: 5px;
                padding: 10px;
                padding-left: 50px;
                color: white;
                background: rgba(0,0,0,.1);
                border-radius: 10px;
                border: none;
                box-sizing: border-box;
                outline: none;
            }
            /** Settings */
            .form input[name=post_visibility] {
                width: 20px;
                height: 40px;
                margin-left: 50px;
            }
            .form label[for=posts] {
                position: absolute;
                margin-top: -5px;
                padding: 20px;
            }
            /** --- */
            .form .terms {
                display: flex;
                margin: 20px 0;
            }
            .form .terms input {
                width: 20px;
                margin-right: 10px;
            }
            .form .terms label {
                line-height: 45px;
            }
            .form .terms label span {
                text-decoration: underline;
                cursor: pointer;
            }
            .form input[type=submit] {
                margin-bottom: 0;
                padding: 0;
                border-radius: 10px;
                color: white;
                background: rgba(255,255,255,.1);
                border: none;
                box-sizing: border-box;
                outline: none;
                cursor: pointer;
            }

            .popup {
                display: none;
                position: absolute;
                padding: 20px;
                color: black;
                background: white;
                box-sizing: border-box;
                z-index: 5;
            }
            <?php if (isMobile() == false) {?>
            .popup {
                top: 50%;
                left: 50%;
                width: 40%;
                height: 50%;
                margin-left: -20%;
                margin-top: -15%;
                border-radius: 20px;
            }
            <?php } else {?>
            .popup {
                top: 10%;
                left: 0;
                width: 100%;
                height: 90%;
                margin: 0 auto;
            }
            <?php }?>
            .popup .popup-close {
                float: right;
                width: 50px;
                height: 50px;
                text-align: center;
                cursor: pointer;
            }
            .popup .popup-close:hover i {
                transform: scale(2);
            }
            .popup .popup-close i {
                color: black;
                transition: transform .5s;
            }
            .popup h2 {
                margin: 0;
                padding: 10px 0;
            }
            .popup .terms {
                width: 100%;
                height: calc(100% - 50px);
                overflow: auto;
            }

            /** Page Container */
            <?php if (isMobile() == false) {?>
            .page-container {
                min-width: 300px;
                width: 50%;
                margin: 0 auto;
                margin-top: 105px;
                padding: 10px 0;
            }
            <?php } else {?>
            .page-container {
                min-width: 300px;
                width: 100%;
                max-width: 800px;
                margin: 0 auto;
                padding: 75px 0;
            }
            <?php }?>
            .page-head {
                text-align: center;
                color: white;
                font-size: 24px;
            }

            .split {
                display: flex;
                justify-content: space-between;
            }
            .split-box {
                width: calc(50% - 3px);
            }
            

            /** Panels */
            .left-panel,
            .right-panel {
                position: fixed;
                top: 75px;
                color: white;
                transition: transform .5s;
            }
            <?php if (isMobile() == false) {?>
            .left-panel,
            .right-panel {
                width: 25%;
                max-width: 300px;
                height: 100%;
                padding: 0 10px;
                box-sizing: border-box;
            }
            <?php } else {?>
            .left-panel,
            .right-panel {
                width: 90%;
                height: calc(100% - 75px);
                background: rgb(var(--dark));
            }
            <?php }?>
            
            .left-panel {
                left: 0;
                <?php if (isMobile() == true) {?>
                transform: translateX(-100%);
                <?php }?>
            }
            .left-panel h3 {
                margin-left: 10px;
            }
            .left-panel button.btn {
                padding: 10px 15px;
                color: white;
                background: var(--greyblue);
                border-radius: 5px;
                border: none;
                cursor: pointer;
                transition: transform .3s;
            }
            .left-panel button.btn:hover {
                transform: scale(1.1);
            }
            
            .right-panel {
                right: 0;
                <?php if (isMobile() == true) {?>
                transform: translateX(100%);
                <?php }?>
            }
            .right-panel h3 {
                text-align: center;
            }

            /** Bottom Navigation */
            .bottom-nav {
                position: fixed;
                display: flex;
                align-items: center;
                justify-content: space-between;
                bottom: 0;
                left: 50%;
                width: auto;
                height: 50px;
                transform: translate(-50%, -50%);
                font-size: 24px;
                color: white;
                background: rgb(var(--darker));
                border: 1px solid rgba(var(--dark),5);
                border-radius: 100px;
                box-sizing: border-box;
                z-index: 9;
            }
            .bnav-btn {
                padding: 20px;
            }

            .bottom-navigation {
                position: fixed;
                bottom: 0;
                width: 100%;
            }
            .bottom-navigation .nav-holder {
                display: flex;
                justify-content: space-evenly;
                width: 100%;
                height: 50px;
                background: white;
                box-shadow: 1px 0 rgba(0,215,255,1);
            }
            .bottom-navigation  .nav-button {
                width: 20%;
                height: 100%;
                line-height: 50px;
                text-align: center;
            }
            .bottom-navigation  .nav-button.center {
                width: 70px;
                height: 70px;
                line-height: 70px;
                margin-top: -40px;
                font-size: 36px;
                border-radius: 50px;
                color: white;
                background: var(--blue);
                box-shadow: 0 0 2px rgba(0,215,255,1);
            }

            .loading {
                width: 100%;
            }

            /** Start page */
            <?php if (!isset($_SESSION['user'])) {
                if (isMobile() == false) {?>
            .start {
                position: relative;
                display: flex;
                justify-content: center;
                top: 200px;
                min-width: 300px;
                max-width: 1100px;
                margin: 0 auto;
            }
            .welcome_information {
                width: 70%;
                margin: 0 100px;
                text-align: center;
                color: white;
                box-sizing: border-box;
                overflow: hidden;
            }
            .info_text {
                padding: 50px 20px;
                background: rgba(var(--darker),.5);
                backdrop-filter: blur(5px);
                border-radius: 20px;
                box-sizing: border-box;
                overflow: hidden;
            }
            .info_text p {
                display: none;
            }
            .info_right {
                display: none;
            }
            <?php } else {?>
            .start {
                display: block;
                min-width: 300px;
                max-width: 800px;
                margin: 0 auto;
            }
            .welcome_information {
                width: calc(100% - 20px);
                margin: 0 auto;
                margin-top: 75px;
                text-align: center;
                color: white;
                background: black;
                border-radius: 20px;
                overflow: hidden;
            }
            .info_text {
                width: 100%;
                padding: 20px;
                background: rgba(0,0,0,.1);
                backdrop-filter: blur(5px);
                box-sizing: border-box;
            }
            <?php }}?>
        
            #welcome_info {
                transition: all 0.3s;
            }
            
            .info_text h2 {
                margin: 0;
                padding: 0;
            }
            .info_text p {
                font-size: 8px;
                color: rgb(var(--dark));
            }
            .info_right {
                box-sizing: border-box;
            }
            #info_table {
                margin-top: 20px;
                transition: all 0.3s ease-in-out;
            }
            #info_table tr {
                opacity: 0;
                transition: all 0.3s ease-in-out;
            }

            <?php if (isMobile() == false) {?>
            .center_form {
                min-width: 30%;
                max-width: 500px;
                margin: 0 auto;
                margin-right: 100px;
                color: white;
                border-radius: 20px;
                box-sizing: border-box;
            }
            <?php } else {?>
            .center_form {
                width: calc(100% - 20px);
                margin: 0 auto;
                margin-top: 5px;
                color: white;
                box-sizing: border-box;
            }
            <?php }?>
            .center_form .form {
                width: 100%;
                padding: 20px;
                background: rgba(0,0,0,.2);
                border-radius: 20px;
                box-sizing: border-box;
            }
            .center_form .form .login,
            .center_form .form .register {
                width: 100%;
                padding: 0;
            }
            .center_form .form .login input,
            .center_form .form .register input {
                display: block;
                width: 100%;
                padding: 0 10px;
                padding-left: 40px;
                background: rgba(0,0,0,.1);
                box-sizing: border-box;
            }
            .center_form .form .register input[type="number"] {
                -moz-appearance: textfield; /* Firefox */
            }

            /* Chrome, Edge, and Safari */
            .center_form .form .register input[type="number"]::-webkit-inner-spin-button,
            .center_form .form .register input[type="number"]::-webkit-outer-spin-button {
                -webkit-appearance: none;
                margin: 0;
            }
            .center_form .form .login input[type=checkbox],
            .center_form .form .register input[type=checkbox] {
                float: left;
                width: 20px;
                padding: 0 10px;
                box-sizing: border-box;
            }
            .center_form .form .login label,
            .center_form .form .register label {
                float: left;
                line-height: 50px;
                cursor: pointer;
            }
            .center_form .form .login input[type=submit] {
                float: right;
                width: auto;
                margin-left: auto;
                padding: 0 20px;
                font-size: 18px;
                background: rgba(255,255,255,.2);
                border: 1px solid rgba(255,255,255,.5);
                overflow: hidden;
            }
            .center_form .form .register input[type=submit] {
                float: right;
                width: 100%;
                padding: 0 20px;
                font-size: 18px;
                background: rgba(255,255,255,.2);
                border: 1px solid rgba(255,255,255,.5);
                overflow: hidden;
            }
            .center_form .form .register input#send_again {
                width: 100%;
                height: 30px;
                margin-bottom: 5px;
                font-size: 16px;
                border: none;
            }
            .center_form .form .register input#step_back {
                width: 80%;
                height: 30px;
                margin: 0 10%;
                font-size: 14px;
                background: rgba(255,255,255,.1);
                border: none;
            }
            .center_form .links {
                width: 100%;
            }
            .center_form .links span:hover {
                color: rgba(200,200,200,1);
            }

            .log-button,
            .reg-button {
                width: auto;
                margin-top: 10px;
                background: rgba(0,0,0,.2);
                box-sizing: border-box;
                border-radius: 20px;
                cursor: pointer;
                overflow: hidden;
            }
            .log-button span {
                height: 100%;
                padding: 15px 20px;
                border-radius: 20px;
            }
            .log-button span:hover {
                background: rgba(255,255,255,.1);
                cursor: pointer;
            }
            .reg-button {
                text-align: center;
                padding: 15px 0;
            }
            .reg-button:hover {
                background: rgba(100,100,100,.1);
            }

            <?php if (isMobile() == false) {
                if (skybyn('login-form') == "login") {?>
            #register-form {
                overflow: hidden;
            }
            <?php } else {?>
            #login-form {
                display: none;
                overflow: hidden;
            }
            <?php }?>
            .register-now {
                position: absolute;
                width: 200px;
                height: 40px;
                left: 50%;
                margin-left: -100px;
                color: white;
                background: linear-gradient(0deg, rgba(var(--dark),1) 0%, rgba(var(--darker),1) 100%);
                border-radius: 10px;
                border: none;
                cursor: pointer;
            }
            <?php } else {?>
            .register-now {
                position: absolute;
                bottom: 20px;
                width: 50%;
                height: 40px;
                margin: 0 25%;
                color: white;
                border-radius: 10px;
                border: none;
            }
            .register-form {
                height: auto;
                transition: height .5s;
            }
            #register-form {
                overflow: hidden;
            }
            .register {
                width: 100%;
                padding: 20px;
                box-sizing: border-box;
            }
            <?php }?>
            .register .form {
                padding: 0;
            }
            .reg-packs {
                width: 800px;
                margin-top: 100px;
                margin-left: 50%;
                transform: translateX(-50%);
                text-align: center;
                color: white;
            }
            .reg-packs button {
                width: auto;
                height: 50px;
                margin: 3px;
                margin-top: 20px;
                padding: 0 50px;
                color: white;
                background: rgba(255,255,255,.1);
                backdrop-filter: blur(5px);
                border: none;
                border-radius: 10px;
                box-sizing: border-box;
                transition: background .3s, transform .3s;
                cursor: pointer;
            }
            .reg-packs h3 {
                text-align: center;
            }
            .reg-packs-box {
                display: flex;
                flex-wrap: wrap;
                justify-content: center;
                gap: 5px;
            }
            .reg-pack {
                width: 300px;
                padding: 5px;
                text-align: left;
                background: rgba(0,0,0,.1);
                border-radius: 30px;
                backdrop-filter: blur(5px);
                transition: width .5s;
            }
            .reg-pack-box {
                height: calc(100% - 20px);
                margin: 10px;
                padding: 5px;
                border: 1px solid rgba(255,255,255,.2);
                border-radius: 20px;
                box-sizing: border-box;
                transition: background .2s;
            }
            .reg-pack-box:hover {
                background: rgba(255,255,255,.1);
                cursor: pointer;
            }
            .reg-pack-box button {
                width: 50%;
                min-width: 100px;
                height: 40px;
                color: white;
                background: rgba(255,255,255,.1);
                border: none;
                border-radius: 10px;
            }
            .reg-pack-box button:hover {
                background: rgba(255,255,255,.2);
                cursor: pointer;
            }
            .reg-pack h2 {
                height: 50px;
                line-height: 50px;
                margin: 0;
                text-align: center;
            }
            .reg-pack ul {
                height: calc(100% - 100px);
                padding: 0;
                list-style: none;
            }
            .reg-pack li {
                padding: 10px;
            }
            .reg-pack li:before {
                content: "*";
                padding-right: 10px;
            }
            .reg-pack span {
                display: block;
                text-align: center;
            }
            .reg-pack p {
                text-align: center;
            }

            .reg-pack-custom {
                width: 100%;
                height: auto;
                text-align: left;
                box-sizing: border-box;
            }
            .reg-pack-box-custom {
                display: flex;
            }
            .reg-pack-box:hover {
                background: none;
            }
            .rpbcb {
                width: 50%;
                cursor: default;
            }
            .rpbcb p {
                height: 60px;
                padding: 10px;
                text-align: center;
                box-sizing: border-box;
            }
            .rpbcb b {
                line-height: 30px;
                font-size: 18px;
            }
            .rpbcb table {
                width: 100%;
            }
            .rpbcb table tr {
                width: 100%;
                text-align: left;
            }
            .rpbcb table tr td {
                padding: 10px;
                box-sizing: border-box;
            }
            .rpbcb table tr:hover,
            .rpbcb table tr td:hover tr,
            .rpbcb table tr td input:hover tr,
            .rpbcb table tr td span:hover tr {
                background: rgba(255,255,255,.1);
                cursor: pointer;
            }
            
            /** Header */
            .header {
                display: flex;
                position: fixed;
                top: 0;
                width: 100%;
                height: 75px;
                z-index: 10;
            }
            <?php if (isMobile() == true) {?>
            .header {
                backdrop-filter: blur(5px);
            }
            <?php }?>

            <?php if (isMobile() == false) {
                if (isset($_SESSION['user'])) {?>
            .header .top-left {
                display: flex;
                width: 33.33%;
            }
            <?php } else {?>
            .header .top-left {
                display: flex;
                width: 100%;
            }
            <?php }} else {?>
            .header .top-left {
                display: flex;
                width: 100%;
                justify-content: center;
                align-items: center;
            }
            <?php }?>

            .header .top-left .logo {
                display: flex;
                justify-content: center;
                align-items: center;
            }
            .header .top-left .logo img {
                width: auto;
                max-height: 75px;
                padding: 0 20px;
                box-sizing: border-box;
            }
            .header .top-left .logo-name {
                width: auto;
                font-size: 14px;
            }
            .header .top-left .logo-name h1 {
                margin: 10px 0 5px 0;
                color: white;
            }
            .header .top-left .logo-name p {
                margin: 0;
                color: white;
            }
            <?php if (isMobile() == false) {?>
            .header .new_post_button {
                width: 33.33%;
                height: 45px;
                line-height: 45px;
                margin: 15px 0;
                padding: 0 20px;
                color: white;
                text-align: center;
                backdrop-filter: blur(5px);
                border: 1px solid rgba(255,255,255,.5);
                border-radius: 50px;
                box-sizing: border-box;
                cursor: pointer;
            }
            .new_post {
                position: fixed;
                top: 75px;
                left: 50%;
                width: 40%;
                margin-left: -20%;
                background: rgba(var(--dark),.8);
                backdrop-filter: blur(5px);
                border-radius: 20px;
                box-sizing: border-box;
                box-shadow: 0px 5px 10px 0px rgba(0,0,0,.5);
                overflow: hidden;
                z-index: 3;
            }
            .new_post .create_post {
                width: 100%;
                margin: 0 auto;
                padding: 20px;
                color: black;
                box-sizing: border-box;
            }
            .new_post .create_post textarea {
                width: 100%;
                min-width: 100%;
                max-width: 100%;
                min-height: 100px;
                max-height: 400px;
                margin: 0 auto;
                padding: 10px;
                padding-right: 40px;
                color: white;
                background: none;
                border: none;
                outline: none;
                box-sizing: border-box;
            }
            .new_post .create_post textarea::placeholder {
                color: white;
            }
            .new_post .create_post textarea::-webkit-scrollbar {
                display: none;
            }
            .new_post img {
                float: left;
                width: 50px;
                margin: -10px 0 0 -10px;
                border-radius: 20px;
            }
            .new_post .create_post_actions_top {
                display: flex;
                justify-content: end;
            }
            .new_post .create_post_actions_top span {
                margin: 0 10px;
                padding: 10px;
                color: white;
            }
            .new_post .create_post_actions_top span select {
                color: white;
                background: none;
                outline: none;
                border: none;
            }
            .new_post .create_post_actions_top span select option {
                color: black;
            }
            .new_post .create_post_actions_top span.close {
                width: 50px;
                margin-right: -10px;
                text-align: center;
                border-left: 1px solid white;
                cursor: pointer;
            }
            .new_post .create_post_actions_bottom {
                display: flex;
                justify-content: space-between;
                margin-top: 20px;
                color: white;
            }
            .new_post .create_post_actions_bottom .share {
                width: 40px;
                line-height: 40px;
                margin-top: -10px;
                padding: 0;
                text-align: center;
                border-radius: 40px;
                justify-self: right;
                cursor: pointer;
            }
            .new_post .create_post_actions_bottom .share:hover {
                background: rgba(255,255,255,.3);
            }
            .new_post .create_post_actions_bottom label,
            .new_post .create_post_actions_bottom span {
                cursor: pointer;
            }
            .new_post .create_post_actions_bottom i {
                padding: 0 10px;
            }
            .new_post .new_post_files {
                display: flex;
                justify-content: left;
                width: 100%;
                padding: 0 10px;
                padding-top: 10px;
            }
            .new_post .new_post_files img {
                margin: 3px 0;
                border-radius: 10px;
            }

            .header .search_result {
                position: absolute;
                width: 33.33%;
                height: auto;
                left: 33.33%;
                right: 33.33%;
                margin-top: 100px;
                padding: 10px;
                background: rgba(255,255,255,.9);
                border-radius: 20px;
                box-sizing: border-box;
            }
            .search_res_user {
                display: flex;
                width: 100%;
                height: 50px;
                line-height: 50px;
                border-radius: 10px;
                box-sizing: border-box;
            }
            .search_res_user:hover {
                background: rgba(255,255,255,.2);
            }
            .search_res_user .search_res_user_avatar {
                width: 50px;
                height: 50px;
                margin-right: 10px;
                border-radius: 10px;
                box-sizing: border-box;
                overflow: hidden;
            }
            .search_res_user .search_res_user_avatar img {
                width: auto;
                height: 100%;
            }
            <?php } else {?>
            .new_post {
                position: fixed;
                top: 75px;
                left: 0;
                width: 100%;
                background: rgba(0,0,0,.8);
                backdrop-filter: blur(5px);
                border-radius: 20px;
                box-sizing: border-box;
                box-shadow: 0px 5px 10px 0px rgba(0,0,0,.5);
                overflow: hidden;
                z-index: 3;
            }
            .new_post .create_post {
                width: 100%;
                margin: 0 auto;
                padding: 20px;
                color: black;
                box-sizing: border-box;
            }
            .new_post .create_post textarea {
                width: 100%;
                min-width: 100%;
                max-width: 100%;
                min-height: 100px;
                max-height: 400px;
                margin: 0 auto;
                padding: 10px;
                padding-right: 40px;
                color: white;
                background: none;
                border: none;
                outline: none;
                box-sizing: border-box;
            }
            .new_post .create_post textarea::placeholder {
                color: white;
            }
            .new_post .create_post textarea::-webkit-scrollbar {
                display: none;
            }
            .new_post img {
                float: left;
                width: 50px;
                margin: -10px 0 0 -10px;
                border-radius: 20px;
            }
            .new_post .create_post_actions_top {
                display: flex;
                justify-content: end;
            }
            .new_post .create_post_actions_top span {
                padding: 0 10px;
                color: white;
            }
            .new_post .create_post_actions_top span select {
                color: white;
                background: none;
                outline: none;
                border: none;
            }
            .new_post .create_post_actions_top span select option {
                color: black;
            }
            .new_post .create_post_actions_top span.close {
                width: 40px;
                margin-right: -10px;
                text-align: center;
                border-left: 1px solid white;
                cursor: pointer;
            }
            .new_post .create_post_actions_bottom {
                display: flex;
                justify-content: space-between;
                margin-top: 20px;
                color: white;
            }
            .new_post .create_post_actions_bottom .share {
                width: 40px;
                line-height: 40px;
                margin-top: -10px;
                padding: 0;
                text-align: center;
                border-radius: 40px;
                justify-self: right;
                cursor: pointer;
            }
            .new_post .create_post_actions_bottom .share:hover {
                background: rgba(255,255,255,.3);
            }
            .new_post .create_post_actions_bottom label,
            .new_post .create_post_actions_bottom span {
                cursor: pointer;
            }
            .new_post .create_post_actions_bottom i {
                padding: 0 10px;
            }
            .new_post .new_post_files {
                display: flex;
                width: 100%;
                padding: 0 10px;
                padding-top: 10px;
            }
            .new_post .new_post_files img {
                border-radius: 0;
            }
            .header .create_post {
                position: relative;
                width: 100%;
                padding: 20px;
                background: rgba(0,0,0,.8);
                box-sizing: border-box;
            }
            .header .create_post textarea {
                width: 100%;
                height: 40%;
                padding: 10px;
                padding-right: 40px;
                color: white;
                background: none;
                border: none;
                outline: none;
                resize: none;
                box-sizing: border-box;
            }
            .header .create_post textarea::placeholder {
                color: white;
            }
            .header .create_post i {
                width: 40px;
                line-height: 40px;
                margin-left: calc(100% - 40px);
                padding: 0 10px;
                color: white;
            }
            <?php }?>
            .header .top {
                display: flex;
                justify-content: space-evenly;
                color: white;
            }
            <?php if (isMobile() == false) {?>
            .header .top {
                width: 300px;
                max-width: 33%;
                margin-left: auto;
            }
            <?php } else {?>
            .header .top {
                width: 100%;
            }
            <?php }?>
            <?php if (isMobile() == false) {?>
            .header .top .top-nav {
                width: 75px;
                height: 75px;
                text-align: center;
            }
            <?php } else {?>
            .header .top .top-nav {
                width: 30%;
                height: 75px;
                text-align: center;
                padding: 0 20px;
            }
            <?php }?>
            .header .top .top-nav ul {
                list-style: none;
                margin: 17.5px 0;
                padding: 0;
            }
            .header .top .top-nav li {
                display: inline-block;
                width: 75px;
                text-align: center;
                padding: 10px 11px;
                box-sizing: border-box;
                cursor: pointer;
            }
            .notification_alert {
                position: absolute;
                width: 0px;
                height: 0px;
                margin-left: 30px;
                margin-top: -7px;
                font-size: 10px;
                color: red;
            }
            #noti_alert {
                opacity: 0;
            }
            .notifications {
                position: fixed;
                top: 75px;
                right: 250px;
                width: 30%;
                max-width: 300px;
                max-height: calc(100% - 300px);
                color: white;
                background: rgba(0,0,0,.3);
                border-radius: 10px 0 10px 10px;
                backdrop-filter: blur(5px);
                box-shadow: 10px 10px 23px -5px rgba(0,0,0,0.6);
                z-index: 11;
            }
            .notifications i {
                cursor: pointer;
            }
            .notifications-head {
                display: flex;
                justify-content: space-between;
            }
            .notifications-head div {
                width: 40px;
                padding: 10px;
                text-align: center;
            }
            .notifications-head h4 {
                text-align: center;
                margin: 0;
                padding: 10px;
            }
            .noti {
                display: flex;
                justify-content: space-between;
                margin: 5px;
                padding: 5px 0;
                background: rgba(0,0,0,.05);
                border-radius: 5px;
                transition: background .5s;
            }
            .noti:hover {
                background: rgba(0,0,0,.1);
            }
            .noti-status {
                width: 20px;
                padding: 0 10px;
            }
            .noti-content {
                width: 100%;
                height: 40px;
                overflow: auto;
                cursor: default;
            }
            .noti-title {
                padding-bottom: 5px;
                font-weight: bold;
            }
            .noti-actions {
                width: 50px;
                text-align: center;
            }
            .noti-action {
                padding: 5px;
                font-size: 12px;
            }

            .notification-window {
                position: fixed;
                top: 15%;
                left: 50%;
                width: 600px;
                max-height: 80%;
                margin-left: -300px;
                color: white;
                background: rgba(var(--lightblue),.3);
                backdrop-filter: blur(5px);
                box-shadow: 10px 10px 23px -5px rgba(0,0,0,0.6);
                border-radius: 20px;
                box-sizing: border-box;
                overflow: auto;
                z-index: 3;
            }
            .noti-win-head {
                display: flex;
                justify-content: space-between;
                height: 50px;
            }
            .noti-win-head-close {
                width: 50px;
                height: 50px;
                line-height: 50px;
                text-align: center;
                background: rgba(0,0,0,.1);
                border-radius: 0 0 0 20px;
                box-sizing: border-box;
                cursor: pointer;
            }
            .noti-win-head-user {
                display: flex;
                line-height: 10px;
            }
            .noti-win-head-user img {
                height: 100%;
                padding: 0 10px;
            }
            .noti-win-body {
                padding: 20px;
            }
            .noti-win-foot {
                display: flex;
                padding: 10px;
            }
            .noti-win-foot div {
                height: 20px;
                line-height: 25px;
                padding: 10px;
            }
            .noti-win-foot i {
                font-size: 24px;
                cursor: pointer;
            }

            <?php if (isMobile() == false) {?>
            .search {
                height: 50px;
                padding: 10px;
            }
            .search i {
                position: absolute;
                right: 25px;
                padding: 10px;
                cursor: pointer;
            }
            .search input {
                display: block;
                width: 100%;
                padding: 10px 20px;
                padding-right: 40px;
                color: white;
                background: none;
                border: 1px solid rgba(var(--lightblue),.5);
                border-radius: 40px;
                box-sizing: border-box;
                outline: none;
            }
            .search input::placeholder {
                color: white;
            }
            <?php } else {?>
            .user-dropdown .search {
                width: 100%;
                margin: 0 -20px;
                box-sizing: border-box;
            }
            .user-dropdown .search i {
                position: absolute;
                width: 20px !important;
                margin: 10px;
                padding: 0;
            }
            .user-dropdown .search input {
                width: 100%;
                padding: 10px 15px;
                padding-left: 40px;
                box-sizing: border-box;
                color: white;
                background: rgba(0,0,0,.1);
                border: none;
                border-radius: 40px;
                outline: none;
                transition: display .5s;
            }
            .user-dropdown .search input::placeholder {
                color: lightgray;
            }
            <?php }?>
            <?php if (isMobile() == false) {?>
            .header .top .user-avatar {
                height: 50px;
                margin: 8px 20px;
                overflow: hidden;
            }
            <?php } else {?>
            .header .top .user-avatar {
                width: 40%;
                height: 50px;
                text-align: center;
                margin: 8px 20px;
                overflow: hidden;
            }
            <?php }?>
            .header .top .user-avatar img {
                width: auto;
                max-width: 50px;
                height: 50px;
                border-radius: 50px;
                object-fit: cover;
            }
            .header .top .user-nav {
                height: 75px;
            }
            <?php if (isMobile() == false) {?>
            .header .top .user-nav {
                margin: 0 20px;
            }
            <?php } else {?>
            .header .top .user-nav {
                width: 30%;
                text-align: center;
                margin: 0 20px;
            }
            <?php }?>
            .header .top .user-nav ul {
                list-style: none;
                margin: 17.5px 0;
                padding: 0;
            }
            .header .top .user-nav li {
                display: inline-block;
                padding: 10px 11px;
                box-sizing: border-box;
            }
            .header .top .user-nav li i {
                margin-top: -10px;
                margin-left: -10px;
                padding: 10px;
                cursor: pointer;
            }
            .user-dropdown {
                position: absolute;
                top: 75px;
                color: white;
                box-shadow: 0 2px 2px rgba(0,0,0,.05);
                backdrop-filter: blur(5px);
                z-index: 9;
            }
            <?php if (isMobile() == false) {?>
            .user-dropdown {
                display: none;
                right: 40px;
                padding: 10px;
                background: rgba(var(--darker),.3);
                border-radius: 20px 0 20px 20px;
            }
            <?php } else {?>
            .user-dropdown {
                left: 0;
                width: 100%;
                padding: 20px;
                background: rgba(var(--dark),.9);
                box-shadow: 0 3px rgba(0,0,0,.2);
                transform: translateX(100%);
                transition: transform .5s;
            }
            <?php }?>
            .user-dropdown ul {
                list-style: none;
                margin: 0;
                padding: 0;
            }
            <?php if (isMobile() == false) {?>
            .user-dropdown ul li {
                padding: 10px;
                cursor: pointer;
            }
            .user-dropdown ul li:hover {
                background: rgba(0,0,0,.1);
                border-radius: 10px;
            }
            .user-dropdown ul li i {
                margin-right: 10px;
            }
            .user-dropdown li.balance i {
                margin-right: 10px;
                color: gold;
            }
            <?php } else {?>
            .user-dropdown ul li {
                padding: 15px;
                font-size: 18px;
            }
            .user-dropdown ul li i {
                width: 40px;
                margin-right: 0;
                padding: 0;
            }
            .user-dropdown li.balance {
                margin: 10px 0;
            }
            .user-dropdown li.balance i {
                margin-right: 0;
                color: gold;
            }
            <?php }?>

            /** Verify form */
            .verify_form input[type=number] {
                padding: 0;
                text-align: center;
            }
            .verify_form input[type=submit] {
                cursor: pointer;
            }
            .set_username_form input[type=submit] {
                cursor: pointer;
            }
            
            /** Form button */
            .form-button {
                width: 100%;
                height: 50px;
                margin: 5px 0;
                font-size: 24px;
                background: white;
                border: none;
                border-radius: 20px;
                box-sizing: border-box;
                cursor: pointer;
            }
            .form-button.signin {
                color: rgba(0,215,255,1);
                background: white;
            }
            .form-button.signup {
                color: white;
                background: linear-gradient(0deg, rgba(var(--greyblue),1) 0%, rgba(var(--lightblue),1) 100%);
            }

            <?php if (isMobile() == false) {?>
            .image_viewer {
                position: fixed;
                display: flex;
                top: 0;
                left: 0;
                width: calc(100% - 100px);
                height: calc(100% - 100px);
                margin: 50px;
                border-radius: 20px;
                box-sizing: border-box;
                overflow: hidden;
                z-index: 12;
            }
            .image_viewer .image_post {
                width: 30%;
                height: 100%;
                color: white;
                background: rgba(var(--greyblue),.8);
                backdrop-filter: blur(5px);
            }
            .image_viewer .image_post .comment_count {
                float: right;
                font-size: 12px;
                padding: 0 10px;
            }
            .image_viewer .image_box {
                width: 70%;
                height: 100%;
                background: rgba(0,0,0,.8);
                backdrop-filter: blur(5px);
            }
            <?php } else {?>
            .image_viewer {
                position: fixed;
                top: 0;
                left: 0;
                width: calc(100% - 100px);
                height: calc(100% - 100px);
                margin: 50px;
                border-radius: 20px;
                box-sizing: border-box;
                overflow: hidden;
                z-index: 12;
            }
            .image_viewer .image_box {
                width: 100%;
                height: 100%;
                background: rgba(0,0,0,.8);
                backdrop-filter: blur(5px);
            }
            <?php }?>
            .image_box_close {
                position: relative;
                width: 50px;
                height: 50px;
                margin-left: auto;
                margin-bottom: -50px;
                line-height: 50px;
                text-align: center;
                color: white;
            }
            .image_box .image_frame {
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100%;
                margin-bottom: -100px;
            }
            .image_box .image_frame img {
                width: auto;
                max-width: 100%;
                height: auto;
                max-height: 100%;
            }
            .image_box .image_slider {
                position: relative;
                display: flex;
                justify-content: center;
                width: 100%;
                height: 64px;
                margin-top: 20px;
                background: rgba(255,255,255,.1);
            }
            .image_box .image_slider img {
                width: auto;
                height: 60px;
                cursor: pointer;
                border: 2px solid transparent;
                border-radius: 5px;
            }
            .image_box .image_slider img.active {
                border-color: #007bff;
            }

            /** Share */
            /*.create_post {
            /*    width: 100%;
            /*    padding: 0;
            /*}
            /*.create_post textarea {
            /*    width: 100%;
            /*    height: 50%;
            /*    padding: 10px;
            /*    color: white;
            /*    background: none;
            /*    border: none !important;
            /*    outline: none;
            /*    box-sizing: border-box;
            /*    resize: none;
            /*}
            /*.create_post textarea::placeholder {
            /*    color: white;
            /*}
            /*.create_post_actions {
            /*    display: flex;
            /*    justify-content: space-between;
            /*    width: 100%;
            /*    padding: 0 10px;
            /*    box-sizing: border-box;
            /*}
            /*.create_post_actions input[type=file] {
            /*    width: 100px;
            /*    padding: 0;
            /*    color: white;
            /*}
            /*.create_post_actions input[type=file]::-webkit-file-upload-button {
            /*    visibility: hidden;
            /*    margin-top: -2px;
            /*    padding: 15px 0;
            /*}
            /*.create_post_actions input[type=file]::before {
            /*    content: 'Select file';
            /*    margin-right: 20px;
            /*    padding: 15px;
            /*    color: black;
            /*    background: white;
            /*    border-radius: 10px;
            /*}
            /*.create_post i {
            /*    line-height: 25px;
            /*    padding: 10px 20px;
            /*    color: white;
            /*}

            /** POST */
            <?php if (isMobile() == false) {?>
            .posts {
                width: 100%;
                max-width: 654px;
                margin: 0 auto;
                padding: 0 10px;
                box-sizing: border-box;
            }
            <?php } else {?>
            .posts {
                width: 100%;
                padding: 0 10px;
                box-sizing: border-box;
            }
            <?php }?>
            .post {
                width: 100%;
                margin: 0 auto;
                margin-bottom: 2px;
                padding-bottom: 10px;
            }
            .post_body {
                color: white;
                background: rgba(var(--darker),.7);
                border-radius: 20px;
                overflow: hidden;
            }
            .post_body a {
                color: white;
            }
            .post_header {
                display: flex;
                justify-content: space-between;
                height: 100px;
            }
            .post_details {
                width: 70%;
            }
            .post_user {
                display: flex;
                width: 100%;
                height: 50px;
            }
            .post_user_image {
                width: 50px;
                height: 50px;
                margin: 10px;
                border-radius: 50px;
                overflow: hidden;
                cursor: pointer;
            }
            .post_user_image img {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }
            .post_user_name {
                line-height: 50px;
                margin-left: 20px;
                padding: 10px 0;
            }
            .post_date {
                margin-left: 50px;
                padding: 10px 20px;
                font-size: 12px;
            }
            .post_actions {
                height: 50px;
                padding: 20px;
                text-align: right;
                font-size: 12px;
                box-sizing: border-box;
                cursor: pointer;
            }
            .post_actions:hover .post_action_list {
                display: flex;
            }
            .post_action_list {
                position: relative;
                background: rgba(0,0,0,.3);
                border-radius: 10px 3px 10px 10px;
                overflow: hidden;
            }
            .post_action {
                display: block;
                padding: 10px 20px;
                text-align: left;
                color: white;
            }
            .post_action:hover {
                background: rgba(255,255,255,.2);
                cursor: pointer;
            }
            .post_content {
                padding: 20px;
                word-break: break-all;
                overflow: hidden;
            }
            .post_links {
                width: 100%;
                margin: 20px 0;
                padding: 0;
                overflow: hidden;
            }
            .post_links iframe {
                width: 100%;
                aspect-ratio: 16/9;
                border: none;
            }
            .post_uploads {
                display: flex;
                box-sizing: border-box;
                overflow: auto;
            }
            .post_uploads {
                margin: 0 10px 10px 10px;
            }
            .post_uploads img {
                width: auto;
                max-width: 100px;
                height: auto;
                max-height: 100px;
                margin: 5px;
                border-radius: 10px;
            }
            .post_uploads img:hover {
                position: inherit;
                z-index: 2;
            }
            .post_comments {
                width: 100%;
                padding: 0 10px;
                box-sizing: border-box;
            }
            .post_body i {
                display: flex;
                justify-content: right;
                font-size: 12px;
                margin-bottom: 10px;
                padding: 0 10px;
            }
            .post_comment {
                display: flex;
                margin-bottom: 10px;
            }
            <?php if (isMobile() == false) {?>
            .post_comment_user {
                display: flex;
                min-width: 30px;
                max-width: 30%;
                padding-left: 10px;
                overflow: hidden;
            }
            <?php } else {?>
            .post_comment_user {
                display: flex;
                width: 40px;
                padding-left: 10px;
                overflow: hidden;
            }
            <?php }?>
            .post_comment_user img {
                max-width: 30px;
                max-height: 30px;
                margin-right: 10px;
                object-fit: cover;
                border-radius: 40px;
            }
            .post_comment_content input[type=text] {
                width: 100%;
                color: white;
                background: none;
                border-radius: 10px;
                border: none;
                outline: none;
                box-sizing: border-box;
            }
            <?php if (isMobile() == false) {?>
            .post_comment_user span {
                line-height: 30px;
            }
            .post_comment_content {
                max-width: 40%;
                line-height: 30px;
                margin: 0 10px;
                padding: 0 10px;
                background: rgba(255,255,255,.1);
                border-radius: 10px;
                box-sizing: border-box;
                overflow: auto;
                word-break: break-all;
            }
            <?php } else {?>
            .post_comment_user span {
                display: none;
            }
            .post_comment_content {
                max-width: calc(100% - 100px);
                line-height: 30px;
                padding: 0 10px;
                background: rgba(255,255,255,.1);
                border-radius: 10px;
                box-sizing: border-box;
                overflow: auto;
                word-break: break-all;
            }
            <?php }?>
            .post_comment_actions {
                margin-left: auto;
            }
            .post_comment_actions .btn {
                padding: 10px 10px 0 10px;
                cursor: pointer;
            }

            /** Side menu */
            .side_menu_back {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0,0,0,.5);
                z-index: 11;
            }
            .side_menu {
                width: 80%;
                height: 100%;
                background: white;
            }
            .menu_profile {
                width: 100%;
                text-align: center;
            }
            .menu_profile .profile_wallpaper {
                width: 100%;
                height: 150px;
                background: linear-gradient(0deg, var(--blue) 0%, var(--lightblue) 100%);
            }
            .menu_profile .profile_wallpaper img {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }
            .menu_profile .profile_image {
                position: relative;
                width: 150px;
                height: 150px;
                margin: 0 auto;
                margin-top: -75px;
                background: white;
                border: 3px solid white;
                border-radius: 150px;
                overflow: hidden;
            }
            .menu_profile .profile_image img {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }
            .menu_profile .profile_name {
                padding: 20px;
                font-size: 18px;
            }
            .menu_item {
                display: flex;
                justify-content: space-between;
                width: 100%;
                font-size: 24px;
                cursor: pointer;
            }
            .menu_item:hover {
                color: white;
                background: rgba(0,0,0,.5);
            }
            .menu_item i {
                width: 20%;
                margin-left: 20px;
                padding: 10px 0;
                text-align: center;
            }
            .menu_item p {
                width: 80%;
                margin: 0;
                padding: 10px 0;
            }

            /** Shortcuts */
            .shortcuts h3 {
                display: flex;
                justify-content: space-between;
                margin: 5px 10px;
                padding: 10px;
                border-radius: 10px;
            }
            .shortcuts i {
                cursor: pointer;
            }
            .shortcut-browse {
                text-align: center;
                padding: 10px;
                border: 1px dashed rgba(255,255,255,.2);
                border-radius: 10px;
            }
            .shortcut-browse:hover {
                background: rgba(255,255,255,.1);
                cursor: pointer;
            }

            /** Group list */
            .groups {
                width: 100%;
            }
            #my-groups {
                padding: 10px;
            }
            .group {
                display: flex;
                justify-content: space-between;
                width: 100%;
                padding: 0 10px;
                font-size: 14px;
                border-radius: 10px;
                box-sizing: border-box;
                transition: background .5s;
            }
            .group:hover {
                background: rgba(0,0,0,.1);
                cursor: pointer;
            }
            .group-icon {
                width: 40px;
                height: 40px;
                margin: 10px 10px 10px 0;
                overflow: hidden;
            }
            .group-icon img {
                width: auto;
                max-width: 100%;
                height: auto;
                max-height: 100%;
                border-radius: 40px;
                object-fit: cover;
            }
            .group-name {
                width: calc(100% - 40px);
                word-break: break-all;
                line-height: 60px;
                overflow: hidden;
            }
            .group-extra {
                line-height: 60px;
            }

            <?php if (isMobile() == false) {?>
            .new-group-create {
                display: flex;
            }
            .new-group-create .left {
                width: 400px;
                padding: 10px 20px;
                color: white;
                background: rgba(var(--dark),.5);
                border-radius: 10px;
                box-sizing: border-box;
            }
            .new-group-create .left span {
                display: none;
            }
            <?php } else {?>
            .new-group-create {
                display: block;
            }
            .new-group-create .left {
                width: calc(100% - 20px);
                height: 80px;
                margin: 0 10px;
                margin-top: 20px;
                padding: 10px 20px;
                color: white;
                background: rgba(var(--dark),.5);
                border-radius: 10px;
                box-sizing: border-box;
                overflow: hidden;
            }
            .new-group-create .left span {
                padding: 0 20px;
                color: grey;
                font-size: 12px;
                text-transform: uppercase;
            }
            <?php }?>
            .new-group-create .left ul {
                padding: 0 10px;
            }
            .new-group-create .left li {
                padding: 10px;
            }
            .new-group-create input[type=radio] {
                width: 30px;
            }
            .new-group-create select {
                width: 100%;
                height: 40px;
                margin-bottom: 5px;
                padding: 0 10px;
                color: white;
                background: rgba(0,0,0,.1);
                border: none;
                border-radius: 10px;
                outline: none;
            }
            .new-group-create option {
                background: black;
            }
            .new-group-privacy {
                display: flex;
                justify-content: space-evenly;
            }
            .new-group-privacy span {
                display: flex;
                align-items: center;
                color: white;
            }

            <?php if (isMobile() == false) {?>
            .group-head {
                width: 100%;
                height: 50px;
                line-height: 50px;
                margin: 0 auto;
                padding: 0 10px 10px 10px;
                text-align: left;
                box-sizing: border-box;
                overflow: hidden;
                word-wrap: break-word;
            }
            <?php } else {?>
            .group-head {
                width: 100%;
                height: 50px;
                line-height: 50px;
                margin: 0 auto;
                padding: 0 10px 10px 10px;
                text-align: center;
                box-sizing: border-box;
                overflow: hidden;
                word-wrap: break-word;
            }
            <?php }?>
            .group-box {
                display: flex;
                height: 500px;
                box-sizing: border-box;
            }
            .group-box p {
                padding: 10px 20px;
                text-align: left;
                color: white;
            }
            <?php if (isMobile() == false) {?>
            .gbox-left {
                width: 25%;
                background: rgba(255,255,255,.3);
                border-radius: 20px;
                overflow: hidden;
            }
            .gbox-feed {
                width: 75%;
                height: 100%;
                margin: 0 5px;
                background: rgba(0,0,0,.1);
            }
            .gbox-right {
                width: 10%;
                background: rgba(255,255,255,.2);
                border-radius: 20px;
                overflow: hidden;
            }
            <?php } else {?>
            .gbox-left {
                position: fixed;
                top: 75px;
                left: 0;
                width: 95%;
                height: calc(100% - 75px);
                background: rgba(var(--dark),.1);
                border-top-right-radius: 20px;
                transform: translateX(-100%);
                overflow: hidden;
                z-index: 5;
            }
            .gbox-feed {
                width: calc(100% - 10px);
                margin: 0 5px;
                background: rgba(0,0,0,.1);
                border-radius: 10px;
                box-sizing: border-box;
            }
            .gbox-right {
                position: fixed;
                top: 75px;
                right: 0;
                width: 95%;
                height: calc(100% - 75px);
                background: rgba(var(--dark),.1);
                border-top-right-radius: 20px;
                transform: translateX(100%);
                overflow: hidden;
                z-index: 5;
            }
            <?php }?>
            .gbox-left hr {
                width: 90%;
                height: 1px;
                background: rgba(0,0,0,.1);
                border: none;
            }
            .gbox-left p {
                text-align: center;
            }
            .gbox-memberlist {
                height: 40%;
                margin: 5px;
                text-align: center;
            }
            .gbox-member {
                display: inline-flex;
                width: 50px;
                margin: 5px;
            }
            .gbox-member img {
                width: 50px;
                height: 50px;
                text-align: center;
                background: black;
                border-radius: 50px;
                object-fit: cover;
            }
            .gbox-member-name {
                display: none;
                position: absolute;
                width: 100px;
                margin-top: 50px;
                margin-left: -25px;
                padding: 10px;
                font-size: 14px;
                text-align: center;
                color: White;
                background: black;
                border-radius: 20px;
                box-sizing: border-box;
                overflow: scroll;
                word-wrap: break-word;
            }
            .gbox-member:hover .gbox-member-name {
                display: block;
            }
            .gbox-gallery-grid {
                text-align: center;
                margin: 5px;
            }
            .gbox-gallery-item {
                display: inline-flex;
                width: 50px;
                margin: 5px;
            }
            .gbox-gallery-item img {
                width: 50px;
                height: 50px;
                text-align: center;
                background: black;
                border-radius: 10px;
                object-fit: cover;
            }
            .gbox-chat {
                width: auto;
                height: 100%;
                overflow: scroll;
            }
            .gbox-msg-send {
                display: flex;
                justify-content: right;
            }
            .gbox-msg-send input {
                padding: 0 10px;
            }
            .gbox-msg-send i {
                position: absolute;
                margin-right: 10px;
                cursor: pointer;
                opacity: 0;
            }
            .gbox-msg-send:hover i {
                opacity: 1;
            }
            .gbox-right p {
                text-align: center;
            }
            .gbox-right-icons {
                text-align: center;
                width: 100%;
                padding: 5px;
                color: white;
                box-sizing: border-box;
            }
            .gbox-right-icon {
                width: 100%;
                padding: 10px 0;
                font-size: 2vw;
                border-radius: 15px;
                box-sizing: border-box;
            }
            .gbox-right-icon:hover {
                background: rgba(0,0,0,.1);
                cursor: pointer;
            }
            .gbox-settings {
                height: 50px;
            }

            /** Page list */
            .pages {
                width: 100%;
            }
            #my-pages {
                padding: 10px;
            }
            .page {
                display: flex;
                justify-content: space-between;
                width: 100%;
                font-size: 14px;
                border-radius: 10px;
                box-sizing: border-box;
                transition: background .5s;
            }
            .page:hover {
                background: rgba(0,0,0,.1);
                cursor: pointer;
            }
            .page-logo {
                width: 40px;
                height: 40px;
                margin: 10px;
                overflow: hidden;
            }
            .page-logo img {
                width: auto;
                max-width: 100%;
                height: auto;
                max-height: 100%;
                border-radius: 40px;
                object-fit: cover;
            }
            .page-name {
                width: calc(100% - 40px);
                line-height: 40px;
                padding: 10px 0;
                overflow: hidden;
            }

            /** Browse */
            .pages-browse,
            .groups-browse {
                display: flex;
                flex-wrap: wrap;
                justify-content: center;
                margin-top: 20px;
            }
            .pb-intro,
            .gb-intro {
                text-align: center;
                padding: 20px 30px;
                font-size: 24px;
                color: rgba(100,100,100,1);
                border: 1px dashed rgba(100,100,100,1);
                border-radius: 100%;
            }
            .pb-box {
                float: left;
                width: 300px;
                height: 160px;
                max-height: auto;
                margin: 3px;
                border: 1px solid white;
                border-radius: 20px;
                overflow: hidden;
            }
            .pb-wallpaper {
                position: relative;
                width: 100%;
                height: 160px;
                background: linear-gradient(343deg, rgba(130,214,255,1) 0%, rgba(45,141,234,1) 100%);
                z-index: -1;
            }
            .pb-wallpaper img {
                width: 100%;
                height: auto;
                object-fit: contain;
            }
            .pb-icon {
                position: relative;
                width: 100px;
                height: 100px;
                margin: 0 auto;
                margin-top: -150px;
                background: rgba(var(--dark),1);
                border: 1px solid white;
                border-radius: 50px;
                z-index: 1;
            }
            .pb-icon img {
                width: 100%;
                height: auto;
                object-fit: contain;
            }
            .pb-info {
                position: relative;
                text-align: center;
                width: 100%;
                height: 50px;
                margin-top: 10px;
                padding: 10px;
                color: white;
                background: rgba(var(--darker),1);
                border: 1px solid white;
                border-radius: 20px;
                box-sizing: border-box;
            }
            .pb-info:hover {
                position: absolute;
                width: 300px;
                height: auto;
                z-index: 2;
            }
            .pb-info:hover p {
                position: relative;
            }
            .pb-info span {
                width: 100%;
                line-height: 20px;
            }
            .pb-info span i {
                position: relative;
                float: right;
                padding: 10px;
            }
            .pb-info p {
                padding: 0 10px;
                text-align: left;
            }
            
            .gb-box {
                float: left;
                width: 300px;
                height: 150px;
                max-height: auto;
                margin: 3px;
                border: 1px solid white;
                border-radius: 20px;
                overflow: hidden;
            }
            .gb-wallpaper {
                position: relative;
                width: 100%;
                height: 150px;
                background: linear-gradient(343deg, rgba(130,214,255,1) 0%, rgba(45,141,234,1) 100%);
                z-index: -1;
            }
            .gb-wallpaper img {
                width: 100%;
                height: auto;
                object-fit: contain;
            }
            .gb-info {
                position: relative;
                text-align: center;
                width: 100%;
                height: 50px;
                margin-top: -60px;
                color: white;
                background: rgba(var(--darker),1);
                border: 1px solid white;
                border-radius: 20px;
                box-sizing: border-box;
            }
            .gb-info:hover {
                position: absolute;
                width: 300px;
                height: auto;
                z-index: 2;
            }
            .gb-info:hover p {
                position: relative;
            }
            .gb-info span {
                width: 100%;
                line-height: 40px;
                padding: 5px;
            }
            .gb-info span i {
                position: relative;
                float: right;
                padding: 10px;
            }
            .gb-info p {
                padding: 0 10px;
                text-align: left;
            }

            .markets {
                width: 100%;
            }
            #my-markets {
                padding: 10px;
            }

            /** Friend list */
            .friend-list {
                width: 100%;
                height: 100%;
                color: White;
            }
            .friend-list h3 {
                padding: 0 20px;
            }
            .friend {
                width: 100%;
                height: 60px;
                margin-bottom: 3px;
                font-size: 14px;
                transition: background .5s, height .2s;
                overflow: hidden;
            }
            .friend:hover {
                height: 90px;
                cursor: pointer;
            }
            .friend:hover .friend-actions {
                height: 30px;
            }
            .friend-user {
                display: flex;
                justify-content: space-between;
                background: rgba(255,255,255,.05);
                border-radius: 10px;
            }
            .friend-user .friend-avatar {
                width: 50px;
                height: 40px;
                margin: 10px;
                overflow: hidden;
            }
            .friend-user .friend-avatar img {
                width: auto;
                max-width: 40px;
                height: 40px;
                border-radius: 40px;
                object-fit: cover;
            }
            .friend-user .friend-name {
                width: calc(100% - 40px);
                line-height: 40px;
                padding: 10px 0;
                overflow: hidden;
            }
            .friend-actions {
                display: flex;
                width: 90%;
                height: 0;
                margin: 0 auto;
                background: rgba(0,0,0,.1);
                border-bottom-left-radius: 10px;
                border-bottom-right-radius: 10px;
                overflow: hidden;
                transition: height .2s;
            }
            .friend-action {
                width: 30px;
                height: 20px;
            }
            .friend-action i {
                line-height: 30px;
                padding: 0 10px;
                text-align: center;
            }
            .friend-action i:hover {
                background: rgba(0,0,0,.1);
            }

            /** Messages */
            .messsages-head {
                display: flex;
                justify-content: space-between;
                width: 1005;
                color: white;
                background: rgba(255,255,255,.2);
                border-radius: 5px;
            }
            .messsages-head h3 {
                line-height: 40px;
                margin: 0 10px;
            }
            .messsages-head input {
                width: 50%;
                height: 40px;
                padding: 0 10px;
                color: white;
                background: none;
                border: none;
                outline: none;
                box-sizing: border-box;
            }
            .messsages-head input::placeholder {
                color: white;
            }
            .message-item {
                display: flex;
                justify-content: space-between;
                height: 50px;
                margin: 10px;
                color: white;
                border-radius: 50px;
                overflow: hidden;
                transition: background .3s;
            }
            .message-item:hover {
                background: rgba(255,255,255,.1);
                cursor: pointer;
            }
            .message-new {
                width: calc(100% - 20px);
                height: 50px;
                line-height: 50px;
                margin: 10px;
                text-align: center;
                color: white;
                border: 1px solid rgba(255,255,255,.2);
                border-radius: 20px;
                box-sizing: border-box;
            }
            .message-avatar {
                width: 45px;
                height: 45px;
                margin: 2.5px 0 0 2.5px;
                border-radius: 45px;
                overflow: hidden;
            }
            .message-avatar img {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }
            .message-info {
                width: calc(100% - 150px);
                padding: 10px;
            }
            .message-info .message-user {
                font-size: 16px;
            }
            .message-info .message-last {
                font-size: 12px;
            }
            .message-action {
                width: 50px;
                text-align: right;
                line-height: 50px;
                font-size: 24px;
                padding: 0 10px;
            }

            /** Chat */
            .chat {
                width: 100%;
                height: calc(100% - 120px);
                box-sizing: border-box;
            }
            .chat_header {
                display: flex;
                justify-content: space-between;
                height: 50px;
                line-height: 50px;
                text-align: center;
                font-size: 18px;
                color: white;
            }
            .chat_header .chat_back {
                width: 15%;
            }
            .chat_header .chat_title {
                display: flex;
                width: 70%;
            }
            .chat_header .chat_title .chat_title_avatar {
                width: 40px;
                height: 40px;
                margin: 5px 10px;
                border-radius: 40px;
                overflow: hidden;
            }
            .chat_header .chat_title .chat_title_avatar img {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }
            .chat_header .chat_action {
                width: 15%;
                padding: 0;
            }
            .chat_box {
                width: 100%;
                height: 500px;
                background: transparent;
                border-radius: 10px;
                box-sizing: border-box;
                overflow: auto;
            }
            .chat_box::-webkit-scrollbar {
                display: none;
            }
            .chat_message {
                display: flex;
                padding: 10px;
            }
            .chat_message.me {
                justify-content: flex-end;
            }
            .chat_message_options {
                position: relative;
                width: 10px;
                padding: 10px;
                text-align: left;
                color: white;
                cursor: pointer;
                opacity: 0;
            }
            .chat_message:hover .chat_message_options {
                opacity: 1;
            }
            .chat_message_options:hover .chat_message_option_list {
                display: block;
            }
            .chat_message_option_list {
                display: none;
                position: absolute;
                background: rgba(255,255,255,.3);
                backdrop-filter: blur(5px);
                box-shadow: 10px 10px 23px -5px rgba(0,0,0,0.6);
                border-radius: 0 10px 10px 10px;
                overflow: hidden;
            }
            .chat_action {
                display: flex;
                padding: 10px 20px;
                color: white;
            }
            .chat_action:hover {
                background: rgba(0,212,255,.3);
                cursor: pointer;
            }
            .chat_action i {
                margin-right: 10px;
            }
            .chat_user {
                width: 50px;
                height: 50px;
                margin-right: 10px;
                border-radius: 50px;
                overflow: hidden;
            }
            .me .chat_user {
                margin-left: 10px;
                margin-right: 0;
            }
            .chat_user img {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }
            .chat_message_box {
                display: flex;
                justify-content: end;
                width: calc(100% - 60px);
            }
            .chat_text {
                max-width: calc(100% - 60px);
                padding: 15px;
                background: white;
                border-radius: 20px;
                box-sizing: border-box;
            }
            .chat_message_box .chat_text {
                max-width: calc(100% - 60px);
                padding: 15px;
                background: white;
                border-radius: 20px;
                box-sizing: border-box;
            }
            .chat_input {
                display: flex;
                margin: 0 10px;
                overflow: hidden;
            }
            .chat_input input {
                width: calc(100% - 60px);
                height: 40px;
                padding: 0 20px;
                color: white;
                background: none;
                border: none;
                box-sizing: border-box;
                outline: none;
            }
            .chat_input input::placeholder {
                color: white;
            }
            .chat_input button {
                width: 60px;
                height: 40px;
                color: white;
                background:none;
                border: none;
                cursor: pointer;
            }
            
            .profile-wallpaper {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                border-bottom: 2px solid black;
                z-index: -1;
            }
            .profile-wallpaper img {
                width: 100%;
                height: 100%;
                background-size: cover;
                background-position-y: bottom;
                background-position-x: center;
                background-attachment: fixed;
                object-fit: cover;
                pointer-events: none;
            }
            
            <?php if (isMobile() == false) {?>
            .profile {
                display: flex;
                justify-content: space-around;
            }
            .profile-left {
                width: 30%;
                margin-right: 13px;
            }
            .profile-left i {
                position: absolute;
                width: 40px;
                height: 40px;
                text-align: center;
                cursor: pointer;
            }
            .profile-left .avatar {
                position: relative;
                width: 150px;
                height: 150px;
                margin: 0 auto;
                border-radius: 25px;
                overflow: hidden;
                object-fit: cover;
            }
            .profile-left-user i {
                position: relative;
                float: right;
                margin-top: -40px;
                width: 40px;
                height: 40px;
                cursor: pointer;
            }
            .profile-left .username {
                width: 100%;
                margin-top: 20px;
                padding: 0 10px;
                text-align: center;
                font-size: 1.3rem;
                color: white;
                overflow: hidden;
                box-sizing: border-box;
            }
            .profile-btns {
                display: flex;
                height: 100%;
                margin: 20px 0;
                box-sizing: border-box;
            }
            .profile-btns button {
                width: auto;
                height: 50px;
                margin: 3px;
                padding: 0 10px;
                color: white;
                background: rgba(255,255,255,.1);
                backdrop-filter: blur(5px);
                border: none;
                border-radius: 10px;
                box-sizing: border-box;
                transition: background .3s, transform .3s;
            }
            .profile-btns button:hover {
                transform: scale(1.15);
                cursor: pointer;
            }
            .profile-btns button span {
                padding-left: 25px;
            }
            .profile-btns button i {
                width: 10px;
                height: 10px;
            }
            .profile-btns button.red {
                background: rgba(255,0,0,.5);
            }
            
            .profile-right {
                width: 70%;
            }
            .profile-right .create_post {
                height: 50px;
            }
            .profile-right .create_post textarea {
                padding-right: 40px;
                border: 1px solid rgba(0,0,0,.2);
            }
            .profile-right .create_post i {
                float: right;
                margin-top: -35px;
            }
            .profile-right .post {
                width: 100%;
            }
            <?php } else {?>
            .profile-left {
                display: flex;
                margin-bottom: 10px;
                padding: 10px;
                overflow-x: scroll;
            }
            .profile-left-user {
                display: flex;
                margin-right: 20px;
            }
            .profile-left-user .avatar {
                width: 100px;
                height: 100px;
                border-radius: 25px;
            }
            .profile-left-user .username {
                text-align: left;
                width: auto;
                margin-top: 20px;
                padding: 0 10px;
                font-size: 1.3rem;
                color: white;
                overflow: hidden;
                box-sizing: border-box;
            }
            .profile-btns {
                display: flex;
                height: 100%;
                margin: 20px 0;
                box-sizing: border-box;
            }
            .profile-btns button {
                width: 50px;
                height: 50px;
                margin: 3px;
                border: 1px solid rgba(255,255,255,.3);
                border-radius: 10px;
                box-sizing: border-box;
            }
            .profile-tabs {
                position: absolute;
                display: flex;
                left: 0;
                top: 190px;
                padding: 0 20px;
                box-sizing: border-box;
            }
            .profile-tabs .settings-cat {
                width: 60px;
                height: 50px;
            }
            .settings-cat .settings-icon {
                padding: 0 10px;
                text-align: center;
            }
            .settings-cat .settings-name {
                display: none;
            }
            <?php }?>
            
            .profile-left {
                color: white;
            }
            .profile-left hr {
                height: 1px;
                background: rgba(0,0,0,.1);
                border: none;
            }
            .profile-left .avatar img {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }
            .profile-left .avatar i {
                float: left;
                width: 40px;
                line-height: 40px;
                margin-top: -40px;
                text-align: center;
            }
            .profile-left .username span {
                display: block;
                padding: 10px 0;
                font-size: 14px;
                color: black;
            }

            <?php if (isMobile() == false) {?>
            .changeAvatar,
            .changeWallpaper {
                position: fixed;
                top: 20%;
                left: -200px;
                width: 400px;
                margin-left: 50%;
                padding: 20px;
                background: white;
                border-radius: 10px;
                box-sizing: border-box;
            }
            <?php } else {?>
            .changeAvatar,
            .changeWallpaper {
                position: fixed;
                top: 20px;
                left: 20px;
                width: calc(100% - 40px);
                height: 300px;
                margin: 0 auto;
                padding: 20px;
                background: white;
                border-radius: 20px;
                box-sizing: border-box;
            }
            <?php }?>

            .settings-cat {
                display: flex;
                justify-content: space-between;
                width: 100%;
                max-height: 40px;
                padding: 0 10px;
                font-size: 14px;
                border-radius: 10px;
                box-sizing: border-box;
                transition: background .5s;
            }
            .settings-cat:hover {
                background: rgba(0,0,0,.1);
                cursor: pointer;
            }
            .settings-icon {
                width: 30px;
                height: 30px;
                font-size: 18px;
                margin: 10px 10px 10px 0;
                overflow: hidden;
            }
            .settings-icon i {
                width: auto;
                max-width: 100%;
                height: auto;
                max-height: 100%;
                border-radius: 40px;
                object-fit: cover;
            }
            .settings-name {
                width: calc(100% - 40px);
                word-break: break-all;
                line-height: 40px;
                overflow: hidden;
            }
            .settings {
                width: 90%;
                margin: 0 auto;
                padding-bottom: 100px;
            }
            .settings h3 {
                margin: 10px 0;
                padding: 0;
                color: white;
            }
            .settings i {
                position: relative;
                float: left;
                line-height: 45px;
                margin-left: 15px;
                margin-bottom: -45px;
                color: rgba(0,215,255,1);
            }
            .ip-log {
                display: flex;
                justify-content: space-between;
                width: 100%;
                margin-bottom: 5px;
                padding: 10px;
                border-radius: 10px;
                background: rgba(0,0,0,.3);
            }
            .ip-actions {
                text-align: right;
            }
            .ip-action {
                display: block;
                margin: 2px;
                padding: 5px;
                color: white;
                background: rgba(0,212,255,.3);
                border: none;
                border-radius: 5px;
                cursor: pointer;
            }

            .check_mobile {
                position: fixed;
                top: 10%;
                width: 90%;
                margin: 0 5%;
                padding: 120px;
                box-sizing: border-box;
                background: white;
                z-index: 5;
            }
        </style>