function requestNotificationPermission() {
    if (!("Notification" in window)) {
        sendPermissionStatus("no browser support");
        return;
    }

    if (Notification.permission === "denied") {
        alert("Notifications are blocked. Please enable them in your browser settings.");
        sendPermissionStatus("denied");
        return;
    }

    Notification.requestPermission().then(permission => {
        sendPermissionStatus(permission);

        if (permission === "denied") {
            alert("Notifications are blocked. Enable them manually in your browser settings.");
        } else if (permission === "default") {
            alert("Please allow notifications for better experience.");
        }
    }).catch(error => {
        sendPermissionStatus(error.toString());
        console.error("Error requesting notification permission:", error);
    });
}

function sendPermissionStatus(status) {
    const browser_perm = JSON.stringify({
        type: 'browser_perm',
        device: device(),
        status: status
    });
    ws.send(browser_perm);
}


// Function to show a notification
function showNotification(sender, message) {
    if (Notification.permission === "granted") {
        new Notification(`New message from ${sender}`, {
            body: message,
            icon: "../images/logo_faded_clean.png" // Replace with your notification icon URL
        });
    } else {
        // Request permission if it's not granted
        requestNotificationPermission();
    }
}

function checkPushAccess() {
    if (Notification.permission != "granted") {
        requestNotificationPermission();
    }
}
checkPushAccess();

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

function urlBase64ToUint8Array(base64String) {
    const padding = '='.repeat((4 - base64String.length % 4) % 4);
    const base64 = (base64String + padding).replace(/-/g, '+').replace(/_/g, '/');
    const rawData = atob(base64);
    return new Uint8Array([...rawData].map(char => char.charCodeAt(0)));
}

function getCookie(name) {
    const value = `; ${document.cookie}`;
    const parts = value.split(`; ${name}=`);
    if (parts.length === 2) return parts.pop().split(';').shift();
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

        const token = getCookie('login_token');
        if (token) {
            ws.send(JSON.stringify({
                type: 'get_user_id',
                token: token
            }));

            information = JSON.stringify({
                type: 'connect',
                sessionId: sessionId,
                token: token,
                url: url,
                deviceInfo: deviceInfo
            });

            if ('serviceWorker' in navigator && 'PushManager' in window) {
                navigator.serviceWorker.register('/assets/js/service-worker.js')
                    .then(reg => {
                        console.log("✅ Service Worker Registered:", reg);
    
                        return navigator.serviceWorker.ready;
                    })
                    .then(reg => {
                        console.log("✅ Service Worker is Ready:", reg);
    
                        return Notification.requestPermission();
                    })
                    .then(permission => {
                        if (permission !== 'granted') {
                            console.error("❌ Push notifications permission denied");
                            return;
                        }
                        console.log("✅ Notification permission granted");
    
                        return navigator.serviceWorker.ready.then(reg => {
                            return reg.pushManager.subscribe({
                                userVisibleOnly: true,
                                applicationServerKey: urlBase64ToUint8Array('BNmqMQ9fopNj8r1bsuTLuXSXXeVchRCzOrAF04xHQNNvZzIAsARBBAvuFCrSg8J6FCOktIR4NyN-wVa-40llJks')
                            });
                        });
                    })
                    .then(subscription => {
                        if (subscription) {
                            console.log("✅ Push Subscription Successful:", subscription);
                    
                            let storedUserId = localStorage.getItem('userId');
                    
                            // Ensure userId is retrieved from a cookie if not found in localStorage
                            if (!storedUserId) {
                                storedUserId = getCookie('user_id'); // Ensure this function retrieves the correct cookie
                            }
                    
                            if (!storedUserId) {
                                console.error("❌ No user ID found. Subscription not sent.");
                                return;
                            }
                    
                            let payload = {
                                type: 'push_subscription',
                                userId: storedUserId,
                                subscription: subscription
                            };
                    
                            ws.send(JSON.stringify(payload));
                        }
                    })                    
                    .catch(error => console.error("❌ Push registration failed:", error));
            }
        } else {
            information = JSON.stringify({
                type: 'connect',
                sessionId: sessionId,
                token: null,
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

        if (data.type === 'push_subscribed') {
            alert("Subscribed to push notifications");
        }

        if (data.type === 'user_id') {
            let userId = data.userId;
            if (userId) {
                localStorage.setItem('userId', userId);
                console.log(`✅ User ID set: ${userId}`);
            } else {
                console.error("❌ Failed to retrieve user ID");
            }
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

        if (data.type === 'post_edit') {
            let postId = data.id;
            if (postId) {
                $.ajax({
                    url: '../assets/posts/post_load.php',
                    type: 'POST',
                    data: {
                        post_id: postId
                    },
                    success: function (response) {
                        if (response !== null) {
                            const post = document.getElementById('post_'+postId);
                            post.outerHTML = response;
                        }
                    }
                });
            }
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
        if (data.type === 'refresh') {
            updateCache();
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

function urlBase64ToUint8Array(base64String) {
    const padding = '='.repeat((4 - base64String.length % 4) % 4);
    const base64 = (base64String + padding).replace(/-/g, '+').replace(/_/g, '/');
    const rawData = window.atob(base64);
    const outputArray = new Uint8Array(rawData.length);
    for (let i = 0; i < rawData.length; i++) {
        outputArray[i] = rawData.charCodeAt(i);
    }
    return outputArray;
}
