function createMessageBox(uid,fid) {
    const messageBox = document.createElement('div');
    messageBox.id = 'message_box_' + fid;
    messageBox.classList.add('message-box');
    messageBox.classList.add('maximized');
    messageBox.innerHTML = `
            <div class="message-header">
                <div class="message-user" id="message_user_${fid}" onclick="maximizeMessageBox('${fid}')" style="opacity:0">
                    <img src="" id="msg_user_avatar_${fid}">
                    <span id="msg_user_name_${fid}"></span>
                </div>
                <div class="message-actions">
                    <div class="message-close" onclick="closeMessageBox('<?=$friend?>')"><i class="fa-solid fa-xmark"></i></div>
                </div>
            </div>
            <div class="message-body" id="message_body_${fid}"></div>
            <div class="message-input">
                <input type="text" id="message_input_${fid}" placeholder="Type your message..." onkeyup="if (event.keyCode === 13) {sendMessage('${uid}', '${fid}');}">
                <button onclick="sendMessage('${fid}','${uid}')"><i class="fa-solid fa-paper-plane"></i></button>
            </div>`;
    document.getElementById('msg_con').appendChild(messageBox);

    $.ajax({
        url: '../../assets/functions.php',
        type: 'POST',
        data: {
            start_chat: null,
            friend_id: fid
        }
    }).done(function(result) {
        let res = JSON.parse(result);
        const username = res.friend_name;
        let avatar = res.friend_avatar;

        if (!avatar) {
            avatar = '../../assets/images/logo_faded_clean.png';
        }

        const message_user = document.getElementById('message_user_'+fid);
        const messageUser = document.getElementById('msg_user_name_'+fid);
        messageUser.textContent = username;
        const messageAvatar = document.getElementById('msg_user_avatar_'+fid);
        messageAvatar.src = avatar;
        message_user.style.opacity = '1';
    });

    $.ajax({
        url: '../../assets/functions.php',
        type: 'POST',
        data: {
            load_chat: null,
            friend_id: fid
        }
    }).done(function(result) {
        document.getElementById('message_body_'+fid).innerHTML = result;
        const messageContainer = document.getElementById('message_body_'+fid);
        messageContainer.scrollTop = messageContainer.scrollHeight;
    });    
}

function startMessaging(uid,fid) {
    const msg_icon = document.getElementById('msg_con');
    if (msg_icon.querySelector('.icon')) {
        openMessages();
        showChat(fid);
    } else {
        if (document.getElementById('message_box_'+fid)) {
            maximizeMessageBox(fid);
        } else {
            createMessageBox(uid,fid);
        }
    }
}

function openMessages() {
    const msgBox = document.getElementById('msg_con');
    const msgIcon = msgBox.querySelectorAll('.icon');
    if (msgBox.style.height == "auto") {
        msgBox.style.height = "0";
        let messageBoxes = document.querySelectorAll('.message-box');
        messageBoxes.forEach(box => {
            if (box.classList.contains('open')) {
                box.classList.remove('open');
            }
        });
        msgIcon.forEach(iconContainer => {
            iconContainer.querySelectorAll('i').forEach(icon => {
                icon.classList.remove('fa-solid');
                icon.classList.remove('fa-xmark');
                icon.classList.add('fa-regular');
                icon.classList.add('fa-message');
            });
        });
    } else {
        msgBox.style.height = "auto";
        msgIcon.forEach(iconContainer => {
            iconContainer.querySelectorAll('i').forEach(icon => {
                icon.classList.remove('fa-regular');
                icon.classList.remove('fa-message');
                icon.classList.add('fa-solid');
                icon.classList.add('fa-xmark');
            });
        });
    }
}

function hideChat(event) {
    const msgBox = document.getElementById('msg_con');
    const profileChatBtn = document.getElementsByClassName('profile-btns');
    // Only hide if msgBox is open (height == "auto") and click is outside msgBox
    if (
        msgBox.style.height === "auto" &&
        !msgBox.contains(event.target) &&
        !Array.from(profileChatBtn).some(btn => btn.contains(event.target))
    ) {
        msgBox.style.height = "0";
        let messageBoxes = document.querySelectorAll('.message-box');
        messageBoxes.forEach(box => {
            box.classList.remove('open');
        });
        const msgIcon = msgBox.querySelectorAll('.icon');
        msgIcon.forEach(iconContainer => {
            iconContainer.querySelectorAll('i').forEach(icon => {
                icon.classList.remove('fa-solid', 'fa-xmark');
                icon.classList.add('fa-regular', 'fa-message');
            });
        });
    }
}

// Add this event listener to trigger hideChat on document clicks
document.addEventListener('click', hideChat);

function closeMessages() {
    const msgBox = document.getElementById('msg_con');
    msgBox.style.height = "50px";
    let messageBoxes = document.querySelectorAll('.message-box');
    messageBoxes.forEach(box => {
        if (box.classList.contains('open')) {
            box.classList.remove('open');
        }
    });
}

function showChat(fid) {
    let messageBoxes = document.querySelectorAll('.message-box');
    messageBoxes.forEach(box => {
        if (box.classList.contains('open')) {
            box.classList.remove('open');
        }
    });
    let messageBox = document.getElementById('message_box_' + fid);
    if (messageBox) {
        messageBox.classList.add('open');
    }
}

function maximizeMessageBox(fid) {
    const messageContainer = document.getElementById('message_box_'+fid);
    const isMaximized = messageContainer.classList.toggle('maximized');
    if (isMaximized) {
        messageContainer.classList.add('open');

        $.ajax({
            url: '../../assets/functions.php',
            type: 'POST',
            data: {
                max_chat: null,
                friend_id: fid,
                action: 'maximize'
            }
        });
    } else {
        messageContainer.classList.remove('open');

        $.ajax({
            url: '../../assets/functions.php',
            type: 'POST',
            data: {
                max_chat: null,
                friend_id: fid,
                action: 'minimize'
            }
        });
    }
}

function closeMessageBox(friend_id) {
    const fid = friend_id;
    let messageBox = document.getElementById('message_box_'+friend_id);

    // Dissaapear animation = slide down
    messageBox.style.transform = 'translateY(150%)';
    messageBox.style.transition = 'transform 0.3s';

    // Disappear animation = minimize and shrink to 0
    //messageBox.style.width = '0';
    //messageBox.style.height = '0';
    //messageBox.style.transition = 'width 0.5s, height 0.3s';

    setTimeout(() => {
        messageBox.remove();
    }, 500);

    $.ajax({
        url: '../../assets/functions.php',
        type: 'POST',
        data: {
            close_chat: null,
            friend_id: fid
        }
    });
}

function checkEnter(event, uid, fid) {
    if (event.keyCode === 13) {
        sendMessage(uid, fid);
    }
}

function sendMessage(uid, fid) {
    let messageInput = document.getElementById('message_input_'+fid);
    let message = messageInput.value.replace(/</g, "&lt;").replace(/>/g, "&gt;");
    if (message) {
        const avatar = document.querySelector('.message-user-avatar img').getAttribute('src');
        let messageContainer = document.getElementById('message_body_' + fid);

        // Display the message immediately with a placeholder avatar
        const userMessage = document.createElement('div');
        userMessage.classList.add('message', 'me');
        userMessage.innerHTML = `
            <div class="message-user">
                <div class="message-user-name">You</div>
                <div class="message-user-avatar"><img src="${avatar}"></div>
            </div>
            <div class="message-content"><p>${message}</p></div>
        `;

        messageInput.value = '';
        messageContainer.scrollTop = messageContainer.scrollHeight;
        const isAtBottom = messageContainer.scrollHeight - messageContainer.scrollTop === messageContainer.clientHeight;
        messageContainer.appendChild(userMessage);
        if (isAtBottom) {
            messageContainer.scrollTop = messageContainer.scrollHeight;
        }

        $.ajax({
            url: '../../assets/chat/chat_send.php',
            type: 'POST',
            data: {
                to: fid,
                from: uid,
                message: message
            },
            success: function(result) {
                let id = result;
                userMessage.id = 'message_'+id;

                ws.send(JSON.stringify({
                    type: 'chat',
                    id: id,
                    from: uid,
                    to: fid,
                    message: message
                }));
            }
        });
    }
}