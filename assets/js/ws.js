let ws = new WebSocket('wss://dev.skybyn.no:4433');

ws.onerror = (error) => {
    console.error('Server connection error:', error);
};

let url = new URL(window.location.href);

const getCookie = (name) => {
    const value = `; ${document.cookie}`;
    const parts = value.split(`; ${name}=`);

    if (parts.length === 2) return parts.pop().split(';').shift();
};

let userId = getCookie('logged');

if (userId && userId.length === 5) {
    userId = userId.substring(4);
}

if (getCookie('logged')) {
    logged = 'User ID: ';
} else {
    logged = null;
}

let ip = '';

// Send a message to the server when the client connects
ws.onopen = () => {
    if (logged !== null) {
        logged = logged + userId;
    } else {
        logged = 'Visitor';
    }


// Get the client's IP address
fetch('https://api.ipify.org?format=json')
    .then(response => response.json())
    .then(data => {
        ip = data.ip.includes('::1') ? 'localhost' : data.ip;
        if (ws.readyState === WebSocket.OPEN) {
            ws.send(JSON.stringify({
                type: 'client_online',
                logged: logged,
                url: url.href,
                userAgent: navigator.userAgent,
                ip: ip
            }));
        }
    });
};

// Receive messages from the server
ws.onmessage = (event) => {
    let message = event.data;

    if (isJsonString(message)) {
        let msgData = JSON.parse(message);
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

        newWs.onopen = () => {
            newWs.send(JSON.stringify({
                type: 'client_online',
                logged: logged + userId,
                url: url.href,
                userAgent: navigator.userAgent,
                ip: ip
            }));
        };

        newWs.onclose = ws.onclose; // Reassign the onclose handler for reconnection
        newWs.onmessage = ws.onmessage; // Reassign any other event handlers you have for the new WebSocket
        ws = newWs; // Update the original reference
    }, 3000);
};

// Send a message to the server when the client closes the window
window.addEventListener('beforeunload', () => {
    ws.send(JSON.stringify({
        type: 'client_offline',
        logged: logged + userId,
        url: url.href,
        userAgent: navigator.userAgent,
        ip: ip
    }));
    ws.close();
});
