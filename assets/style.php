        <style>
            :root {
                --mode: 0,0,0;
                --mode-invert: 255,255,255;
                --mode-text: white;
                --mode-placeholder: lightgrey;
                --lightmode: linear-gradient(to top, #48c6ef 0%, #6f86d6 100%);
                --darkmode: linear-gradient(to top, #243B55 0%, #141E30 100%);
            }

            html {
                font-family: Arial, Helvetica, sans-serif;
                padding-top: env(safe-area-inset-top);
            }
            body {
                margin: 0 auto;
                padding: 0;
                padding-top: env(safe-area-inset-top);
                color: var(--mode-text);
            }

            .background {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                z-index: -1;
            }

            .clouds {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                z-index: -1;
            }

            @media (prefers-color-scheme: light) {
                .clouds {
                    background: linear-gradient(to top, #48c6ef 0%, #6f86d6 100%);
                }
            }
            @media (prefers-color-scheme: dark) {
                .clouds {
                    background: linear-gradient(to top, #243B55 0%, #141E30 100%);
                }
            }

            .cloud {
                position: absolute;
                opacity: .1;
                pointer-events: none;
            }

            *::-webkit-scrollbar {
                display: none;
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
                color: var(--mode-text);
                z-index: 3;
            }
            .new_user {
                display: flex;
                align-items: center;
                opacity: 0;
                margin: 2px 0;
                background: rgba(var(--mode-invert),.1);
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
                transition: all .5s ease-in-out;
                opacity: 1;
                z-index: 10;
                cursor: pointer;
            }
            #welcome-click {
                position: absolute;
                bottom: 20px;
                left: 50%;
                font-size: 18px;
                color: var(--mode-placeholder);
                transform: translateX(-50%);
                transition: all .5s ease-in-out;
                opacity: 0;
            }
            #welcome-click.show {
                opacity: 1;
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
                color: var(--mode-text);
                z-index: 9999;
                transition: all .5s ease-in-out;
            }
            #welcome-inner img {
                height: 200px;
                animation: cloudZoom 2s infinite ease-in-out;
            }
            @keyframes cloudZoom {
                0% { transform: scale(1.2); }
                50% { transform: scale(0.8); }
                100% { transform: scale(1.2); }
            }
            #welcome-inner h1,
            #welcome-inner h3 {
                margin: 0;
            }

            /** Pixelate images */
            .lazy-load {
                image-rendering: pixelated; /* or 'crisp-edges' */
            }

            /** Beta page */
            .beta {
                width: 100%;
                padding: 20px;
                background: rgba(var(--mode),.3);
                backdrop-filter: blur(5px);
                border-radius: 20px;
                box-sizing: border-box;
            }
            .beta input {
                width: 100%;
                height: 40px;
                padding: 5px 0;
                font-size: 18px;
                color: var(--mode-text);
                background: none;
                border: none;
                outline: none;
            }
            /** Beta message */
            .beta-feedback {
                display: flex;
                flex-direction: column;
                width: 100%;
                background: var(--dark);
                border-radius: 10px;
            }
            .beta-feedback-body {
                display: flex;
                flex-direction: column;
                padding: 10px;
            }
            .beta-feedback-body textarea {
                width: 100%;
                height: 50px;
                background: var(--dark);
                color: var(--light);
                border: none;
                border-radius: 10px;
                padding: 0 10px;
                resize: none;
                box-sizing: border-box;
                outline: none;
            }
            .beta-feedback-footer {
                display: flex;
                justify-content: flex-end;
                padding: 10px;
            }

            .feedback-admin {
                width: 100%;
                max-width: 800px;
                margin: 0 auto;
                padding: 20px;
            }
            .feedback {
                width: 100%;
                background: rgba(var(--mode),.2);
                border-radius: 5px;
                margin: 10px 0;
                padding: 10px;
                box-shadow: 0 0 5px rgba(0,0,0,0.1);
            }
            .feedback-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
            }
            .feedback-user {
                display: flex;
                align-items: center;
            }
            .feedback-user img {
                width: 30px;
                height: 30px;
                border-radius: 50%;
                margin-right: 10px;
            }
            .feedback-content {
                margin-top: 10px;
            }
            .feedback-page {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-top: 10px;
            }
            .feedback-page button {
                padding: 5px 10px;
                border: none;
                border-radius: 5px;
                background: rgba(var(--mode),.1);
                color: var(--mode-text);
                cursor: pointer;
            }
            .feedback-page button:hover {
                background: rgba(var(--mode),.3);
            }
            .feedback-actions {
                display: flex;
                justify-content: space-between;
                margin-top: 10px;
            }
            .feedback-action {
                padding: 5px;
                cursor: pointer;
            }
            .feedback-actions i.fa-circle-check,
            .feedback-actions i.fa-circle {
                font-size: 24px;
            }
            .feedback-actions i.fa-circle-check {
                color: #4CAF50;
            }
            .feedback-actions i.fa-trash {
                color: #ff4545;
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
                color: var(--mode-text);
            }
            .form i {
                position: relative;
                float: left;
                line-height: 40px;
                margin-left: 15px;
                margin-bottom: -40px;
                color: var(--mode-text);
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
                color: var(--mode-text);
                background: rgba(var(--mode),.1);
                border-radius: 10px;
                border: none;
                box-sizing: border-box;
                outline: none;
            }
            .form input::placeholder,
            .form input[type=date]::placeholder {
                color: var(--mode-text);
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
                color: var(--mode-text);
                background: rgba(var(--mode),.1);
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
            .form .terms i {
                font-size: 18px;
            }
            .form .terms input {
                padding-left: 50px !important;
            }
            .form .terms .refer_user {
                display: flex;
            }
            .refer_user .ref_u_avatar {
                width: 50px;
                height: 50px;
                margin: 10px 0;
                text-align: center;
                background: rgba(var(--mode),.2);
                border-radius: 10px;
                overflow: hidden;
            }
            .refer_user .ref_u_avatar img {
                width: 100%;
                height: auto;
                max-height: 100%;
                object-fit: cover;
                object-position: center;
            }
            .refer_user .ref_u_name {
                width: calc(100% - 70px);
                line-height: 70px;
                margin-left: 10px;
            }
            .form .terms .check {
                display: flex;
                margin: 20px 0;
            }
            .form .terms .check input {
                width: 20px;
                margin-right: 10px;
            }
            .form .terms .check label {
                line-height: 45px;
            }
            .form .terms .check label span {
                text-decoration: underline;
                cursor: pointer;
            }
            .form input[type=submit] {
                margin-bottom: 0;
                padding: 0;
                border-radius: 10px;
                color: var(--mode-text);
                background: rgba(var(--mode-invert),.1);
                border: none;
                box-sizing: border-box;
                outline: none;
                cursor: pointer;
            }
            .form input[type=submit]:hover {
                background: rgba(var(--mode-invert),.2);
            }

            .popup {
                display: none;
                position: absolute;
                padding: 20px;
                color: var(--mode-text);
                background: rgba(var(--mode),.9);
                box-sizing: border-box;
                z-index: 5;
            }
            <?php if (isMobile($userAgent) == false) {?>
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
                color: var(--mode-text);
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
            <?php if (isMobile($userAgent) == false) {?>
            .page-container {
                min-width: 300px;
                max-width: 650px;
                margin: 0 auto;
                margin-top: 75px;
            }
            .group-container {
                min-width: 300px;
                margin: 0 auto;
                margin-top: 0px;
                padding: 10px 0;
            }
            @media only screen and (min-width: 1240px) {
                .group-container {
                    max-width: calc(100% - 600px);
                }
            }
            @media only screen and (max-width: 1239px) {
                .group-container {
                    max-width: calc(100% - 100px);
                    margin-top: 75px;
                }
            }
            <?php } else {?>
            .page-container {
                min-width: 300px;
                width: 100%;
                max-width: 800px;
                margin: 0 auto;
                padding: 75px 0;
            }
            .group-container {
                min-width: 300px;
                margin: 0 auto;
                margin-top: 100px;
                padding: 10px 0;
            }
            <?php }?>
            .page-head {
                text-align: center;
                color: var(--mode-text);
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
                top: 0;
                color: var(--mode-text);
                transition: transform .5s;
                overflow-y: auto;
                z-index: 3;
            }
            <?php if (isMobile($userAgent) == false) {?>
            .left-panel,
            .right-panel {
                width: 25%;
                max-width: 300px;
                height: 100%;
                padding: 0 10px;
                margin-bottom: 60px;
                padding-top: 75px;
                box-sizing: border-box;
            }
            <?php } else {?>
            .left-panel,
            .right-panel {
                width: 100%;
                height: calc(100% - 75px);
                margin-bottom: 60px;
                padding-top: 75px;
                background: rgba(var(--mode),.5);
                backdrop-filter: blur(5px);
            }
            <?php }?>
            
            .left-panel {
                left: 0;
                <?php if (isMobile($userAgent) == true) {?>
                transform: translateX(-100%);
                <?php }?>
            }
            .left-panel h3 {
                margin-left: 10px;
            }
            .left-panel button.btn {
                padding: 10px 15px;
                color: var(--mode-text);
                background: rgba(var(--mode),.2);
                border-radius: 5px;
                border: none;
                cursor: pointer;
                transition: transform .3s;
            }
            .left-panel button.btn:hover {
                transform: scale(1.1);
            }
            .left-panel-open {
                /** border-top: 1px solid rgba(var(--mode-invert),.3);
                border-right: 1px solid rgba(var(--mode-invert),.3);
                border-bottom: 1px solid rgba(var(--mode-invert),.3);
                border-top-right-radius: 10px;
                border-bottom-right-radius: 10px; */
            }
            
            .right-panel {
                right: 0;
                <?php if (isMobile($userAgent) == true) {?>
                transform: translateX(100%);
                <?php }?>
            }
            .right-panel h3 {
                text-align: center;
            }
            .right-panel-open {
                right: 0;
                /** border-top: 1px solid rgba(var(--mode-invert),.3);
                border-left: 1px solid rgba(var(--mode-invert),.3);
                border-bottom: 1px solid rgba(var(--mode-invert),.3);
                border-top-left-radius: 10px;
                border-bottom-left-radius: 10px; */
            }

            .left-panel-open,
            .right-panel-open {
                position: fixed;
                top: 50%;
                padding: 30px 10px;
                backdrop-filter: blur(5px);
                cursor: pointer;
                transition: all .5s;
            }

            /** Bottom Navigation */
            .bottom-nav {
                position: fixed;
                display: flex;
                align-items: center;
                justify-content: space-between;
                text-align: center;
                bottom: 0;
                left: 50%;
                width: auto;
                height: 50px;
                font-size: 18px;
                transform: translate(-50%, -20px);
                color: var(--mode-text);
                background: rgba(var(--mode),.7);
                border: 1px solid rgba(var(--mode),5);
                border-radius: 100px;
                box-sizing: border-box;
                transition: all .3s;
                overflow: hidden;
                z-index: 9;
            }
            .bnav-btn {
                width: 50px;
                height: 100%;
                line-height: 28px;
                padding: 10px;
                box-sizing: border-box;
            }
            .bnav-btn:nth-of-type(2) {
                font-size: 24px;
            }
            .bnav-btn i {
                transition: all .5s ease-in-out;
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
                background: rgba(var(--mode),.7);
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
                color: var(--mode-text);
                background: rgba(var(--mode),.7);
                box-shadow: 0 0 2px rgba(var(--mode),.5);
            }

            /** Friend button */
            .noti_menu {
                position: fixed;
                right: 20px;
                bottom: 20px;
                width: 50px;
                height: 50px;
                line-height: 45px;
                text-align: center;
                font-size: 24px;
                background: rgba(var(--mode),.5);
                border-radius: 25px;
                border: 1px solid rgba(var(--mode-text),1);
                box-sizing: border-box;
                z-index: 10;
            }
            .noti_menu i {
                font-size: 20px;
            }

            .loading {
                width: 100%;
            }

            /** Start page */
            <?php if (!isset($_SESSION['user'])) {
                if (isMobile($userAgent) == false) {?>
            .start {
                position: relative;
                display: flex;
                gap: 10px;
                justify-content: space-around;
                top: 200px;
                min-width: 300px;
                max-width: 1100px;
                margin: 0 auto;
                padding: 0 10px;
            }
            @media only screen and (max-width: 680px) {
                .start {
                    display: block;
                    width: 90%;
                    margin: 0 auto;
                }
                .start .welcome_information {
                    width: calc(100% - 20px);
                }
                .start .center_form {
                    width: calc(100% - 20px);
                    margin-top: 10px;
                }
            }
            .welcome_information {
                width: 60%;
                color: var(--mode-text);
                text-align: center;
            }
            .info_text,
            .reg_info {
                padding: 40px 10px;
                background: rgba(var(--mode),.4);
                backdrop-filter: blur(5px);
                border-radius: 20px;
                box-sizing: border-box;
                overflow: hidden;
            }
            .info_text p {
                display: none;
            }
            .info_text .intro {
                margin-top: 10px;
                padding: 10px 0;
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
                height: 75px;
                margin: 0 auto;
                margin-top: 75px;
                text-align: center;
                color: var(--mode-text);
                background: rgba(var(--mode),.4);
                border-radius: 20px;
                overflow: hidden;
            }
            .info_text {
                width: 100%;
                padding: 20px;
                box-sizing: border-box;
            }
            <?php }}?>
        
            #welcome_info,
            #reg_info,
            #reg_table,
            #reg_table tr {
                transition: all .3s ease-in-out;
            }
            
            .info_text h2 {
                margin: 0;
                padding: 0;
            }
            .info_text p {
                font-size: 8px;
                color: rgb(var(--dark));
            }
            .info_text ul {
                margin: 0 20px;
                padding: 0;
                list-style: none;
                box-sizing: border-box;
            }
            .info_text b {
                padding: 0 10px;
                line-height: 30px;
            }
            .intro pre {
                white-space: pre-wrap;
                padding: 10px;
                background: rgba(var(--mode),.2);
                border-radius: 10px;
                box-sizing: border-box;
            }
            .intro a {
                color: var(--mode-text);
                text-decoration: none;
                cursor: pointer;
            }

            .reg_info {
                display: none;
            }
            #reg_table {
                height: 0px;
                margin-top: 20px;
            }
            #reg_table tr {
                opacity: 0;
            }

            <?php if (isMobile($userAgent) == false) {?>
            .center_form {
                min-width: 40%;
                color: var(--mode-text);
                border-radius: 20px;
                box-sizing: border-box;
            }
            <?php } else {?>
            .center_form {
                width: calc(100% - 20px);
                margin: 0 auto;
                margin-top: 5px;
                color: var(--mode-text);
                box-sizing: border-box;
            }
            <?php }?>
            .center_form .form {
                width: auto;
                padding: 20px;
                background: rgba(var(--mode),.4);
                border-radius: 20px;
                box-sizing: border-box;
                backdrop-filter: blur(5px);
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
                background: rgba(var(--mode),.1);
                box-sizing: border-box;
            }
            .center_form .form .login input::placeholder,
            .center_form .form .register input::placeholder {
                color: var(--mode-placeholder);
            }
            .center_form .form .register input[type="number"] {
                -moz-appearance: textfield; /* Firefox */
            }

            .center_form .form .register input[type="date"]::-webkit-calendar-picker-indicator {
                position: absolute;
                width: 90%;
                height: 35px;
                margin-top: 1px;
                margin-left: -40px;
                opacity: 0;
                cursor: pointer;
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
                width: 0;
                height: 40px;
                margin: 0;
                padding: 0;
                padding-right: 30px;
                background: none;
                box-sizing: border-box;
                appearance: none;
                cursor: pointer;
            }
            .center_form .form .login input[type=checkbox]::before,
            .center_form .form .register input[type=checkbox]::before {
                content: "";
                display: block;
                width: 15px;
                height: 15px;
                margin-top: 12px;
                padding: 0;
                background: rgba(var(--mode),.2);
                border-radius: 5px;
                box-sizing: border-box;
            }
            .center_form .form .login input[type=checkbox]:checked::before,
            .center_form .form .register input[type=checkbox]:checked::before {
                content: "\2713";
                text-align: center;
                line-height: 15px;
                color: var(--mode-text);
            }
            label[for=login-remember] {
                font-size: 12px;
            }

            .center_form .form .login label,
            .center_form .form .register label {
                float: left;
                line-height: 40px;
                cursor: pointer;
            }

            .password-strength progress {
                border-radius: 20px;
                overflow: hidden;
            }
            .password-strength progress {
                width: 100%;
            }

            .center_form .form .password-criteria {
                padding-bottom: 10px;
                font-size: 12px;
            }
            .center_form .form .password-criteria i {
                float: none;
                color: #ff4545 !important;
            }
            .center_form .form .password-criteria i.ok {
                color: #6aff45 !important;
            }

            .center_form .form .login button {
                float: right;
                width: auto;
                margin-left: auto;
                padding: 10px 20px;
                font-size: 18px;
                color: var(--mode-text);
                background: rgba(var(--mode),.2);
                border-radius: 10px;
                border: none;
                overflow: hidden;
            }
            .center_form .form .login button i {
                line-height: 20px;
                float: none;
            }
            .center_form .form .login button:hover {
                background: var(--mode-placeholder) !important;
            }
            .center_form .form .login .show_qr_login {
                cursor: pointer;
            }
            .center_form .form .login .show_qr_login span {
                padding-right: 30px;
                box-sizing: border-box;
            }
            .center_form .form .login .show_qr_login i {
                float: right;
                margin-top: -13px;
                font-size: 18px;
            }
            .center_form .form .login .qr_login {
                width: 400px;
                display: none;
                justify-content: space-between;
            }
            .center_form .form .login .qr_login .qr_login_text {
                width: 50%;
            }
            .center_form .form .login .qr_login .qr_login_text p {
                padding-right: 10px;
            }
            .center_form .form .login .qr_login .qr_login_text img {
                height: 40px;
            }
            .center_form .form .login .qr_login .qr_login_text .btn {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-right: 20px;
                padding: 10px;
                color: var(--mode-text);
                background: rgba(var(--mode),.2);
                border: none;
                border-radius: 10px;
                cursor: pointer;
            }
            .center_form .form .login .qr_login .qr_login_text .btn i {
                height: 20px;
                line-height: 20px;
                margin: 0;
                padding: 0;
            }
            .center_form .form .login .qr_login .qr_login_img {
                width: 50%;
                padding: 10px;
                background: white;
                border-radius: 10px;
                box-sizing: border-box;
                overflow: hidden;
            }
            .center_form .form .login .qr_login .qr_login_img img {
                width: 100%;
                height: auto;
                max-height: 100%;
                object-fit: cover;
                object-position: center;
            }
            .center_form .form .login .normal_login {
                vertical-align: top;
            }
            .center_form .form .register input[type=submit] {
                float: right;
                width: 100%;
                padding: 0 20px;
                font-size: 18px;
                background: rgba(var(--mode-invert),.2);
                border: none;
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
                background: rgba(var(--mode-invert),.1);
                border: none;
            }
            .center_form .links {
                width: 100%;
            }
            .center_form .links span {
                padding: 10px;
                font-size: 12px;
                background: rgba(var(--mode),.2);
                border-radius: 10px;
            }
            .center_form .links span:hover {
                color: rgba(200,200,200,1);
            }
            
            .log-button,
            .reg-button {
                backdrop-filter: blur(5px);
            }
            <?php if (isMobile($userAgent) == false) {?>
            .log-button,
            .reg-button {
                display: flex;
                width: auto;
                margin-top: 10px;
                text-align: center;
                background: rgba(var(--mode),.4);
                box-sizing: border-box;
                border-radius: 20px;
                cursor: pointer;
            }
            .log-button span,
            .reg-button span {
                width: 100%;
                padding: 15px;
                border-radius: 20px;
                overflow: hidden;
                transition: transform .3s;
            }
            <?php } else {?>
            .log-button,
            .reg-button {
                display: flex;
                flex-direction: column;
                gap: 5px;
                width: auto;
                margin-top: 5px;
                text-align: center;
            }
            .log-button span,
            .reg-button span {
                width: 100%;
                padding: 15px;
                background: rgba(var(--mode),.4);
                box-sizing: border-box;
                border-radius: 20px;
                overflow: hidden;
                transition: transform .3s;
            }
            .log-button span:nth-child(1) {
                font-size: 24px;
            }
            .log-button span:nth-child(2) {
                font-size: 14px;
            }
            <?php }?>
            .log-button span:hover,
            .reg-button span:hover {
                background: rgba(var(--mode),.2);
                cursor: pointer;
                transform: scale(1.1);
            }

            <?php if (isMobile($userAgent) == false) {
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
                color: var(--mode-text);
                background: linear-gradient(0deg, rgba(var(--dark),1) 0%, rgba(var(--mode),1) 100%);
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
                color: var(--mode-text);
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
            #email-check {
                display: none !important;
            }
            <?php if (isMobile($userAgent) == false) {?>
            .reg-packs {
                width: 800px;
                margin-top: 100px;
                margin-left: 50%;
                transform: translateX(-50%);
                text-align: center;
                color: var(--mode-text);
            }
            .reg-packs button {
                width: auto;
                height: 50px;
                margin: 3px;
                margin-top: 20px;
                padding: 0 50px;
                color: var(--mode-text);
                background: rgba(var(--mode-invert),.1);
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
                background: rgba(var(--mode-invert),.1);
                border-radius: 30px;
                backdrop-filter: blur(5px);
                transition: width .5s;
            }
            .reg-pack-box {
                height: calc(100% - 20px);
                margin: 10px;
                padding: 5px;
                border: 1px solid rgba(var(--mode-invert),.2);
                border-radius: 20px;
                box-sizing: border-box;
                transition: background .2s;
            }
            .reg-pack-box button {
                width: 50%;
                min-width: 100px;
                height: 40px;
                color: var(--mode-text);
                background: rgba(var(--mode-invert),.1);
                border: none;
                border-radius: 10px;
            }
            .reg-pack-box button:hover {
                background: rgba(var(--mode-invert),.2);
                cursor: pointer;
            }
            .reg-pack-box p button {
                transform: translateY(-50px);
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

            #reg-pack-custom {
                width: 610px;
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
            .rpbcb label{
                width: 50%;
                cursor: pointer;
            }
            .rpbcb table {
                width: 100%;
                margin-top: 30px;
            }
            .rpbcb table tr {
                width: 100%;
                text-align: left;
            }
            .rpbcb table tr:hover,
            .rpbcb table tr td:hover tr,
            .rpbcb table tr td input:hover tr,
            .rpbcb table tr td span:hover tr {
                background: rgba(var(--mode-invert),.1);
                cursor: pointer;
            }
            <?php } else {?>
            .reg-packs {
                width: 100%;
                margin-top: 75px;
                text-align: center;
                color: var(--mode-text);
            }
            .reg-packs button {
                width: auto;
                height: 50px;
                margin: 3px;
                margin: 20px 0;
                padding: 0 50px;
                color: var(--mode-text);
                background: rgba(var(--mode-invert),.1);
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
                background: rgba(var(--mode-invert),.1);
                border-radius: 30px;
                backdrop-filter: blur(5px);
                transition: width .5s;
            }
            .reg-pack-box {
                height: calc(100% - 20px);
                margin: 10px;
                padding: 5px;
                border: 1px solid rgba(var(--mode-invert),.2);
                border-radius: 20px;
                box-sizing: border-box;
                transition: background .2s;
            }
            .reg-pack-box button {
                width: 50%;
                min-width: 100px;
                height: 40px;
                color: var(--mode-text);
                background: rgba(var(--mode-invert),.1);
                border: none;
                border-radius: 10px;
            }
            .reg-pack-box button:hover {
                background: rgba(var(--mode-invert),.2);
                cursor: pointer;
            }
            .reg-pack-box p button {
                transform: translateY(-50px);
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

            #reg-pack-custom button {
                width: auto;
                min-width: 100px;
                height: 40px;
                color: var(--mode-text);
                background: rgba(var(--mode-invert),.1);
                border: none;
                border-radius: 10px;
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
            .rpbcb label{
                width: 50%;
                cursor: pointer;
            }
            .rpbcb table {
                width: 100%;
                margin-top: 100px;
            }
            .rpbcb table tr {
                width: 100%;
                text-align: left;
            }
            .rpbcb table tr:hover,
            .rpbcb table tr td:hover tr,
            .rpbcb table tr td input:hover tr,
            .rpbcb table tr td span:hover tr {
                background: rgba(var(--mode-invert),.1);
                cursor: pointer;
            }
            <?php }?>

            #reg-pack-custom p button {
                transform: translateY(0px);
            }
            .rpbcb table tr td {
                padding: 10px;
                text-align: center;
                background: rgba(var(--mode),.2);
                box-sizing: border-box;
                border-radius: 10px;
            }
            .rpbcb table tr td:nth-child(1) {
                display: none;
            }
            
            /** Header */
            .header {
                display: flex;
                width: 100%;
                height: 75px;
                transition: all .3s;
                z-index: 10;
            }
            <?php if (isMobile($userAgent) == true) { if (isset($_SESSION['user'])) {?>
            .header {
                position: fixed;
                top: 0;
                background: rgba(var(--mode),.2);
                backdrop-filter: blur(5px);
                box-shadow: 0px -20px 10px 30px rgba(var(--mode),.2);
            }
            <?php } else {?>
            .header {
                position: absolute;
                top: 0;
            }
            <?php }} else {?>
            .header {
                position: fixed;
                top: 0;
            }
            <?php }?>

            <?php if (isMobile($userAgent) == false) { if (isset($_SESSION['user'])) {?>
            .header .top-left {
                display: flex;
                width: 33.33%;
            }
            .header .top-left .logo img {
                width: auto;
                height: 50px;
                padding: 10px 20px;
            }
            <?php } else {?>
            .header .top-left {
                display: flex;
                width: 100%;
            }
            .header .top-left .logo img {
                width: auto;
                height: 50px;
                padding: 10px 20px;
            }
            <?php }} else {?>
            .header .top-left {
                display: flex;
                width: 100%;
            }
            .header .top-left .logo img {
                width: auto;
                height: 100%;
                margin: 0 auto;
            }
            <?php }?>

            .header .top-left .logo {
                display: flex;
                text-align: left;
                color: var(--mode-text);
            }
            @media only screen and (min-width: 681px) {
                .header .top-left .logo {
                    min-width: 300px;
                    max-width: 33.33%;
                }
                .header .top-left .logo-name {
                    width: auto;
                    color: white;
                    font-size: 12px;
                }
            }
            @media only screen and (max-width: 680px) {
                .header .top-left .logo {
                    width: 90px;
                    overflow: hidden;
                }
                .header .top-left .logo-name {
                    display: none;
                }
            }
            .header .top-left .logo-name p {
                margin: 20px 0 5px 0;
                font-size: 24px;
                font-weight: bold;
            }
            .header .top-left .logo-name p span {
                font-size: 12px;
            }
            <?php if (isMobile($userAgent) == false) {?>
            .header .new_post_button {
                width: 33.33%;
                height: 45px;
                line-height: 45px;
                margin: 15px 0;
                padding: 0 20px;
                color: var(--mode-text);
                text-align: center;
                backdrop-filter: blur(5px);
                border: 1px solid rgba(var(--mode-invert),.2);
                border-radius: 50px;
                box-sizing: border-box;
                cursor: pointer;
                overflow: hidden;
            }
            .new_post {
                position: fixed;
                top: 75px;
                left: 50%;
                width: calc(100% - 20px);
                max-width: 630px;
                transform: translateX(-50%);
                background: rgba(var(--mode),.6);
                backdrop-filter: blur(5px);
                border-radius: 20px;
                box-sizing: border-box;
                box-shadow: 0px 5px 10px 0px rgba(var(--mode),.5);
                overflow: hidden;
                z-index: 5;
            }
            .new_post .create_post {
                width: 100%;
                margin: 0 auto;
                padding: 10px;
                color: black;
                box-sizing: border-box;
            }
            .create_post textarea::placeholder {
                color: var(--mode-text);
            }
            .create_post textarea::-webkit-scrollbar {
                display: none;
            }
            .create_post_actions_top {
                display: flex;
                justify-content: space-between;
                width: 100%;
                color: var(--mode-text);
                overflow: hidden;
            }
            .create_post_user {
                display: flex;
                justify-content: space-between;
            }
            .create_post_user img {
                width: 50px;
                height: 50px;
                border-radius: 25px;
                object-fit: cover;
            }
            .create_post_user div {
                line-height: 50px;
                padding: 0 10px;
                box-sizing: border-box;
            }
            .create_post_actions_top i {
                width: 50px;
                height: 50px;
                line-height: 50px;
                text-align: center;
                cursor: pointer;
            }
            .create_post textarea {
                min-width: calc(100% - 100px);
                max-width: calc(100% - 100px);
                min-height: 50px;
                max-height: 400px;
                padding: 10px;
                color: var(--mode-text);
                background: none;
                border: none;
                outline: none;
                box-sizing: border-box;
                resize: none;
            }
            .new_post_files {
                display: flex;
                justify-content: left;
                width: 100%;
                max-height: 200px;
                margin-top: 10px;
                padding: 0 10px;
                padding-top: 10px;
                box-sizing: border-box;
                overflow: scroll;
            }
            .new_post_files img {
                max-width: 100px;
                max-height: 200px;
                margin: 3px 0;
                border-radius: 10px;
                transition: all .5s;
            }
            .new_post_files img:hover {
                max-width: 100%;
                max-height: 200px;
            }
            .create_post_actions_bottom {
                display: flex;
                justify-content: space-between;
                margin-top: 20px;
                color: var(--mode-text);
            }
            .create_post_actions_bottom span select {
                appearance: none;
                padding: 10px;
                color: black;
                background: white;
                border: none;
                border-radius: 10px;
                outline: none;
                cursor: pointer;
            }
            .create_post_actions_bottom span select::before,
            .create_post_actions_bottom span select::after {
                color: black;
            }
            .create_post_actions_bottom .share {
                width: 50px;
                padding: 0;
                text-align: center;
                border-radius: 40px;
                justify-self: right;
                cursor: pointer;
            }
            .create_post_actions_bottom .share:hover {
                background: rgba(var(--mode-invert),.3);
            }
            .create_post_actions_bottom i {
                line-height: 50px;
                padding: 0 10px;
            }

            .search_result {
                width: calc(100% - 20px);
                height: auto;
                margin: 0 10px;
                padding: 10px;
                background: rgba(var(--mode),.1);
                border-radius: 10px;
                box-sizing: border-box;
            }
            .search_result h3 {
                margin: 0;
                padding: 10px;
                text-align: center;
            }
            .search_res_user {
                display: flex;
                width: 100%;
                height: 50px;
                line-height: 50px;
                border-radius: 10px;
                box-sizing: border-box;
                cursor: pointer;
            }
            .search_res_user:hover {
                background: rgba(var(--mode-invert),.2);
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

            @media (prefers-color-scheme: light) {
                .new_post {
                    background: linear-gradient(to top, #48c6ef 0%, #6f86d6 100%);
                }
            }
            @media (prefers-color-scheme: dark) {
                .new_post {
                    background: linear-gradient(to top, #243B55 0%, #141E30 100%);
                }
            }
            .new_post {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                padding-top: 75px;
                backdrop-filter: blur(5px);
                box-sizing: border-box;
                box-shadow: 0px 5px 10px 0px rgba(var(--mode),.5);
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
            .create_post textarea {
                width: 100%;
                min-width: 100%;
                max-width: 100%;
                min-height: 100px;
                max-height: 600px;
                margin: 0 auto;
                padding: 10px;
                padding-right: 40px;
                color: var(--mode-text);
                background: none;
                border: none;
                outline: none;
                box-sizing: border-box;
            }
            .new_post_files {
                display: flex;
                width: 100%;
                padding: 0 10px;
                padding-top: 10px;
            }
            .new_post_files img {
                max-width: 100px;
                max-height: 100px;
                border-radius: 0;
            }

            .create_post textarea::placeholder {
                color: var(--mode-text);
            }
            .create_post textarea::-webkit-scrollbar {
                display: none;
            }
            .create_post_actions_top {
                display: flex;
                justify-content: space-between;
                width: 100%;
                color: var(--mode-text);
                overflow: hidden;
            }
            .create_post_user {
                display: flex;
                justify-content: space-between;
            }
            .create_post_user img {
                width: 50px;
                height: 50px;
                border-radius: 25px;
                object-fit: cover;
            }
            .create_post_user div {
                line-height: 50px;
                padding: 0 10px;
                box-sizing: border-box;
            }
            .create_post_actions_top i {
                width: 50px;
                height: 50px;
                line-height: 50px;
                text-align: center;
                cursor: pointer;
            }
            .create_post_actions_top span {
                padding: 0 10px;
                color: var(--mode-text);
            }
            .create_post_actions span select {
                color: var(--mode-text);
                background: none;
                outline: none;
                border: none;
            }
            .create_post_actions span select option {
                color: black;
            }
             .create_post_actions_top span.close {
                width: 40px;
                margin-right: -10px;
                text-align: center;
                border-left: 1px solid white;
                cursor: pointer;
            }
            .create_post_actions_bottom {
                display: flex;
                justify-content: space-between;
                margin-top: 20px;
                color: var(--mode-text);
            }
            .create_post_actions_bottom .share {
                width: 40px;
                line-height: 40px;
                margin-top: -10px;
                padding: 0;
                text-align: center;
                border-radius: 40px;
                justify-self: right;
                cursor: pointer;
            }
            .create_post_actions_bottom .share:hover {
                background: rgba(var(--mode-invert),.3);
            }
            .create_post_actions_bottom label,
            .create_post_actions_bottom span {
                cursor: pointer;
            }
            .create_post_actions_bottom i {
                padding: 0 10px;
            }

            .header .create_post {
                position: relative;
                width: 100%;
                padding: 20px;
                background: rgba(var(--mode),.8);
                box-sizing: border-box;
            }
            .header .create_post textarea {
                width: 100%;
                height: 40%;
                padding: 10px;
                padding-right: 40px;
                color: var(--mode-text);
                background: none;
                border: none;
                outline: none;
                resize: none;
                box-sizing: border-box;
            }
            .header .create_post textarea::placeholder {
                color: var(--mode-text);
            }
            .header .create_post i {
                width: 40px;
                line-height: 40px;
                margin-left: calc(100% - 40px);
                padding: 0 10px;
                color: var(--mode-text);
            }

            .search_result {
                width: 100%;
                height: calc(100% - 250px);
                margin-top: 20px;
                padding: 10px 20px;
                background: rgba(var(--mode-invert),.1);
                border-radius: 20px;
                box-sizing: border-box;
                overflow: auto;
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
                background: rgba(var(--mode-invert),.2);
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
            <?php }?>
            .header .top {
                display: flex;
                justify-content: space-evenly;
                color: var(--mode-text);
            }
            <?php if (isMobile($userAgent) == false) {?>
            .header .top {
                min-width: 300px;
                max-width: 33%;
                margin-left: auto;
            }
            <?php } else {?>
            .header .top {
                width: 100%;
            }
            <?php }?>
            <?php if (isMobile($userAgent) == false) {?>
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
                font-size: 10px;
                color: orange;
                opacity: 0;
            }
            .notification_alert.nat {
                margin-left: 30px;
                margin-top: -5px;
            }
            .notification_alert.nab {
                margin-left: 18px;
                margin-top: -7px;
            }
            <?php if (isMobile($userAgent) == false) {?>
            .notifications {
                position: fixed;
                top: 75px;
                right: 250px;
                width: 30%;
                max-width: 300px;
                max-height: calc(100% - 300px);
                color: var(--mode-text);
                background: rgba(var(--mode),.3);
                border-radius: 10px 0 10px 10px;
                backdrop-filter: blur(5px);
                box-shadow: 10px 10px 23px -5px rgba(var(--mode),0.6);
                z-index: 11;
            }
            <?php } else {?>
            .notifications {
                position: fixed;
                top: 75px;
                left: 10px;
                width: calc(100% - 20px);
                height: calc(100% - 165px);
                color: var(--mode-text);
                background: rgba(var(--mode),.5);
                border-radius: 10px;
                backdrop-filter: blur(5px);
                box-shadow: 10px 10px 23px -5px rgba(var(--mode),0.6);
                z-index: 11;
            }
            <?php }?>
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
                background: rgba(var(--mode),.05);
                border-radius: 5px;
                transition: background .5s;
            }
            .noti:hover {
                background: rgba(var(--mode),.1);
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
                font-size: 14px;
            }
            .noti-content span {
                font-size: 12px;
            }
            .noti-content span a {
                color: rgba(var(--mode-text),1);
            }
            .noti-content span i {
                padding: 0 10px;
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
                color: var(--mode-text);
                background: rgba(var(--mode),.2);
                backdrop-filter: blur(5px);
                box-shadow: 10px 10px 23px -5px rgba(var(--mode),0.6);
                border-radius: 20px;
                box-sizing: border-box;
                overflow: auto;
                z-index: 15;
            }
            <?php if (isMobile($userAgent) == false) {?>
            .notification-window {
                left: 50%;
                width: 600px;
                max-height: 80%;
                margin-left: -300px;
            }
            <?php } else {?>
            .notification-window {
                left: 10px;
                width: calc(100% - 20px);
                max-height: 75%;
            }
            <?php }?>
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
                background: rgba(var(--mode),.1);
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

            <?php if (isMobile($userAgent) == false) {?>
            .search {
                height: 50px;
                padding: 0 10px;
            }
            .search i {
                position: absolute;
                right: 25px;
                padding: 10px;
                cursor: pointer;
                z-index: 1;
            }
            .search input {
                display: block;
                width: 100%;
                padding: 10px 20px;
                padding-right: 40px;
                color: var(--mode-text);
                background: none;
                border: 1px solid rgba(var(--mode-invert),.5);
                backdrop-filter: blur(5px);
                border-radius: 40px;
                box-sizing: border-box;
                outline: none;
            }
            .search input::placeholder {
                color: white;
            }
            <?php } else {?>
            .mobile-search {
                position: fixed;
                top: 0;
                left: 0;
                transform: translateY(-155px);
                width: 100%;
                padding: 20px;
                padding-top: 95px;
                color: var(--mode-text);
                background: rgba(var(--mode),.3);
                box-sizing: border-box;
                backdrop-filter: blur(5px);
                border-bottom-left-radius: 20px;
                border-bottom-right-radius: 20px;
                transition: all .2s ease-in-out;
                z-index: 3;
            }
            .search {
                width: 100%;
                background: rgba(var(--mode),.3);
                border-radius: 40px;
                box-sizing: border-box;
                backdrop-filter: blur(5px);
            }
            .search i {
                position: absolute;
                width: 20px !important;
                margin: 10px;
                padding: 0;
            }
            .search input {
                width: 100%;
                padding: 10px 15px;
                padding-left: 40px;
                box-sizing: border-box;
                color: var(--mode-text);
                background: rgba(var(--mode),0);
                border: none;
                outline: none;
                transition: display .5s;
            }
            .search input::placeholder {
                color: white;
            }
            <?php }?>
            <?php if (isMobile($userAgent) == false) {?>
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
                width: 50px;
                max-width: 50px;
                height: 50px;
                max-height: 50px;
                border-radius: 50px;
                object-fit: cover;
            }
            .header .top .user-nav {
                height: 75px;
            }
            <?php if (isMobile($userAgent) == false) {?>
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
                top: 75px;
                color: var(--mode-text);
                box-shadow: 0 2px 2px rgba(var(--mode),.05);
                box-sizing: border-box;
                backdrop-filter: blur(5px);
            }
            <?php if (isMobile($userAgent) == false) {?>
            .user-dropdown {
                position: absolute;
                display: none;
                right: 40px;
                padding: 10px;
                background: rgba(var(--mode),.2);
                border-radius: 20px 0 20px 20px;
                z-index: 10;
            }
            <?php } else {?>
            .user-dropdown {
                position: fixed;
                right: 0;
                padding: 10px;
                background: rgba(var(--mode),.2);
                border-radius: 20px 0px 0px 20px;
                transform: translateX(100%);
                transition: transform .5s;
                box-sizing: border-box;
                z-index: 10;
            }
            <?php }?>
            .user-dropdown ul {
                list-style: none;
                margin: 0;
                padding: 0;
            }
            .user-dropdown ul li {
                display: flex;
                justify-content: space-between;
            }
            <?php if (isMobile($userAgent) == false) {?>
            .user-dropdown ul li {
                padding: 10px;
                cursor: pointer;
            }
            .user-dropdown ul li:hover {
                background: rgba(var(--mode),.1);
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
                color: var(--mode-text);
                background: linear-gradient(0deg, rgba(var(--greyblue),1) 0%, rgba(var(--lightblue),1) 100%);
            }

            .login_qr {
                float: right;
                width: 20px;
                height: 20px;
                margin-top: -40px;
                margin-right: -10px;
                font-size: 20px;
                cursor: pointer;
                transition: transform .3s;
            }
            .login_qr:hover {
                transform: scale(2.5) translateX(-5px) translateY(0px);
            }
            .login_qr i {
                width: 100%;
                height: 100%;
                margin: 0;
                padding: 0;
                color: var(--mode-text);
            }

            <?php if (isMobile($userAgent) == false) {?>
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
                color: var(--mode-text);
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
                background: rgba(var(--mode),.8);
                backdrop-filter: blur(5px); 
            }
            <?php } else {?>
            .image_viewer {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                border-radius: 20px;
                box-sizing: border-box;
                overflow: hidden;
                z-index: 12;
            }
            .image_viewer .image_box {
                width: 100%;
                height: 100%;
                background: rgba(var(--mode),.8);
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
                color: var(--mode-text);
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
                background: rgba(var(--mode-invert),.1);
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

            /** POST */
            <?php if (isMobile($userAgent) == false) {?>
            .posts {
                width: 100%;
                max-width: 650px;
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
                margin-bottom: 10px;
                padding-bottom: 2px;
                backdrop-filter: blur(5px);
            }
            .post_body {
                color: var(--mode-text);
                background: rgba(var(--mode),.7);
                border-radius: 20px;
                overflow: hidden;
            }
            .post_body a {
                color: var(--mode-text);
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
                text-align: center;
                object-fit: cover;
            }
            .post_user_name {
                line-height: 50px;
                padding: 10px 0;
            }
            .post_date {
                margin-left: 50px;
                padding: 10px 20px;
                font-size: 12px;
            }
            .post_actions {
                position: relative;
                height: 50px;
                padding: 20px;
                box-sizing: border-box;
                cursor: pointer;
            }
            .post_action_list {
                flex-direction: column;
                background: rgba(var(--mode),.3);
                border-radius: 10px 3px 10px 10px;
                overflow: hidden;
            }
            .post_action {
                display: flex;
                padding: 7px;
                color: var(--mode-text);
            }
            .post_action i {
                margin-top: 10px;
            }
            .post_action span {
                position: absolute;
                width: 100px;
                opacity: 0;
                text-align: right;
                margin-top: 8px;
                transition: all .5s;
            }
            .post_action:hover span {
                transform: translateX(-120px);
                opacity: 1;
            }
            .post_action:hover {
                background: rgba(var(--mode-invert),.2);
                cursor: pointer;
            }
            .post_content {
                padding: 10px 20px;
                word-break: break-all;
                overflow: hidden;
            }
            .post_link_preview {
                display: flex;
                justify-content: left;
                gap: 10px;
                width: calc(100% - 20px);
                height: 100px;
                margin: 0 auto;
                margin-top: 10px;
                border-radius: 20px;
                overflow: auto;
                background: rgba(var(--mode-invert),.1);
                overflow: hidden;
                cursor: pointer;
            }
            .post_link_preview_image {
                min-width: 100px;
                width: auto;
                max-width: 300px;
                min-height: 100px;
                height: auto;
                max-height: 100px;
                padding: 0;
                text-align: center;
                border-radius: 5px;
                box-sizing: border-box;
                overflow: hidden;
            }
            .post_link_preview_image img {
                width: auto;
                height: 100%;
                object-fit: cover;
            }
            .post_link_preview_icon {
                position: absolute;
                width: 20px;
                height: 20px;
                margin-top: 80px;
                margin-left: 80px;
                padding: 0;
                background: black;
                box-sizing: border-box;
                border-radius: 5px;
                overflow: hidden;
            }
            .post_link_preview_icon img {
                width: 100%;
                height: 100%;
                object-fit: contain;
            }
            .post_link_preview_info {
                box-sizing: border-box;
                overflow: hidden;
            }
            .post_link_preview_title {
                padding: 10px;
                font-size: 14px;
                font-weight: bold;
            }
            .post_link_preview_description {
                padding: 10px;
                font-size: 12px;
            }
            .post_full {
                margin-bottom: 10px;
                padding-bottom: 30px;
                border-bottom: 1px solid rgba(var(--mode),.05);
            }
            .post_website {
                width: calc(100% - 20px);
                max-height: 100px;
                margin: 5px 10px;
                border-radius: 10px;
                box-sizing: border-box;
                overflow: hidden;
            }
            .post_website img {
                max-width: 90px;
                max-height: 90px;
                margin: 10px;
                border-radius: 10px;
                box-sizing: border-box;
                overflow: hidden;
            }
            .post_links iframe {
                width: calc(100% - 20px);
                margin: 0 10px;
                aspect-ratio: 16/9;
                border: none;
                border-radius: 10px;
                box-sizing: border-box;
            }
            .post_links video {
                width: auto;
                max-height: 250px;
                margin: 0 auto;
                padding: 5px;
                border: none;
                border-radius: 10px;
                box-sizing: border-box;
                box-shadow: none;
                outline: none;
            }
            .post_uploads {
                width: calc(100% - 20px);
                max-height: 300px;
                margin: 0 auto;
                border-radius: 0 0 10px 10px;
                box-sizing: border-box;
                overflow: hidden;
                transition: all .3s;
                cursor: pointer;
            }
            .post_expand {
                position: relative;
                width: calc(100% - 20px);
                height: 50px;
                line-height: 70px;
                margin: 0 auto;
                margin-top: -50px;
                margin-bottom: 0;
                text-align: center;
                background: linear-gradient(0deg, rgba(var(--mode),1) 0%, rgba(var(--mode),0) 100%);
                border-radius: 0 0 10px 10px;
                box-sizing: border-box;
                cursor: pointer;
            }
            .post_gallery {
                display: flex;
                justify-content: center;
                gap: 10px;
                overflow: auto;
            }
            .post_gallery img {
                width: 500px;
                height: 500px;
                border-radius: 10px;
            }
            .post_comments {
                width: 100%;
                max-height: 320px;
                padding: 0 10px;
                padding-left: 10px;
                box-sizing: border-box;
            }
            .post_body i {
                display: flex;
                justify-content: right;
                font-size: 12px;
                margin-bottom: 10px;
                padding: 0 10px;
            }
            .post_comment_count {
                display: flex;
                justify-content: right;
                align-items: center;
                height: 30px;
                padding: 0;
            }
            .post_comment_count i {
                margin: 0;
                padding-left: 10px;
            }
            .post_comment_new {
                display: flex;
                align-items: center;
                margin-bottom: 10px;
            }
            .post_comment_new_content input[type=text] {
                width: 100%;
                padding-right: 50px;
                color: var(--mode-text);
                background: none;
                border-radius: 10px;
                border: none;
                outline: none;
                box-sizing: border-box;
            }
            <?php if (isMobile($userAgent) == false) {?>
            .post_comment_new_user span {
                width: 200px;
                line-height: 30px;
            }
            .post_comment_new_content {
                width: 100%;
                line-height: 30px;
                margin-right: 10px;
                padding: 0 10px;
                background: rgba(var(--mode-invert),.1);
                border-radius: 10px;
                box-sizing: border-box;
                overflow: auto;
                word-break: break-all;
            }
            .post_comment_new_actions {
                width: 30px;
                margin-left: -40px;
                justify-content: center;
            }
            <?php } else {?>
            .post_comment_new_user span {
                display: none;
            }
            .post_comment_new_content {
                width: 100%;
                line-height: 30px;
                padding: 0 10px;
                background: rgba(var(--mode-invert),.1);
                border-radius: 10px;
                box-sizing: border-box;
                overflow: auto;
                word-break: break-all;
            }
            .post_comment_new_actions {
                width: 30px;
                margin-left: -30px;
                justify-content: center;
            }
            <?php }?>
            .post_comment_new_actions .btn {
                width: 30px;
                padding: 10px 10px 0 10px;
                box-sizing: border-box;
                cursor: pointer;
            }

            
            .post_comment {
                width: 100%;
                margin-bottom: 10px;
            }
            .post_comment.me {
            }
            .post_comment_user {
                display: flex;
                justify-content: space-between;
                margin-top: 20px;
            }
            .post_comment_user_info {
                display: flex;
                justify-content: space-between;
                align-items: center;
                height: 30px;
            }
            .post_comment_user_avatar {
                min-width: 30px;
                max-width: 30px;
                height: 30px;
                margin-right: 10px;
                border-radius: 30px;
                overflow: hidden;
            }
            .post_comment_user_avatar img {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }
            .post_comment_user_info span {
                line-height: 30px;
            }
            <?php if (isMobile($userAgent) == false) {?>
            .post_comment_user_info span {
                width: auto;
            }
            .post_comment_user_actions {
                float: right;
                width: 30px;
            }
            .post_comment_user_actions .btn {
                width: 30px;
                padding: 10px 10px 0 10px;
                box-sizing: border-box;
                cursor: pointer;
            }
            .post_comment_content {
                line-height: 30px;
                margin: 0;
                padding: 0 10px;
                background: rgba(var(--mode-invert),.1);
                border-radius: 10px;
                box-sizing: border-box;
                overflow: auto;
                word-break: break-all;
            }
            <?php } else {?>
            .post_comment_user span {
                max-width: 200px;
            }
            .post_comment_content {
                width: 100%;
                line-height: 30px;
                padding: 0 10px;
                background: rgba(var(--mode-invert),.1);
                border-radius: 10px;
                box-sizing: border-box;
                overflow: auto;
                word-break: break-all;
            }
            <?php }?>

            .preview-container {
                display: flex;
                flex-direction: column;
                border: 1px solid #ddd;
                padding: 10px;
                width: 400px;
                font-family: Arial, sans-serif;
            }
            .preview-header {
                display: flex;
                align-items: center;
            }
            @media only screen and (min-width: 1240px) {
                .group-container {
                    max-width: calc(100% - 600px);
                }
            }
            .title-description {
                display: flex;
                flex-direction: column;
            }
            .title {
                font-size: 18px;
                font-weight: bold;
            }
            .description {
                font-size: 14px;
                color: #555;
            }
            .featured-image {
                width: 100%;
                margin-top: 10px;
            }

            /** Side menu */
            .side_menu_back {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(var(--mode),.5);
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
                color: var(--mode-text);
                background: rgba(var(--mode),.5);
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

            /** LEFT PANEL */

            /** Shortcuts */
            .shortcuts {
                background: rgba(var(--mode),.4);
                border-radius: 20px;
                backdrop-filter: blur(5px);
            }
            .shortcuts h3 {
                display: flex;
                justify-content: space-between;
                margin: 5px 10px;
                padding: 10px;
                font-size: 18px;
                border-radius: 10px;
            }
            .shortcuts i.beta {
                padding-top: 3px;
                font-size: 12px;
            }
            .shortcuts i {
                width: 25px;
                text-align: center;
                cursor: pointer;
            }
            .shortcut-browse {
                text-align: center;
                padding: 10px;
                border: 1px dashed rgba(var(--mode-invert),.2);
                border-radius: 10px;
            }
            .shortcut-browse:hover {
                background: rgba(var(--mode-invert),.3);
                cursor: pointer;
            }
            .shortcuts .shortcut {
                padding: 10px;
                background: rgba(var(--mode-invert),.1);
            }
            .shortcuts .shortcut:hover {
                background: rgba(var(--mode-invert),.3);
                cursor: pointer;
            }

            /** BETA Keys */
            #beta-keys {
                max-height: 100px;
                padding-bottom: 1px;
                text-align: center;
                overflow: auto;
            }
            .beta-key {
                padding: 0 10px;
                box-sizing: border-box;
            }

            /** Discord widget */
            .discord-widget {
                width: 100%;
            }
            .discord-widget #discordWidget {
                width: 100%;
                text-align: center;
                border: none;
                overflow: hidden;
            }

            /** Group list */
            .groups {
                width: 100%;
            }
            .shortcut-group {
                display: flex;
                justify-content: space-between;
                align-items: center;
                width: 100%;
                height: 40px;
                font-size: 14px;
                border-radius: 50px;
                box-sizing: border-box;
                transition: background .5s;
                overflow: hidden;
            }
            .shortcut-group:hover {
                background: rgba(var(--mode-invert),.1);
                cursor: pointer;
            }
            .group-icon {
                width: 30px;
                height: 30px;
                margin: 5px;
                margin-right: 10px;
                border-radius: 40px;
                overflow: hidden;
            }
            .group-icon img {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }
            .group-name {
                width: calc(100% - 80px);
                word-break: break-all;
                overflow: hidden;
            }
            .group-extra {
                line-height: 60px;
                padding-right: 10px;
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
                color: var(--mode-text);
                background: rgba(var(--mode),.1);
                border-radius: 10px;
                cursor: pointer;
            }
            .pb-intro:hover,
            .gb-intro:hover {
                color: var(--mode-text);
                background: rgba(var(--mode),.3);
            }

            /** Pages */
            .pages {
                width: 100%;
            }
            .shortcut-page {
                display: flex;
                justify-content: space-between;
                width: 100%;
                font-size: 14px;
                border-radius: 10px;
                box-sizing: border-box;
                transition: background .5s;
            }
            .shortcut-page:hover {
                background: rgba(var(--mode),.1);
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

            <?php if (isMobile($userAgent) == false) {?>
            .new-group-create {
                display: flex;
            }
            .new-group-create .left {
                width: 400px;
                padding: 10px 20px;
                color: var(--mode-text);
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
                color: var(--mode-text);
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
                color: var(--mode-text);
                background: rgba(var(--mode),.1);
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
                color: var(--mode-text);
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
                color: var(--mode-text);
                background: rgba(var(--mode),1);
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
                color: var(--mode-text);
                background: rgba(var(--mode),1);
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

            /** Music list */
            .music {
                width: 100%;
            }

            /** Event list */
            .events {
                width: 100%;
            }

            /** Game list */
            .gaming {
                width: 100%;
            }

            /** Market list */
            .markets {
                width: 100%;
            }

            /** Console */
            .terminal {
                position: relative;
                width: 100%;
                padding-bottom: 10px;
            }
            .terminal span {
                position: absolute;
                right: 0;
                margin-top: 5px;
                margin-right: 15px;
                color: #555;
                font-size: 12px;
            }
            #console {
                width: calc(100% - 20px);
                height: auto;
                max-height: 200px;
                margin: 0 auto;
                padding: 10px;
                padding-bottom: 30px;
                color: var(--mode-text);
                font-size: 12px;
                background: rgba(var(--mode-invert),.05);
                border-radius: 10px;
                box-sizing: border-box;
                word-break: break-all;
                overflow: auto;
            }
            #console p {
                width: 100%;
                margin: 0;
                padding: 5px 0;
            }
            .con_input {
                margin-top: -20px;
                padding-left: 10px;
            }
            .con_input input {
                width: calc(100% - 10px);
                height: 20px;
                padding: 0 10px;
                color: var(--mode-text);
                background: none;
                box-sizing: border-box;
                border: none;
                outline: none;
            }
            .con_input {
                position: relative;
            }
            .con_input::before {
                content: ">";
                position: absolute;
                left: 15px;
                top: 3px;
                color: var(--mode-text);
            }
            .con_input input {
                padding-left: 25px;
            }
            .con_input input::placeholder {
                color: var(--mode-text);
            }

            /** END OF LEFT PANEL */

            /** RIGHT PANEL */

            /** Friend list */
            .friend-list {
                width: 100%;
                height: 100%;
                max-height: 500px;
                padding: 0 10px;
                color: var(--mode-text);
                box-sizing: border-box;
            }
            .friend-list h3 {
                padding: 10px 20px;
                background: rgba(var(--mode),.41);
                backdrop-filter: blur(5px);
                border-radius: 10px;
            }
            .friend {
                width: 100%;
                height: 60px;
                margin-bottom: 3px;
                font-size: 14px;
                transition: background .5s, height .2s;
                overflow: hidden;
                backdrop-filter: blur(5px);
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
                background: rgba(var(--mode),.05);
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
                background: rgba(var(--mode),.1);
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
                background: rgba(var(--mode),.1);
            }

            .friend-referral {
                width: 100%;
                height: 35px;
                margin-top: 20px;
                padding: 10px;
                background: rgba(var(--mode),.4);
                backdrop-filter: blur(5px);
                border-radius: 10px;
                box-sizing: border-box;
                overflow: hidden;
            }
            .friend-referral div {
                cursor: pointer;
            }
            .friend-referral i {
                float: right;
            }
            .friend-referral i:hover {
                transform: scale(1.1);
            }
            .fr_code {
                margin: 10px 0;
                padding: 10px;
                text-transform: uppercase;
                text-align: center;
                background: rgba(var(--mode),.1);
                border-radius: 10px;
            }
            .fr_info {
                height: auto;
                padding: 10px;
                font-size: 12px;
                cursor: pointer;
                overflow: hidden;
            }
            .fr_info i {
                float: none;
            }
            .fr_info span {
                float: right;
            }
            .fr_info_text {
                height: 0px;
                overflow: hidden;
            }
            .fr_info_text a {
                color: var(--mode-text);
            }

            /** END OF RIGHT PANEL */

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
                color: var(--mode-text);
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
                box-sizing: border-box;
            }
            .chat_message.me {
                justify-content: flex-end;
            }
            .chat_message_options {
                position: relative;
                width: 10px;
                padding: 10px;
                text-align: left;
                color: var(--mode-text);
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
                background: rgba(var(--mode-invert),.3);
                backdrop-filter: blur(5px);
                box-shadow: 10px 10px 23px -5px rgba(var(--mode),0.6);
                border-radius: 0 10px 10px 10px;
                overflow: hidden;
            }
            .chat_action {
                display: flex;
                padding: 10px 20px;
                color: var(--mode-text);
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
                border-radius: 25px;
                overflow: hidden;
            }
            .me .chat_user {
                margin-right: 0px;
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
                background: rgba(var(--mode),.2);
                border-radius: 20px;
                box-sizing: border-box;
            }
            .me .chat_text {
                margin-right: 10px;
            }
            .chat_message_box .chat_text {
                max-width: calc(100% - 60px);
                padding: 15px;
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
                color: var(--mode-text);
                background: none;
                border: none;
                box-sizing: border-box;
                outline: none;
            }
            .chat_input input::placeholder {
                color: var(--mode-text);
            }
            .chat_input button {
                width: 60px;
                height: 40px;
                color: var(--mode-text);
                background:none;
                border: none;
                cursor: pointer;
            }

            /** END OF MESSAGES */

            /** FLOATING MESSAGES */
            <?php if (isMobile($userAgent) == false) {?>
            .message-container {
                position: fixed;
                display: flex;
                gap: 10px;
                bottom: 0;
                width: 100%;
                margin: 10px;
                z-index: 10;
            }
            .message-box-icon {
                display: none;
            }
            .message-box {
                width: 60px;
                height: 40px;
                background: rgba(var(--mode),.5);
                backdrop-filter: blur(5px);
                border-radius: 50px;
                box-sizing: border-box;
                overflow: hidden;
                transition: all .3s;
                overflow: hidden;
            }
            .message-box:hover {
                width: 300px;
            }
            .message-box.maximized {
                position: relative;
                margin-top: -340px;
                width: 300px;
                height: 380px;
                border-radius: 10px;
            }
            .message-box.maximized .message-header {
                border-radius: 10px 10px 0 0;
            }
            .message-box.maximized > .message-header .message-actions {
                width: 50px;
            }
            .message-body {
                height: 280px;
                padding: 10px;
                background: rgba(var(--mode),.2);
                border-radius: 10px;
                overflow: auto;
            }
            <?php } else {?>
            .message-container {
                position: fixed;
                display: flex;
                flex-direction: column-reverse;
                gap: 5px;
                left: 20px;
                bottom: 20px;
                width: 50px;
                height: 50px;
                overflow: hidden;
                z-index: 10;
            }
            .message-container .icon {
                width: 50px;
                height: 50px;
                padding: 13px;
                font-size: 24px;
                background: rgba(var(--mode),.5);
                border-radius: 25px;
                border: 1px solid rgba(var(--mode-text),1);
                box-sizing: border-box;
            }
            .message-container .icon:hover .message-container {
                height: 50px;
            }
            .message-box-icon {
                width: 40px;
                height: 40px;
                margin: 0 auto;
                border-radius: 20px;
                background: rgba(var(--mode),.5);
            }
            .message-box-icon img {
                width: 40px;
                height: 40px;
            }
            .message-box {
                position: fixed;
                bottom: 80px;
                left: 80px;
                width: 0;
                max-width: 300px;
                height: 0;
                background: rgba(var(--mode),.8);
                backdrop-filter: blur(5px);
                border-radius: 15px;
                box-sizing: border-box;
                overflow: hidden;
                transition: all .3s;
                overflow: hidden;
            }
            .message-box.open {
                width: calc(100% - 100px);
                height: calc(100% - 155px);
            }
            .message-body {
                height: calc(100% - 100px);
                padding: 10px;
                background: rgba(var(--mode),.2);
                border-radius: 10px;
                overflow: auto;
            }
            <?php }?>
            .message-header {
                display: flex;
                justify-content: space-between;
                padding: 10px;
                background: rgba(var(--mode),.1);
                border-radius: 40px;
            }
            .message-header .message-user {
                display: flex;
                width: 50px;
                height: 20px;
                align-items: center;
            }
            .message-header .message-user img {
                width: 40px;
                height: 40px;
                border-radius: 40px;
                object-fit: cover;
            }
            .message-header .message-user span {
                padding: 0 10px;
            }
            .message-header .message-actions {
                display: flex;
                gap: 10px;
                width: 50px;
                text-align: center;
                overflow: auto;
            }
            .message-header .message-min,
            .message-header .message-close {
                width: 20px;
                padding: 0;
            }
            .message-header .message-min {
                cursor: pointer;
            }
            .message-header .message-close {
                cursor: pointer;
            }
            .message-body::-webkit-scrollbar {
                display: none;
            }
            .message-body .message {
                margin-bottom: 10px;
            }
            .message-body .message .message-user {
                display: flex;
                align-items: center;
            }
            .message-body .message.me .message-user {
                justify-content: right;
            }
            .message-body .message .message-user .message-user-avatar {
                width: 30px;
                padding: 0 10px;
            }
            .message-body .message .message-user .message-user-avatar img {
                width: 100%;
            }
            .message-body .message .message-user .message-user-name {
                font-size: 12px;
            }
            .message-body .message .message-content {
                display: flex;
            }
            .message-body .message.me .message-content {
                justify-content: right;
            }
            .message-body .message .message-content p {
                margin: 0;
                padding: 10px;
                border: 1px solid rgba(var(--mode-invert),.1);
                border-radius: 5px 10px 10px 10px;
            }
            .message-body .message.me .message-content p {
                border-radius: 10px 5px 10px 10px;
            }
            .message-input {
                display: flex;
                justify-content: space-between;
                background: rgba(var(--mode),.1);
                border-radius: 0 0 10px 10px;
            }
            .message-input input {
                width: calc(100% - 60px);
                height: 40px;
                padding: 0 10px;
                color: var(--mode-text);
                background: none;
                border: none;
                outline: none;
                box-sizing: border-box;
            }
            .message-input button {
                width: 50px;
                height: 40px;
                color: var(--mode-text);
                background: none;
                border: none;
                cursor: pointer;
                outline: none;
            }

            /** END OF FLOATING MESSAGES */

            /** PROFILE */

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
            
            <?php if (isMobile($userAgent) == false) {?>
            .profile {
                display: flex;
                gap: 10px;
                justify-content: space-around;
            }
            .profile-left {
                position: sticky;
                top: 115px;
                width: 30%;
                height: calc(100vh - 125px);
            }
            .profile-left i {
                position: absolute;
                width: 40px;
                height: 40px;
                text-align: center;
                cursor: pointer;
            }
            .profile-left .avatar {
                max-width: 150px;
                max-height: 150px;
                aspect-ratio: 1/1;
                margin: 0 auto;
                background: rgba(var(--mode),.3);
                border: 3px solid rgba(var(--mode),.1);
                border-radius: 10px;
                overflow: hidden;
                object-fit: cover;
                box-sizing: border-box;
            }
            .profile-left-user i {
                margin-top: -20px;
                margin-left: 15px;
                width: 40px;
                height: 40px;
                cursor: pointer;
                color: var(--mode-text);
                opacity: .1;
            }
            .profile-left-user:hover i {
                opacity: 1;
            }
            .profile-left-user .username {
                width: 100%;
                margin-top: 10px;
                padding: 10px;
                text-align: center;
                font-size: 1.3rem;
                color: var(--mode-text);
                background: rgba(var(--black-light),.3);
                border-radius: 20px;
                overflow: hidden;
                box-sizing: border-box;
            }
            .profile-left-user .username span {
                color: white !important;
            }
            .profile-tabs {
                padding: 10px;
                background: rgba(var(--mode),.3);
                border-radius: 10px;
                box-sizing: border-box;
            }
            .profile-tabs .settings-cat:hover {
                background: rgba(var(--mode),.1);
            }
            .profile-btns {
                display: flex;
                width: 100%;
                margin: 20px 0;
            }
            #friend_actions {
                height: 40px;
                overflow-y: hidden;
            }
            #friend_actions:hover {
                height: auto;
            }
            .profile-btns button {
                display: flex;
                min-width: 35px;
                width: auto;
                height: 35px;
                margin: 3px;
                padding: 10px;
                color: var(--mode-text);
                background: rgba(var(--mode-invert),.1);
                backdrop-filter: blur(5px);
                border: none;
                border-radius: 10px;
                box-sizing: border-box;
                transition: all .3s, transform .3s;
                overflow: hidden;
            }
            .profile-btns button:hover {
                width: auto;
                cursor: pointer;
            }
            .profile-btns button i {
                width: 35px;
                height: 35px;
                line-height: 35px;
                margin-top: -10px;
                margin-left: -10px;
            }
            .profile-btns button span {
                display: none;
                padding-left: 25px;
            }
            .profile-btns button:hover span {
                display: block;
            }
            .profile-btns button.red {
                color: var(--mode-text);
                background: rgba(255,0,0,.5);
            }
            .profile-btns button.green {
                color: var(--mode-text);
                background: rgba(0,255,0,.5);
            }
            .profile-btns button.blue {
                color: var(--mode-text);
                background: rgba(0,0,255,.5);
            }
            .profile-btns button.yellow {
                color: var(--mode-text);
                background: rgba(255,255,0,.5);
            }
            .profile-btns button.orange {
                color: var(--mode-text);
                background: rgba(255,165,0,.5);
            }
            
            .profile-right {
                width: 70%;
                color: var(--mode-text);
            }
            .profile-right .create_post {
                height: 50px;
            }
            .profile-right .create_post textarea {
                padding-right: 40px;
                border: 1px solid rgba(var(--mode),.2);
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
                width: 100%;
                margin-bottom: 10px;
                padding: 10px;
                overflow-y: scroll;
                box-sizing: border-box;
            }
            .profile-left-user {
                display: flex;
                width: 100%;
            }
            .profile-left .avatar {
                width: 100px;
                height: 100px;
                border: 3px solid rgba(var(--mode),.1);
                border-radius: 10px;
                overflow: hidden;
                object-fit: cover;
                box-sizing: border-box;
            }
            .profile-left-user .username {
                text-align: left;
                width: auto;
                margin-top: 20px;
                padding: 0 10px;
                font-size: 1.3rem;
                color: var(--mode-text);
                overflow: hidden;
                box-sizing: border-box;
            }
            .profile-left-user .username span {
                color: white !important;
            }
            .profile-btns {
                display: flex;
                justify-content: space-between;
                width: 100%;
                padding: 10px 0;
                box-sizing: border-box;
            }
            #friend_actions {
                width: 100%;
                margin: 0 3px;
            }
            .profile-btns button {
                width: 35px;
                height: 35px;
                margin: 3px;
                padding: 10px 0;
                color: var(--mode-text);
                background: rgba(var(--mode-invert),.1);
                backdrop-filter: blur(5px);
                border: none;
                border-radius: 10px;
                box-sizing: border-box;
                transition: all .3s, transform .3s;
                overflow: hidden;
            }
            .profile-btns button:hover {
                cursor: pointer;
            }
            .profile-btns button i {
                width: 35px;
                height: 35px;
                line-height: 35px;
                margin-top: -10px;
            }
            .profile-btns button span {
                display: none;
                padding-left: 25px;
            }
            .profile-btns button:hover span {
                display: block;
            }
            .profile-btns button.red {
                background: rgba(255,0,0,.5);
            }
            
            .profile-right {
                width: 100%;
                padding: 0 10px;
                color: var(--mode-text);
                box-sizing: border-box;
            }
            .profile-right .create_post {
                height: 50px;
            }
            .profile-right .create_post textarea {
                padding-right: 40px;
                border: 1px solid rgba(var(--mode),.2);
            }
            .profile-right .create_post i {
                float: right;
                margin-top: -35px;
            }
            .profile-right .post {
                width: 100%;
            }
            <?php }?>
        
            .profile-left hr {
                height: 1px;
                background: rgba(var(--mode),.1);
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

            <?php if (isMobile($userAgent) == false) {?>
            .changeAvatar,
            .changeWallpaper {
                position: fixed;
                top: 20%;
                left: 0;
                width: 600px;
                margin-left: 50%;
                padding: 20px;
                transform: translateX(-50%);
                background: rgba(var(--mode),.9);
                backdrop-filter: blur(5px);
                border-radius: 10px;
                box-sizing: border-box;
            }
            .changeAvatar img {
                width: 50%;
                max-width: 300px;
                height: 100%;
                max-height: 300px;
            }
            .changeWallpaper img {
                width: 100%;
                max-width: 300px;
                height: 100%;
                max-height: 300px;
            }
            <?php } else {?>
            .changeAvatar,
            .changeWallpaper {
                position: fixed;
                top: 75px;
                left: 0;
                width: 100%;
                height: calc(100% - 165px);
                margin: 0 auto;
                padding: 20px;
                background: rgba(var(--mode),.9);
                backdrop-filter: blur(5px);
                border-radius: 20px;
                box-sizing: border-box;
            }
            .changeAvatar img {
                width: 50%;
                height: 100%;
            }
            .changeWallpaper img {
                width: 100%;
                height: 100%;
            }
            <?php }?>
            .changeAvatar img,
            .changeWallpaper img {
                transform: translateX(50%);
                object-fit: cover;
                object-position: center;
            }
            .changeAvatar i,
            .changeWallpaper i {
                float: right;
                cursor: pointer;
            }
            .changeBtns {
                display: flex;
                justify-content: space-between;
            }
            .changeBtns input {
                height: 40px;
                padding: 10px;
                color: var(--mode-text);
                border: none;
                border-radius: 10px;
                box-sizing: border-box;
                cursor: pointer;
            }
            .changeBtns input[type=file] {
                width: 100%;
            }
            .changeBtns input[type=submit] {
                width: 100px;
                background: rgba(var(--mode-invert),.1);
            }

            .avatar_select_area {
                display: flex;
                justify-content: center;
                align-items: center;
                width: 200px;
                height: 200px;
                margin: 0 auto;
                border: 3px dashed rgba(var(--white-dark),.3);
                border-radius: 50px;
                cursor: pointer;
                overflow: hidden;
            }
            .avatar_select_area i {
                margin: 0;
            }
            .avatar_select_area img {
                width: 100%;
                max-width: 100%;
                height: 100%;
                max-height: 100%;
                object-fit: cover;
            }
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
                background: rgba(var(--mode),.1);
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
                color: var(--mode-text);
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
                background: rgba(var(--mode),.3);
            }
            .ip-actions {
                text-align: right;
            }
            .ip-action {
                display: block;
                margin: 2px;
                padding: 5px;
                color: var(--mode-text);
                background: rgba(0,212,255,.3);
                border: none;
                border-radius: 5px;
                cursor: pointer;
            }

            /** END OF PROFILE */
            /** GROUP */
            <?php if (isMobile($userAgent) == false) {?>
            .new-group-create {
                display: flex;
            }
            .new-group-create .left {
                width: 400px;
                padding: 10px 20px;
                color: var(--mode-text);
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
                color: var(--mode-text);
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
                color: var(--mode-text);
                background: rgba(var(--mode),.1);
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
                color: var(--mode-text);
            }

            <?php if (isMobile($userAgent) == false) {?>
            .group-head {
                display: flex;
                justify-content: space-between;
                align-items: center;
                width: 100%;
                height: 50px;
                line-height: 50px;
                margin: 0 auto;
                padding: 0 10px 10px 10px;
                font-size: 28px;
                text-align: left;
                box-sizing: border-box;
                overflow: hidden;
                word-wrap: break-word;
            }
            <?php } else {?>
            .group-head {
                display: flex;
                justify-content: space-between;
                align-items: center;
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
            .group-head img {
                width: 40px;
                height: 40px;
                margin: 0 5px;
                border-radius: 40px;
            }
            .group-box {
                display: flex;
                height: 800px;
                box-sizing: border-box;
            }
            .group-box p {
                padding: 10px 20px;
                text-align: left;
                color: var(--mode-text);
            }
            <?php if (isMobile($userAgent) == false) {?>
            .gbox-main {
                width: calc(100% - 70px);
                height: 100%;
                background: rgba(var(--mode),.1);
                border-radius: 20px 0 0 20px;
            }
            .gbox-main #gbox-chat,
            .gbox-main #gbox-members,
            .gbox-main #gbox-gallery,
            .gbox-main #gbox-settings,
            .gbox-main #gbox-logout {
                height: 100%;
            }
            .gbox-right {
                width: 70px;
                background: rgba(var(--mode-invert),.1);
                border-radius: 0 20px 20px 0;
            }
            <?php } else {?>
            .gbox-main {
                width: calc(100% - 10px);
                margin: 0 5px;
                background: rgba(var(--mode),.1);
                border-radius: 10px;
                box-sizing: border-box;
            }
            .gbox-main #gbox-chat,
            .gbox-main #gbox-members,
            .gbox-main #gbox-gallery,
            .gbox-main #gbox-settings,
            .gbox-main #gbox-logout {
                height: 100%;
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

            .gbox-chat {
                width: auto;
                height: 100%;
                overflow: scroll;
            }
            .gbox-box::-webkit-scrollbar {
                display: none;
            }
            .gchat-message {
                display: flex;
                align-items: center;
                padding: 10px;
                box-sizing: border-box;
            }
            .gchat-message.me {
                justify-content: flex-end;
            }
            .gchat-message-options {
                position: relative;
                width: 10px;
                padding: 10px;
                text-align: left;
                color: var(--mode-text);
                cursor: pointer;
                opacity: 0;
            }
            .gchat-message:hover .gchat-message-options {
                opacity: 1;
            }
            .gchat-message-options:hover .gchat-message-option-list {
                display: block;
            }
            .gchat-message-option-list {
                display: none;
                position: absolute;
                background: rgba(var(--mode-invert),.3);
                backdrop-filter: blur(5px);
                box-shadow: 10px 10px 23px -5px rgba(var(--mode),0.6);
                border-radius: 0 10px 10px 10px;
                overflow: hidden;
            }
            .gchat-action {
                display: flex;
                padding: 10px 20px;
                color: var(--mode-text);
            }
            .gchat-action:hover {
                background: rgba(0,212,255,.3);
                cursor: pointer;
            }
            .gchat-action i {
                margin-right: 10px;
            }
            .gchat-user {
                width: 50px;
                height: 50px;
                border-radius: 25px;
                overflow: hidden;
            }
            .me .gchat-user {
                margin-right: 0px;
            }
            .gchat-user img {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }
            .gchat-message-box {
                display: flex;
                justify-content: end;
                width: calc(100% - 60px);
                box-sizing: border-box;
            }
            .gchat-text {
                max-width: calc(100% - 60px);
                padding: 15px;
                background: rgba(var(--mode),.2);
                border-radius: 20px;
                box-sizing: border-box;
            }
            .me .gchat-text {
                margin-right: 10px;
            }
            .gchat-message-box .gchat-text {
                max-width: calc(100% - 60px);
                padding: 15px;
                border-radius: 20px;
                box-sizing: border-box;
            }
            .gbox-msg-send {
                display: flex;
                justify-content: right;
                margin-top: 20px;
                padding: 0px;
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

            .gbox-memberlist {
                height: 40%;
                margin: 5px;
            }
            .gbox-member {
                display: inline-flex;
                width: 50px;
                margin: 5px;
            }
            .gbox-member.active img {
                border: 2px solid green;
                box-sizing: border-box;
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
                max-width: 100px;
                margin-top: 50px;
                margin-left: -25px;
                padding: 10px;
                font-size: 14px;
                text-align: center;
                color: var(--mode-text);
                background: black;
                border-radius: 20px;
                box-sizing: border-box;
                overflow: scroll;
                word-wrap: break-word;
                overflow: hidden;
                z-index: 9;
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

            .gbox-settings {
                padding: 10px;
            }
            <?php if (isMobile($userAgent) == false) {?>
            .gbox-settings .split {
                display: flex;
            }
            .gbox-settings .split .divider {
                width: 50%;
            }
            <?php } else {?>
            .gbox-settings .split {
                display: block;
            }
            .gbox-settings .split .divider {
                width: 100%;
            }
            <?php }?>
            .gbox-settings .split .divider p {
                padding: 0;
            }
            .gbox-settings .gbs-icon {
                width: 100px;
                height: 100px;
                margin-bottom: 20px;
                border-radius: 50px;
                overflow: hidden;
            }
            .gbox-settings .gbs-icon img {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }
            .gbox-settings label {
                display: block;
                margin-bottom: 10px;
            }
            .gbox-settings input,
            .gbox-settings select {
                height: 40px;
                margin-bottom: 20px;
                padding: 0 10px;
                border: none;
                outline: none;
                border-radius: 5px;
            }
            .gbox-settings textarea {
                width: 200px;
                height: 100px;
                margin-bottom: 20px;
                padding: 10px;
                border: none;
                outline: none;
                border-radius: 5px;
            }

            .gbox-right p {
                text-align: center;
            }
            .gbox-right-top {
                height: calc(100% - 113px);
            }
            .gbox-right-icons {
                text-align: center;
                width: 100%;
                padding: 5px;
                color: var(--mode-text);
                box-sizing: border-box;
            }
            .gbox-right-icon {
                width: 100%;
                padding: 10px 0;
                font-size: 16px;
                border-radius: 15px;
                box-sizing: border-box;
            }
            .gbox-right-icon:hover {
                background: rgba(var(--mode),.1);
                cursor: pointer;
            }
            .gbox-right-icons .gleave {
                vertical-align: bottom;
            }

            /** END OF GROUP */

            /** MARKETS */

            /** GAMING */

            /** EVENTS */

            /** MUSIC */

            /** Other */

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

            /** BROADCAST MESSAGE */
            .broadcast-msg {
                position: fixed;
                top: 75px;
                left: 50%;
                transform: translateX(-50%);
                max-width: calc(100% - 20px);
                max-height: 50%;
                padding: 20px;
                color: var(--mode-text);
                background: rgba(var(--mode),.5);
                backdrop-filter: blur(5px);
                box-sizing: border-box;
                border-radius: 20px;
                z-index: 5;
            }
        </style>

        <style>
            .happy_new_year {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                z-index: 100;
            }

            .happy_new_year_text {
                position: fixed;
                top: 50%;
                left: 50%;
                width: 70%;
                margin: 0 auto;
                transform: translateX(-50%) translateY(-50%);
                text-align: center;
            }
            <?php if (isMobile($userAgent) == false) {?>
            .happy_new_year_text {
                font-size: 7vh;
            }
            <?php } else {?>
            .happy_new_year_text {
                font-size: 3vh;
            }
            <?php }?>
        </style>