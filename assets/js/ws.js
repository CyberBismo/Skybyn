function requestNotificationPermission() {

    if ("Notification" in window) {
        Notification.requestPermission().then(permission => {
            if (permission === "granted") {
                browser_perm = JSON.stringify({
                    type: 'browser_perm',
                    device: device(),
                    status: "granted"
                });
                ws.send(browser_perm);
            } else if (permission === "denied") {
                console.log("Notification permission denied.");
            } else {
                console.log("Notification permission dismissed.");
            }
        }).catch(error => {
            console.error("Error requesting notification permission:", error);
        });
    } else {
        console.error("Notifications are not supported by this browser.");
    }
}

// Function to show a notification
function showNotification(sender, message) {
    if (Notification.permission === "granted") {
        new Notification(`New message from ${sender}`, {
            body: message,
            icon: "../images/logo_faded_clean.png" // Replace with your notification icon URL
        });
    } else {
        console.warn("Notification permission not granted.");
    }
}

function device() {
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

    return device;
}

function connectWebSocket() {
    ws = new WebSocket('wss://dev.skybyn.com:4433');

    ws.onerror = (error) => {
        console.error(error);
    };

    let sessionId = localStorage.getItem('sessionId') || generateSessionId();
    localStorage.setItem('sessionId', sessionId);

    ws.onopen = () => {
        const url = new URL(window.location.href);
        
        let information = '';

        const deviceInfo = {
            device: device(),
            browser: navigator.userAgent
        };

        if (document.cookie.split(';').some((item) => item.trim().startsWith('login_token='))) {
            const userId = document.cookie.replace(/(?:(?:^|.*;\s*)login_token\s*\=\s*([^;]*).*$)|^.*$/, "$1");
            if (userId.length > 0) {
                information = JSON.stringify({
                    type: 'connect',
                    sessionId: sessionId,
                    userId: userId,
                    url: url,
                    deviceInfo: deviceInfo
                });
            } else {
                information = JSON.stringify({
                    type: 'connect',
                    sessionId: sessionId,
                    userId: null,
                    url: url,
                    deviceInfo: deviceInfo
                });
            }
        } else {
            information = JSON.stringify({
                type: 'connect',
                sessionId: sessionId,
                userId: null,
                url: url,
                deviceInfo: deviceInfo
            });
        }

        ws.send(information);
    };

    ws.onmessage = (event) => {
        const data = JSON.parse(event.data);

        if (data.type === 'active_clients') {
            var users = data.userCount;
            var guests = data.guestCount;
            updateActiveClients(users,guests);
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
                url: './assets/posts/post_load.php',
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
                    url: './assets/comments/comments_check.php',
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
            const messageBox = document.getElementById('message_box_' + data.from);
            if (messageBox) {
                const messageContainer = document.getElementById('message_body_' + data.from);
                const avatar = document.getElementById('msg_user_avatar_' + data.from).src;
                const friend = document.getElementById('msg_user_name_' + data.from).innerHTML;
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

                showNotification(data.from, data.message);

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
            } else {
                startMessaging(data.to,data.from);
            }
        }

        if (data.type == 'notification') {
            checkNoti();
        }

        if (data.type === 'ping') {
            ws.send(JSON.stringify({type: 'pong'}));
        }

        if (data.type === 'reload') {
            window.location.reload();
        }

        if (data.type === 'kick') {
            var url = data.url;
            window.location.href = url;
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
function updateActiveClients(users,guests) {
    const terminal = document.getElementById('console');
    const termClient = document.getElementById('term_clients');
    if (termClient) {
        termClient.innerHTML = 'Active users: ' + users + '<br>Active guests: ' + guests;
    } else if (terminal) {
        terminal.innerHTML += '<p id="term_clients">Active users: ' + users + '<br>Active guests: ' + guests + '</p>';
    }
}

function generateSessionId() {
    return 'xxxx-xxxx-4xxx-yxxx-xxxx'.replace(/[xy]/g, c => {
        const r = (Math.random() * 16) | 0,
            v = c === 'x' ? r : (r & 0x3) | 0x8;
        return v.toString(16);
    });
}