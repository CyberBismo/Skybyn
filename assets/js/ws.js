let ws = new WebSocket('wss://dev.skybyn.no:4433');

ws.onerror = (error) => {
    console.error('Server connection error:', error);
};

let url = new URL(window.location.href);

ws.onopen = () => {
    // Send a message to the server when the client connects
    $.ajax({
        url: '../assets/session.php',
        method: 'POST',
        success: function(response) {
            let sessionData = JSON.parse(response);
            let clientInfo = {
                type: 'client_connected',
                id: sessionData.user
            };
            ws.send(JSON.stringify(clientInfo));
        },
        error: function() {
            let clientInfo = {
                type: 'client_connected',
                id: 'guest'
            };
            ws.send(JSON.stringify(clientInfo));
        }
    });
};

// Receive messages from the server
ws.onmessage = (event) => {
    let message = event.data;

    if (isJsonString(message)) {
        let msgData = JSON.parse(message);
        if (msgData.type == 'qr_login') {
            const code = getcookie('qr');
            if (msgData.code == code) {
                const user = msgData.user;
                $.ajax({
                    url: './assets/session.php',
                    type: 'POST',
                    data: {
                        user: user
                    },
                    success: function (response) {
                        let sessionData = JSON.parse(response);
                        if (sessionData.user === user) {
                            window.location.href = "./";
                        }
                    },
                    error: function () {
                    }
                });
            }
        } else
        if (msgData.type === 'new_post') {
            let postId = msgData.id;
            $.ajax({
                url: './assets/post_load.php',
                type: 'POST',
                data: {
                    post_id: postId
                },
                success: function (response) {
                    if (response !== null) {
                        const postsContainer = document.getElementById('posts');
                        postsContainer.insertAdjacentHTML('afterbegin', response);
                    }
                },
                error: function () {
                }
            });
        } else
        if (msgData.type === 'delete_post') {
            let postId = msgData.id;
            if (document.getElementById('post_'+postId)) {
                let post = document.getElementById('post_'+postId);
                post.remove();
            }
        } else
        if (msgData.type === 'new_comment') {
            let commentId = msgData.cid;
            let postId = msgData.pid;
            let postElement = document.getElementById('post_' + postId);
            let commentsContainer = document.getElementById('post_comments_' + postId);
            let commentsCountElement = document.getElementById('comments_count_' + postId);

            if (postElement && commentsContainer && commentsCountElement) {
                $.ajax({
                    url: './assets/comments_check.php',
                    type: 'POST',
                    data: {
                    comment_id: commentId,
                    post_id: postId
                    },
                    success: function (response) {
                    if (response) {
                        commentsContainer.insertAdjacentHTML('afterbegin', response);
                        commentsCountElement.textContent = parseInt(commentsCountElement.textContent) + 1;
                    }
                    },
                    error: function () {
                    console.error('Failed to load new comment.');
                    }
                });
            }
        } else
        if (msgData.type === 'delete_comment') {
            let commentId = msgData.id;
            let postId = msgData.pid;
            if (document.getElementById('post_'+postId)) {
                if (document.getElementById('comment_'+commentId)) {
                    document.getElementById('comment_'+commentId).remove();
                }
                document.getElementById('comments_count_'+postId).textContent = parseInt(document.getElementById('comments_count_'+postId).textContent) - 1;
            }
        } else
        if (msgData.type === 'chat') {
            let chatId = msgData.id;
            let chatMessage = msgData.message;
            let chatFrom = msgData.from;

            // Display the message immediately
            if (document.getElementById('message_container_' + chatId)) {
                addMessageToChat(chatId, chatMessage, chatFrom);
            } else {
                createMessageBox(chatFrom);
                addMessageToChat(chatId, chatMessage, chatFrom);
            }
            function addMessageToChat(chatId, chatMessage, chatFrom) {
                let messageContainer = document.getElementById('message_container_' + chatId);

                let messageDiv = document.createElement('div');
                messageDiv.classList.add('message');

                let messageUserDiv = document.createElement('div');
                messageUserDiv.classList.add('message-user');

                let messageUserAvatarDiv = document.createElement('div');
                messageUserAvatarDiv.classList.add('message-user-avatar');
                let avatarImg = document.createElement('img');
                avatarImg.src = './assets/images/logo_faded_clean.png'; // Placeholder for avatar, will be updated later
                messageUserAvatarDiv.appendChild(avatarImg);

                let messageUserNameDiv = document.createElement('div');
                messageUserNameDiv.classList.add('message-user-name');
                messageUserNameDiv.textContent = '...'; // Placeholder for username, will be updated later

                messageUserDiv.appendChild(messageUserAvatarDiv);
                messageUserDiv.appendChild(messageUserNameDiv);

                let messageContentDiv = document.createElement('div');
                messageContentDiv.classList.add('message-content');
                let messageContentP = document.createElement('p');
                messageContentP.textContent = chatMessage;
                messageContentDiv.appendChild(messageContentP);

                messageDiv.appendChild(messageUserDiv);
                messageDiv.appendChild(messageContentDiv);

                messageContainer.appendChild(messageDiv);

                // AJAX request to get username and avatar
                $.ajax({
                    url: './assets/functions.php',
                    type: 'POST',
                    data: {
                        get_chat_user: null,
                        friend_id: chatFrom
                    },
                    success: function(response) {
                        const res = JSON.parse(response);
                        const username = res.friend_name;
                        let avatar = res.friend_avatar;

                        // Update the avatar and username once data is retrieved
                        avatarImg.src = avatar;
                        messageUserNameDiv.textContent = username;
                    }
                });
            }
        } else
        if (msgData.type === 'broadcast') {
            const broadcastMsg = document.createElement('div');
            broadcastMsg.classList.add('broadcast-msg');
            broadcastMsg.innerHTML = msgData.message;
            document.getElementsByTagName('body')[0].appendChild(broadcastMsg);

            setTimeout(() => {
                broadcastMsg.remove();
            }, 5000);
        } else
        if (msgData.type === 'message_client') {
            const broadcastMsg = document.createElement('div');
            broadcastMsg.classList.add('broadcast-msg');
            broadcastMsg.innerHTML = msgData.message;
            document.getElementsByTagName('body')[0].appendChild(broadcastMsg);

            setTimeout(() => {
                broadcastMsg.remove();
            }, 5000);
        } else
        if (msgData.type === 'kick') {
            let url = msgData.url;
            window.location.href = url;
        } else
        if (msgData.type === 'active_clients') {
            let count = msgData.count;
            if (document.getElementById('console')) {
                const terminal = document.getElementById('console');
                if (document.getElementById('term_clients')) {
                    const term_client = document.getElementById('term_clients');
                    term_client.innerHTML = 'Active clients: '+count;
                } else {
                    terminal.innerHTML += '<p id="term_clients">Active clients: '+count+'</p>';
                }
            }
        } else {
            console.log('Received message:', message);
        }
    } else {

    }
};

// Function to check if a string is a valid JSON
function isJsonString(value) {
    try {
        JSON.parse(value);
        return true;
    } catch (e) {
        return false;
    }
}

// Reconnect to the server if the connection is closed
ws.onclose = () => {
    setTimeout(() => {
        const newWs = new WebSocket('wss://dev.skybyn.no:4433');

        newWs.onopen = ws.onopen; // Reassign onopen handler
        newWs.onmessage = ws.onmessage; // Reassign message handler
        newWs.onclose = ws.onclose; // Reassign close handler
        newWs.onerror = ws.onerror; // Reassign error handler

        ws = newWs; // Update the reference to the new WebSocket
    }, 3000);
};

// Send a message to the server when the client closes the window
window.addEventListener('beforeunload', () => {
    ws.close();
});

// Helper function to show temporary messages
function showTemporaryMessage(message) {
    const broadcastMsg = document.createElement('div');
    broadcastMsg.classList.add('broadcast-msg');
    broadcastMsg.innerHTML = message;
    document.body.appendChild(broadcastMsg);

    setTimeout(() => {
        broadcastMsg.remove();
    }, 5000);
}

// Function to update active clients in the console element
function updateActiveClients(count) {
    const terminal = document.getElementById('console');
    const termClient = document.getElementById('term_clients');
    if (termClient) {
        termClient.innerHTML = 'Active clients: ' + count;
    } else if (terminal) {
        terminal.innerHTML += '<p id="term_clients">Active clients: ' + count + '</p>';
    }
}
