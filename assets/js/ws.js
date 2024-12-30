//const { clearInterval } = require("node:timers");

function connectWebSocket() {
    ws = new WebSocket('wss://dev.skybyn.com:4433');

    ws.onerror = (error) => {
        
    };

    let sessionId = localStorage.getItem('sessionId') || generateSessionId();
    localStorage.setItem('sessionId', sessionId);

    // Store device and browser information
    let device = 'Unknown';
    if (navigator.userAgent.match(/Android/i)) {
        device = 'Android';
    } else if (navigator.userAgent.match(/iPhone|iPad|iPod/i)) {
        device = 'iOS';
    } else if (navigator.userAgent.match(/Windows/i)) {
        device = 'Windows';
    } else if (navigator.userAgent.match(/Mac/i)) {
        device = 'Mac';
    } else if (navigator.userAgent.match(/Linux/i)) {
        device = 'Linux';
    } else if (navigator.userAgent.match(/Ubuntu/i)) {
        device = 'Ubuntu';
    } else if (navigator.userAgent.match(/BlackBerry/i)) {
        device = 'BlackBerry';
    } else if (navigator.userAgent.match(/Tesla/i)) {
        device = 'Tesla';
    }

    ws.onopen = () => {
        const url = new URL(window.location.href);
        
        let information = '';

        const deviceInfo = {
            type: 'device_info',
            device: device,
            browser: navigator.userAgent
        };

        if (document.cookie.split(';').some((item) => item.trim().startsWith('logged='))) {
            const userId = document.cookie.replace(/(?:(?:^|.*;\s*)logged\s*\=\s*([^;]*).*$)|^.*$/, "$1");
            if (userId.length > 0) {
                information = JSON.stringify({
                    type: 'connect',
                    sessionId: sessionId,
                    userId: userId,
                    url: url
                });
            } else {
                information = JSON.stringify({
                    type: 'connect',
                    sessionId: sessionId,
                    userId: null,
                    url: url
                });
            }
        } else {
            information = JSON.stringify({
                type: 'connect',
                sessionId: sessionId,
                userId: null,
                url: url
            });
        }

        ws.send(information);
    };

    ws.onmessage = (event) => {
        const data = JSON.parse(event.data);

        if (data.type === 'active_clients') {
            updateActiveClients(data.count);
        }

        if (data.type === 'broadcast') {
            // Create a new div element with id 'broadcast'
            const body = document.querySelector('body');
            const broadcast = document.createElement('div');
            broadcast.classList.add('broadcast-msg');
            broadcast.innerHTML = data.message;
            body.appendChild(broadcast);
            // Delete div element with id 'broadcast' after 5 seconds
            setTimeout(() => {
                broadcast.remove();
            }, 5000);
        }

        if (data.type === 'new_post') {
            let postId = data.id;
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
                }
            });
        }

        if (data.type === 'delete_post') {
            let postId = data.id;
            if (document.getElementById('post_'+postId)) {
                let post = document.getElementById('post_'+postId);
                post.remove();
            }
        }

        if (data.type === 'new_comment') {
            let commentId = data.cid;
            let postId = data.pid;
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
        }

        if (data.type === 'delete_comment') {
            let commentId = data.id;
            let postId = data.pid;
            if (document.getElementById('post_'+postId)) {
                if (document.getElementById('comment_'+commentId)) {
                    document.getElementById('comment_'+commentId).remove();
                }
                document.getElementById('comments_count_'+postId).textContent = parseInt(document.getElementById('comments_count_'+postId).textContent) - 1;
            }
        }

        if (data.type === 'chat') {
            const avatar = document.getElementById('msg_user_avatar_' + data.from).src;
            const friend = document.getElementById('msg_user_name_' + data.from).innerHTML;
            const messageBox = document.getElementById('message_box_' + data.from);
            const messageContainer = document.getElementById('message_body_' + data.from);
            const userMessage = document.createElement('div');
            userMessage.classList.add('message');
            userMessage.id = 'message_' + data.id;
            userMessage.innerHTML = `
                <div class="message-user">
                    <div class="message-user-avatar"><img src="${avatar}"></div>
                    <div class="message-user-name">${friend}</div>
                </div>
                <div class="message-content"><p>${data.message}</p></div>
            `;

            messageContainer.scrollTop = messageContainer.scrollHeight;
            const isAtBottom = messageContainer.scrollHeight - messageContainer.scrollTop === messageContainer.clientHeight;
            messageContainer.appendChild(userMessage);
            if (isAtBottom) {
                messageContainer.scrollTop = messageContainer.scrollHeight;
            }

            if (!messageBox.classList.contains('maximized')) {
                function breathe(x) {
                    let intervalId; // Variable to store the interval ID
                
                    if (x == "start") {
                        intervalId = setInterval(() => {
                            messageBox.style.background = "";
                            setTimeout(() => {
                                messageBox.style.background = "";
                            }, 500);
                        }, 1000);
                    } else if (x == "stop") {
                        clearInterval(intervalId);
                    }
                }
                breathe('start');                
            }
        }

        if (data.type == 'notification') {
            checkNoti();
        }

        if (data.type === 'ping') {
            ws.send(JSON.stringify({type: 'pong'}));
        }
    };

    ws.onclose = () => {
        setTimeout(connectWebSocket, 3000);
    };
}

addEventListener('beforeunload', () => {
    ws.send(JSON.stringify({type: 'disconnect', sessionId: localStorage.getItem('sessionId') }));
    ws.close();
});

// Initial connection
connectWebSocket();

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

function generateSessionId() {
    return 'xxxx-xxxx-4xxx-yxxx-xxxx'.replace(/[xy]/g, c => {
        const r = (Math.random() * 16) | 0,
            v = c === 'x' ? r : (r & 0x3) | 0x8;
        return v.toString(16);
    });
}