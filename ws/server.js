const WebSocket = require('ws');
const fs = require('fs');
const https = require('https');
const readline = require('readline');

const webPush = require('web-push');
const mysql = require('mysql');

const server = https.createServer({
    cert: fs.readFileSync('ssl/cert.pem'),
    key: fs.readFileSync('ssl/key.pem')
});

require('dotenv').config();

// Start WebSocket server on port 4433
server.listen(4433, () => {
    console.warn('Server running\n');
});

const dbConfig = {
    host: process.env.DB_HOST,
    user: process.env.DB_USER,
    password: process.env.DB_PASSWORD,
    database: process.env.DB_NAME,
    multipleStatements: true
};

let db;

function connectDatabase() {
    db = mysql.createConnection(dbConfig);
    
    db.connect(err => {
        if (err) {
            //console.error("| Database: FAILED         |\nReason:\n", err, "\n\n");
            setTimeout(connectDatabase, 5000); // Retry connection after 5 seconds
        } else {
            //console.info("| Database: CONNECTED      |\n");
        }
    });

    db.on("error", err => {
        if (err.code === "PROTOCOL_CONNECTION_LOST") {
            //console.error("⚠️ Database connection lost. Reconnecting...\n");
            connectDatabase();
        } else {
            throw err;
        }
    });
}
connectDatabase();

// VAPID keys (replace with your generated keys)
const vapidKeys = {
    publicKey: process.env.VAPID_PUBLIC_KEY,
    privateKey: process.env.VAPID_PRIVATE_KEY
};

// Set VAPID details
webPush.setVapidDetails(
    'mailto:admin@skybyn.no', // Replace with your email
    vapidKeys.publicKey,
    vapidKeys.privateKey
);

function getTime() {
    let now = new Date();
    let time = now.getFullYear() + "." +
            String(now.getMonth() + 1).padStart(2, '0') + "." +
            String(now.getDate()).padStart(2, '0') + " - " +
            String(now.getHours()).padStart(2, '0') + ":" +
            String(now.getMinutes()).padStart(2, '0') + ":" +
            String(now.getSeconds()).padStart(2, '0');
    return time;
}

const wss = new WebSocket.Server({ server });
const clientMap = new Map(); // Map to store clients by their unique identifier

// Handle WebSocket connections
wss.on('connection', (ws) => {
    const cleanedIp = ws._socket.remoteAddress.replace(/^::ffff:/, '');

    ws.on('message', (message) => {
        if (isJsonString(message)) {
            const data = JSON.parse(message);

            if (data.type === 'connect') {
                let clientID = data.sessionId;
                let userId = data.token;
                let url = data.url;
                let device = data.deviceInfo['device'];
                let time = getTime();

                let guestCount = 0; // Start guest ID count at 1
                wss.clients.forEach(client => {
                    if (client.readyState === WebSocket.OPEN && !client.clientID) {
                        guestCount++;
                    }
                });
                
                let logType = "";

                if (userId === null) {
                    userId = "g"+guestCount;
                    logType = "Guest " + userId + " connected";
                    clientMap.set(clientID, { ws, ip: cleanedIp, userId: userId });
                    console.log(`${time}\n${logType} \nIP: ${cleanedIp}\nUrl: ${url}\nDevice: ${device}\n`);
                } else {
                    let username = "";
                    db.query("SELECT username FROM users WHERE token = ?", [userId], (err, results) => {
                        if (err) {
                            console.error("Error fetching username:", err);
                            return;
                        }
                        username = results[0]?.username || userId;
                        logType = "User " + username + " connected";
                        clientMap.set(clientID, { ws, ip: cleanedIp, userId: userId });
                        console.log(`${time}\n${logType} \nIP: ${cleanedIp}\nUrl: ${url}\nDevice: ${device}\n`);
                    });
                }

                setTimeout(() => {
                    updateActiveClients();
                }
                , 1000); // Delay to ensure all clients are registered
                registerActivity(cleanedIp);
            }

            if (data.type === 'disconnect') {
                let clientID = data.sessionId;
                clientMap.delete(clientID); // Remove the client from the map on disconnect
                updateActiveClients();
            }

            if (data.type === 'new_post') {
                let postId = data.id;
                let broadcastMessage = JSON.stringify({
                    type: 'new_post',
                    id: postId
                });
                wss.clients.forEach((client) => {
                    if (client.readyState === WebSocket.OPEN) {
                        client.send(broadcastMessage);
                    }
                });
            }

            if (data.type === 'delete_post') {
                let postId = data.id;
                let broadcastMessage = JSON.stringify({
                    type: 'delete_post',
                    id: postId
                });
                wss.clients.forEach((client) => {
                    if (client.readyState === WebSocket.OPEN) {
                        client.send(broadcastMessage);
                    }
                });
            }

            if (data.type === 'new_comment') {
                let commentId = data.cid;
                let postId = data.pid;
                let broadcastMessage = JSON.stringify({
                    type: 'new_comment',
                    pid: postId,
                    cid: commentId
                });
                wss.clients.forEach((client) => {
                    if (client.readyState === WebSocket.OPEN) {
                        client.send(broadcastMessage);
                    }
                });
            }

            if (data.type === 'delete_comment') {
                let commentId = data.cid;
                let postId = data.pid;
                let broadcastMessage = JSON.stringify({
                    type: 'delete_comment',
                    id: commentId,
                    pid: postId
                });
                wss.clients.forEach((client) => {
                    if (client.readyState === WebSocket.OPEN) {
                        client.send(broadcastMessage);
                    }
                });
            }

            if (data.type === 'chat') {
                const { id, sender, reciever, message: messageText } = data;
                const sendMsg = JSON.stringify({
                    type: 'chat',
                    id,
                    sender,
                    reciever,
                    message: messageText,
                });
                
                clientMap.forEach((client, userId) => {
                    if (userId === reciever && client.ws.readyState === WebSocket.OPEN) {
                        client.ws.send(sendMsg);
                        console.log(`Message sent to client ${userId}: ${messageText}`);
                    }
                });
                clientMap.forEach((client, userId) => {
                    if (userId === sender && client.ws.readyState === WebSocket.OPEN) {
                        client.ws.send(sendMsg);
                        console.log(`Message sent to client ${userId}: ${messageText}`);
                    }
                });
            }

            if (data.type === 'notification') {
                const to = data.to;
                clientMap.forEach((client, userId) => {
                    if (userId === to && client.ws.readyState === WebSocket.OPEN) {
                        client.ws.send(sendMsg);
                    }
                });
            }

            if (data.type === 'pong') {
                console.log("Pong received from client:", data.sessionId);
                clientMap.forEach((client, userId) => {
                    if (client.sessionID === data.sessionId) {
                        clientMap.set(userId, { ws, ip: cleanedIp, sessionID: data.sessionId });
                    }
                });
            }

            else {
                if (!Buffer.isBuffer(message)) {
                    console.log(message);
                }
            }
        }
        else {
            console.log("Received non-JSON message:", message);
        }
    });

    ws.on('close', (code, reason) => {
        const clientID = [...clientMap.entries()].find(([_, client]) => client.ws === ws)?.[0];
        if (clientID) {
            clientMap.delete(clientID);
            updateActiveClients();
        } else {
            //console.log('Disconnected WebSocket was not found in the clientMap.');
        }
    });

    ws.on('error', (error) => {
        console.error(`WebSocket error: ${error}`);
    });
});

function isJsonString(value) {
    try {
        JSON.parse(value);
        return true;
    } catch (e) {
        return false;
    }
}

// Function to register activity in the database
function registerActivity(ip) {
    const time = Math.floor(Date.now() / 1000);
    // Check if the IP address is already registered
    const checkQuery = "SELECT COUNT(*) AS count FROM visitors WHERE ip = ?";
    db.query(checkQuery, [ip], (err, results) => {
        if (err) {
            console.error("Error checking activity:", err);
            return;
        }

        const count = results[0].count;
        if (count === 0) {
            // If not registered, insert the new activity
            insertActivity(ip);
        } else {
            //console.log("IP address already registered for today.");
            // Optionally, you can update the existing record if needed
            const updateQuery = "UPDATE visitors SET time = ? WHERE ip = ?";
            db.query(updateQuery, [time, ip], (err) => {
                if (err) {
                    console.error("Error updating activity:", err);
                } else {
                    //console.log("Activity updated successfully.");
                }
            });
        }
    });
}

function insertActivity(ip) {
    const time = Math.floor(Date.now() / 1000);
    const insertQuery = "INSERT INTO visitors (ip, time) VALUES (?, ?)";
    //console.log("Inserting activity:", ip, time);
    db.query(insertQuery, [ip, time], (err) => {
        if (err) {
            console.error("Error inserting activity:", err);
        } else {
            //console.log("Activity registered successfully.");
        }
    });
}

function pingPong() {
    console.log("Sending ping to all clients\n");
    let pingPong = JSON.stringify({ type: 'ping' });
    clientMap.forEach((client) => {
        client.ws.send(pingPong);
    });
}

setInterval(pingPong, 5000);

// Function to broadcast messages to all connected WebSocket clients
function broadcastToAll(message) {
    let broadcastMessage = JSON.stringify({
        type: 'broadcast',
        message: message
    });
    // Send the message to all clients
    clientMap.forEach((client) => {
        if (client.ws.readyState === WebSocket.OPEN) {
            client.ws.send(broadcastMessage);
        }
    });
}

// Send message to client
function sendMessage(clientId, message) {
    const clientSocket = clientMap.get(clientId);
    if (clientSocket && clientSocket.ws.readyState === WebSocket.OPEN) {
        clientSocket.ws.send(JSON.stringify({
            type: 'broadcast',
            message: message
        }));
        console.log(`Message sent to client ${clientId}\n`);
    } else {
        console.log(`Cannot send message. Client ${clientId} not found or WebSocket not open.\n`);
    }
}

function updateActiveClients() {
    const activeUsersCount = [...clientMap.entries()].filter(([userId, client]) => client.ws.readyState === WebSocket.OPEN && !/^g\d+$/.test(userId)).length;
    const activeGuestsCount = [...clientMap.entries()].filter(([userId, client]) => client.ws.readyState === WebSocket.OPEN && /^g\d+$/.test(userId)).length;
    clientMap.forEach((client) => {
        if (client.ws.readyState === WebSocket.OPEN) {
            client.ws.send(JSON.stringify({ type: 'active_clients', userCount: activeUsersCount, guestCount: activeGuestsCount }));
        }
    });
}

// Read input from command line and broadcast messages to WebSocket clients
const rl = readline.createInterface({
    input: process.stdin,
    output: process.stdout
});

rl.on('line', (input) => {
    // List all commands
    if (input === 'help' || input === '?') {
        console.log('\nAvailable commands:');
        console.log('help, ? - Show this help message');
        console.log('cls, clear - Clear console');
        console.log('online - List connected users/guests count');
        console.log('broadcast:message, bc:message - Send message to all clients');
        console.log('msgto:clientId msg:message - Send message to specific client');
        console.log('reload:clientId - Force reload specific client');
        console.log('refresh - Force all clients to clear cache');
        console.log('kick:clientId url:URL - Redirect client to specified URL');
        console.log('check:userId - Check if user is connected\n');
    }
    
    // Clear the console
    if (input === 'cls' || input === 'clear') {
        console.clear();
    }

    // Reload a specific client
    if (input.startsWith('reload:')) {
        const clientMatch = input.match(/reload:([a-zA-Z0-9]+)/);
        
        if (clientMatch) {
            const clientId = clientMatch[1];
            console.log(`Reloading client (${clientId})`);
            const clientSocket = clientMap.get(clientId); // Retrieve client WebSocket object
            
            if (clientSocket) {
                clientSocket.ws.send(JSON.stringify({
                    type: 'reload'
                }));
            } else {
                console.log(`Client with ID ${clientId} not found.\n`);
            }
        } else {
            console.log('Invalid input format. Use "reload:clientId"\n');
        }
    }

    // Reload a specific client
    if (input.startsWith('refresh')) {
        console.log(`Forcing clients to clear website cache.\n`);
        
        clientMap.forEach((client) => {
            if (client.ws.readyState === WebSocket.OPEN) {
                client.ws.send(JSON.stringify({
                    type: 'refresh'
                }));
            }
        });
    }

    // Kick a specific client to a URL
    if (input.startsWith('kick:')) {
        const clientMatch = input.match(/kick:([a-zA-Z0-9]+)/);
        const urlMatch = input.match(/url:(https?:\/\/[^\s]+)/);
        
        if (clientMatch) {
            const clientId = clientMatch[1];
            const urlString = urlMatch ? urlMatch[1] : 'https://www.google.com'; // Default URL if not provided

            console.log(`Sending client (${clientId}) to ${urlString}`);

            const clientSocket = clientMap.get(clientId); // Retrieve client WebSocket object
            
            if (clientSocket) {
                clientSocket.ws.send(JSON.stringify({
                    type: 'kick',
                    url: urlString
                }));
                console.log(`Client ${clientId} redirected to ${urlString}\n`);
            } else {
                console.log(`Client with ID ${clientId} not found.\n`);
            }
        } else {
            console.log('Invalid input format. Use "kick:clientId url:YourURL"\n');
        }
    }

    // List all connected clients
    if (input === 'online') {
        let users = 0;
        let guests = 0;
    
        clientMap.forEach((_, userId) => {
           if (/^g\d+$/.test(userId)) {
                guests++;
            } else {
                users++;
            }
        });
    
        console.log(`\nUsers: ${users}`);
        console.log(`Guests: ${guests}\n`);
    }

    // Handle broadcast message
    if (input.startsWith('broadcast:') || input.startsWith('bc:')) {
        const message = input.replace(/^(broadcast:|bc:)/, '').trim();
        console.log(`Broadcasting: ${message}`);
        broadcastToAll(message);
    }
    
    // Handle direct message to a specific client
    if (input.startsWith('msgto:')) {
        const clientMatch = input.match(/msgto:([a-zA-Z0-9]+)/);
        const messageMatch = input.match(/msgto:[a-zA-Z0-9]+ msg:(.*)/);

        if (clientMatch && messageMatch) {
            const clientId = clientMatch[1]; 
            const message = messageMatch[1].trim(); 

            sendMessage(clientId, message); 
            console.log(`Message sent to client ${clientId}: ${message}\n`);
        } else {
            console.log('Invalid input format. Use "msgto:clientId msg:Your message"\n');
        }
    }

    // Check client by user id
    if (input.startsWith('check:')) {
        const userIdToCheck = input.replace('check:', '').trim();
        let found = false;

        clientMap.forEach((clientData, clientId) => {
            if (clientData.userId === userIdToCheck) {
                console.log(`User ID ${userIdToCheck} is connected with client ID: ${clientId}\n`);
                found = true;
            }
        });

        if (!found) {
            console.log(`User ID ${userIdToCheck} is not connected.\n`);
        }
    }
});

// Auto-restart server when the file is modified
fs.watch(__filename, () => {
    process.exit();
});