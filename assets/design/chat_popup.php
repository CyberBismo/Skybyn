        <div class="message-box-icon" onclick="showChat('0')">
            <img src="../assets/images/logo_faded_clean.png">
        </div>
        <div class="message-box" id="message_box_0">
            <div class="message-header" onclick="maximizeMessageBox('0')">
                <div class="message-user" id="message_user_0">
                    <div class="message-user-avatar">
                    <img src="../assets/images/logo_faded_clean.png" id="msg_user_avatar_0">
                    </div>
                    <div class="message-user-name">Friend</div>
                </div>
                <div class="message-actions">
                    <?php if (isMobile($userAgent) == false) {?>
                    <div class="message-close" onclick="closeMessageBox('0')"><i class="fa-solid fa-xmark"></i></div>
                    <?php }?>
                </div>
            </div>
            <div class="message-body" id="message_body_0">

                <div class="message">
                    <div class="message-user">
                        <div class="message-user-avatar"><img src="../assets/images/logo_faded_clean.png"></div>
                        <div class="message-user-name">Friend</div>
                    </div>
                    <div class="message-content"><p>Hello you</p></div>
                </div>
                <div class="message me">
                    <div class="message-user">
                        <div class="message-user-name">You</div>
                        <div class="message-user-avatar"><img src="../assets/images/logo_faded_clean.png"></div>
                    </div>
                    <div class="message-content"><p>Hello you too</p></div>
                </div>
                <div class="message">
                    <div class="message-user">
                        <div class="message-user-avatar"><img src="../assets/images/logo_faded_clean.png"></div>
                        <div class="message-user-name">Friend</div>
                    </div>
                    <div class="message-content"><p>Hello you</p></div>
                </div>
                <div class="message me">
                    <div class="message-user">
                        <div class="message-user-name">You</div>
                        <div class="message-user-avatar"><img src="../assets/images/logo_faded_clean.png"></div>
                    </div>
                    <div class="message-content"><p>Hello you too</p></div>
                </div>

            </div>
            <div class="message-input">
                <input type="text" id="message_input_0" placeholder="Type your message..." onkeypress="checkEnter(event,'<?=$uid?>','0')">
                <button onclick="sendMessage('<?=$uid?>','0')"><i class="fa-solid fa-paper-plane"></i></button>
            </div>
        </div>