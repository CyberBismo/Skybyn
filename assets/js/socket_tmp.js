function connectWebSocket() {
    ws = new WebSocket('wss://dev.skybyn.no:4433');

    ws.onerror = (error) => {
        console.error('Server connection error:', error);
    };

    ws.onopen = () => {
    };

    ws.onmessage = (event) => {
        const data = JSON.parse(event.data);
    }

    ws.onclose = () => {
        setTimeout(connectWebSocket, 3000);
    };
}

// Initial connection
connectWebSocket();