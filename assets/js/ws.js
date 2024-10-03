const socket = new WebSocket('ws://localhost:8080');
socket.onopen = () => console.log('Connected to WebSocket');
socket.onmessage = (event) => console.log('Message from server:', event.data);