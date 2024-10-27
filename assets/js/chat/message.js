function createMessageBox(user_id,friend_id) {
    const uid = user_id;
    const fid = friend_id;

    const messageBox = document.createElement('div');
    messageBox.id = 'message_box_' + fid;
    messageBox.classList.add('message-box');
    messageBox.innerHTML = `
            <div class="message-header">
                <div class="message-user" id="message_user_${fid}" onclick="maximizeMessageBox('${fid}')" style="opacity:0">
                    <img src="" id="msg_user_avatar_${fid}">
                    <span id="msg_user_name_${fid}"></span>
                </div>
                <div class="message-actions">
                    <div class="message-min" onclick="minimizeMessageBox('${fid}')"><i class="fa-solid fa-chevron-down" id="msg_min_${fid}"></i></div>
                    <div class="message-close" onclick="closeMessageBox('${fid}')"><i class="fa-solid fa-xmark"></i></div>
                </div>
            </div>
            <div class="message-body" id="message_body_${fid}"></div>
            <div class="message-input">
                <input type="text" id="message_input_${fid}" placeholder="Type your message...">
                <button onclick="sendMessage('${fid}','${uid}')"><i class="fa-solid fa-paper-plane"></i></button>
            </div>`;
    document.getElementsByTagName('body')[0].appendChild(messageBox);

    $.ajax({
        url: './assets/functions.php',
        type: 'POST',
        data: {
            start_chat: null,
            friend_id: String(fid).slice(4)
        }
    }).done(function(response) {
        const res = JSON.parse(response);
        const username = res.friend_name;
        let avatar = res.friend_avatar;

        if (avatar === null) {
            avatar = './assets/images/logo_faded_clean.png';
        }

        const message_user = document.getElementById('message_user_'+fid);
        const messageUser = document.getElementById('msg_user_name_'+fid);
        messageUser.textContent = username;
        const messageAvatar = document.getElementById('msg_user_avatar_'+fid);
        messageAvatar.src = avatar;
        message_user.style.opacity = '1';
    });

    $.ajax({
        url: './assets/functions.php',
        type: 'POST',
        data: {
            load_chat: null,
            friend_id: String(fid).slice(4)
        }
    }).done(function() {
    });
}

function startMessaging(user_id,friend_id) {
    const uid = user_id;
    const fid = friend_id;
    if (document.getElementById('message_box_'+fid)) {
        const messageBox = document.getElementById('message_box_'+fid);
        if (!messageBox.classList.contains('maximized')) {
            messageBox.classList.add('maximized');
        }
    } else {
        createMessageBox(uid,fid);
    }
}

function maximizeMessageBox(fid) {
    const messageContainer = document.getElementById('message_box_'+fid);
    messageContainer.classList.toggle('maximized');
    const minimize = document.getElementById('msg_min_'+fid);
    if (minimize.classList.contains('fa-chevron-up')) {
        minimize.classList.remove('fa-chevron-up');
        minimize.classList.add('fa-chevron-down');

        $.ajax({
            url: './assets/functions.php',
            type: 'POST',
            data: {
                max_chat: null,
                friend_id: fid,
                action: 'maximize'
            }
        });
    } else {
        minimize.classList.remove('fa-chevron-down');
        minimize.classList.add('fa-chevron-up');

        $.ajax({
            url: './assets/functions.php',
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
    const fid = String(friend_id).slice(4);
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
        url: './assets/functions.php',
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
    const messageInput = document.getElementById('message_input_'+fid);
    let message = messageInput.value;
    if (message) {
        let messageContainer = document.getElementById('message_body_' + fid);

        // Display the message immediately with a placeholder avatar
        const userMessage = document.createElement('div');
        userMessage.classList.add('message', 'me');
        userMessage.id = 'message_'+uid;
        userMessage.innerHTML = `
            <div class="message-user">
                <div class="message-user-name">You</div>
                <div class="message-user-avatar"><img src="../assets/images/logo_faded_clean.png" id="message_user_avatar_${uid}"></div>
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

        let username = document.getElementById('msg_user_name_'+fid).textContent;
        let avatar = document.getElementById('msg_user_avatar_'+fid).src;

        $.ajax({
            url: './assets/chat_send.php',
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
                    message: message,
                    username: username,
                    avatar: avatar
                }));
            }
        });
    }
}