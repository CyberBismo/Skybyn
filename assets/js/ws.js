function connectWebSocket() {
    ws = new WebSocket('wss://dev.skybyn.no:4433');

    ws.onerror = (error) => {
        console.error('Server connection error:', error);
    };

    let sessionId = localStorage.getItem('sessionId') || generateSessionId();
    localStorage.setItem('sessionId', sessionId);

    ws.onopen = () => {
        const url = new URL(window.location.href);
        if (document.cookie.split(';').some((item) => item.trim().startsWith('logged='))) {
            const userId = document.cookie.replace(/(?:(?:^|.*;\s*)logged\s*\=\s*([^;]*).*$)|^.*$/, "$1");
            if (userId.length > 0) {
                ws.send(JSON.stringify({type: 'connect', sessionId: sessionId, userId: userId, url: url }));
            } else {
                ws.send(JSON.stringify({type: 'connect', sessionId: sessionId, userId: null, url: url }));
            }
        } else {
            ws.send(JSON.stringify({type: 'connect', sessionId: sessionId, userId: null, url: url }));
        }
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

        if (data.type === 'chat') {
            const avatar = document.getElementById('msg_user_avatar_' + data.from).src;
            const friend = document.getElementById('msg_user_name_' + data.from).innerHTML;
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