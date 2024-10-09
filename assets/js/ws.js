const ws = new WebSocket('wss://dev.skybyn.no:4433');

let url = new URL(window.location.href);

const getCookie = (name) => {
    const value = `; ${document.cookie}`;
    const parts = value.split(`; ${name}=`);
    if (parts.length === 2) return parts.pop().split(';').shift();
};

let userId = getCookie('logged') || getCookie('PHPSESSID');
let logged = '';

if (getCookie('logged')) {
    logged = 'User ID: ';
} else {
    logged = 'Session ID: ';
}

ws.onopen = () => {
  ws.send('Client online:\n    ' + logged + userId + '\n    URL: ' + url);
};

ws.onmessage = (event) => {
    msg = event.data;

    if (msg.includes('broadcast')) {
        const broadcastMsg = document.createElement('div');
        broadcastMsg.classList.add('broadcast-msg');
        broadcastMsg.textContent = event.data.replace('broadcast:', '');
        document.getElementsByTagName('body')[0].appendChild(broadcastMsg);
        setTimeout(() => {
            broadcastMsg.style.display = 'none';
        }, 5000);
    } else {
        
    }
};

ws.onerror = (error) => {
  console.error('Server connection error:', error);
};

ws.onclose = () => {
  console.log('Server offline. Back in a bit.');
};

window.addEventListener('beforeunload', () => {
    ws.send('Client offline:\n    ' + logged + userId);
    ws.close();
});