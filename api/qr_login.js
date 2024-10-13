// Receive data from post request
const data = req.body;
const code = data.code;
const user = data.user;

// Send data using WebSocket
const ws = new WebSocket('wss://dev.skybyn.no:4433');
ws.onopen = () => {
    ws.send(JSON.stringify({
        type: 'qr_login',
        code: code,
        user: user
    }));
};