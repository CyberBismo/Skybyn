function connectWebSocket() {
    ws = new WebSocket('wss://dev.skybyn.no:4433');

    ws.onerror = (error) => {
        console.error('Server connection error:', error);
    };

    ws.onopen = () => {
        if (navigator.userAgent.includes("Tesla") && navigator.userAgent.includes("Linux")) {
            console.log("Tesla browser detected");
        } else {
            console.log("Non-Tesla browser detected");
        }
        ws.send(JSON.stringify({
            type: 'device_info',
            device: navigator.userAgent.includes("Tesla") ? 'Tesla' : 'Non-Tesla',
            browser: navigator.userAgent
        }));
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