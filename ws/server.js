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
    console.warn('+--------------------------+');
    console.warn('| Secure Web Socket Server |');
    console.warn('+--------------------------+');
    console.info("| Listening on port: 4433  |");
});

const dbConfig = {
    host: process.env.DB_HOST,
    user: process.env.DB_USER,
    password: process.env.DB_PASSWORD,
    database: process.env.DB_NAME,
    multipleStatements: true
};

let db;

function handleDisconnect() {
    db = mysql.createConnection(dbConfig);
    
    db.connect(err => {
        if (err) {
            console.error("| Database: FAILED         |\nReason:\n", err, "\n\n");
            setTimeout(handleDisconnect, 5000); // Retry connection after 5 seconds
        } else {
            console.info("| Database: CONNECTED      |\n");
        }
    });

    db.on("error", err => {
        if (err.code === "PROTOCOL_CONNECTION_LOST") {
            console.error("⚠️ Database connection lost. Reconnecting...\n");
            handleDisconnect();
        } else {
            throw err;
        }
    });
}

handleDisconnect();

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

function getUserIdFromToken(token) {
    return new Promise((resolve) => {
        db.query("SELECT id FROM users WHERE token = ?", [token], (err, results) => {
            if (err) {
                console.error("Error checking token:", err);
                resolve(null);
                return;
            }
            resolve(results.length > 0 ? results[0].id : null);
        });
    });
}

function getUsernameFromId(userId) {
    return new Promise((resolve) => {
        db.query("SELECT username FROM users WHERE id = ?", [userId], (err, results) => {
            if (err) {
                console.error("Error fetching username:", err);
                resolve("");
                return;
            }
            resolve(results.length > 0 ? results[0].username : "");
        });
    });
}

function getTime() {
    const now = new Date();
    const hours = now.getHours().toString().padStart(2, '0');
    const minutes = now.getMinutes().toString().padStart(2, '0');
    const seconds = now.getSeconds().toString().padStart(2, '0');
    const day = now.getDate().toString().padStart(2, '0');
    const month = (now.getMonth() + 1).toString().padStart(2, '0');
    const year = now.getFullYear();
    return `[${day}/${month}/${year} ${hours}:${minutes}:${seconds}]`;
}

// Store client subscriptions
let subscriptions = {};

const wss = new WebSocket.Server({ server });
const clientMap = new Map(); // Map to store clients by their unique identifier

// Handle WebSocket connections
wss.on('connection', (ws) => {
    const cleanedIp = ws._socket.remoteAddress.replace(/^::ffff:/, '');

    ws.on('message', (message, isBinary) => {
        if (isBinary) {
            // Broadcast the binary message (video frame) to all connected clients
            clientMap.forEach((client) => {
                if (client.ws.readyState === WebSocket.OPEN && client.ws !== ws) {
                    client.ws.send(message, { binary: true });
                }
            });
        } else
        if (isJsonString(message)) {
            let data = JSON.parse(message);

            if (data.type === 'get_user_id') {
                let token = data.token;

                getUserIdFromToken(token).then(userId => {
                    if (userId) {
                        ws.send(JSON.stringify({
                            type: 'user_id',
                            userId: userId
                        }));
                    } else {
                        ws.send(JSON.stringify({
                            type: 'user_id',
                            userId: null
                        }));
                    }
                });
            }

            if (data.type === 'connect') {
                let clientID = data.sessionId;
                let token = data.token;
                let url = data.url;
                let device = data.deviceInfo['device'];
                let time = getTime();

                getUserIdFromToken(token).then(userId => {
                    let guestCount = 0;
                    wss.clients.forEach(client => {
                        if (client.readyState === WebSocket.OPEN && !client.userid) {
                            guestCount++;
                        }
                    });
                    
                    if (userId === null) {
                        let guest = "g" + guestCount;
                        let logType = "Guest connected: " + guest;
                        clientMap.set(guest, { ws, ip: cleanedIp, sessionID: clientID });
                        console.log(`${time}\n${logType}\nIP: ${cleanedIp}\nUrl: ${url}\nDevice: ${device}\n`);
                        updateActiveClients();
                    } else {
                        getUsernameFromId(userId).then(username => {
                            let logType = "User online: " + username;
                            clientMap.set(userId, { ws, ip: cleanedIp, sessionID: clientID });
                            console.log(`${time}\n${logType}\nIP: ${cleanedIp}\nUrl: ${url}\nDevice: ${device}\n`);
                            updateActiveClients();
                        });
                    }
                });
            }

            if (data.type === 'browser_perm') {
                let device = data.device;
                let status = data.status;

                console.log(data);
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
                console.log("Someone posted");
                clientMap.forEach((client) => {
                    if (client.ws.readyState === WebSocket.OPEN) {
                        client.ws.send(broadcastMessage);
                    }
                });
            }

            if (data.type === 'delete_post') {
                let postId = data.id;
                let broadcastMessage = JSON.stringify({
                    type: 'delete_post',
                    id: postId
                });
                clientMap.forEach((client) => {
                    if (client.ws.readyState === WebSocket.OPEN) {
                        client.ws.send(broadcastMessage);
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
                clientMap.forEach((client) => {
                    if (client.ws.readyState === WebSocket.OPEN) {
                        client.ws.send(broadcastMessage);
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
                clientMap.forEach((client) => {
                    if (client.ws.readyState === WebSocket.OPEN) {
                        client.ws.send(broadcastMessage);
                    }
                });
            }

            if (data.type === 'push_subscription') {
                let userId = data.userId;
                
                if (!data.subscription) {
                    console.error(`❌ Subscription is undefined for ${userId}`);
                    return;
                }
            
                console.log(`Storing push subscription for: ${userId}`);
                
                // Save subscription to the database
                savePushSubscription(userId, data.subscription);
                
                ws.send(JSON.stringify({ type: 'push_subscribed', userId }));
            }                 

            if (data.type === 'chat') {
                const { id, from, to, message: messageText } = data;
                const payload = JSON.stringify({
                    title: 'New Message!',
                    body: messageText,
                    icon: 'https://skybyn.com/assets/images/logo_faded_clean.png',
                });

                async function sendPushNotification(userId, title, message) {
                    try {
                        // Fetch subscription from database
                        const query = "SELECT endpoint, auth, p256dh FROM push_subscriptions WHERE user_id = ?";
                        db.query(query, [userId], (err, results) => {
                            if (err) {
                                console.error(`❌ Error fetching push subscription for ${userId}:`, err);
                                return;
                            }
                
                            if (results.length === 0) {
                                console.log(`❌ No push subscription found for user ${userId}`);
                                return;
                            }
                
                            const subscription = {
                                endpoint: results[0].endpoint,
                                keys: {
                                    auth: results[0].auth,
                                    p256dh: results[0].p256dh
                                }
                            };
                
                            const payload = JSON.stringify({
                                title: title || 'Notification',
                                body: message || 'You have a new notification!',
                                icon: 'https://skybyn.com/assets/images/logo_faded_clean.png'
                            });
                
                            webPush.sendNotification(subscription, payload).catch(error => {
                                console.error(`❌ Error sending push notification to ${userId}:`, error);
                
                                if (error.statusCode === 410) { // Subscription expired
                                    console.log(`🗑️ Removing invalid subscription for ${userId}`);
                                    db.query("DELETE FROM push_subscriptions WHERE user_id = ?", [userId]);
                                }
                            });
                        });
                    } catch (error) {
                        console.error(`❌ Error sending push notification to ${userId}:`, error);
                    }
                }

                //sendPushNotification('user123', 'New Message', 'Hello, this is a test notification!');

                const sendMsg = JSON.stringify({
                    type: 'chat',
                    id,
                    from,
                    to,
                    message: messageText,
                });

                let sent = false;
                clientMap.forEach((client, userId) => {
                    if (userId === to && client.ws.readyState === WebSocket.OPEN) {
                        client.ws.send(sendMsg);
                        sent = true;
                    }
                });

                if (!sent) {
                    console.log(`Client ${to} is not connected.`);
                }
            }

            if (data.type === 'new_notification') {
                const payload = JSON.stringify({
                    title: 'Notification',
                    body: data.message,
                    icon: 'https://skybyn.com/assets/images/logo_faded_clean.png',
                });

                subscriptions.forEach((subscription, index) => {
                    webPush.sendNotification(subscription, payload).catch((error) => {
                        console.error('Error sending notification:', error);
                        if (error.statusCode === 410) {
                            subscriptions.splice(index, 1);
                        }
                    });
                });
            }

            if (data.type === 'pong') {
                clientMap.forEach((client, userId) => {
                    if (client.ws === ws) {
                        clientMap.set(userId, { ws, ip: cleanedIp, userId });
                    }
                });
            }

            else {
                if (!Buffer.isBuffer(message)) {
                    console.log(message);
                }
            }
        }
    });

    ws.on('close', (code, reason) => {
        const clientID = [...clientMap.entries()].find(([_, client]) => client.ws === ws)?.[0];
        if (clientID) {
            clientMap.delete(clientID);
            console.log(`Client disconnected: ${clientID}\n`);
            updateActiveClients();
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

function pingPong() {
    let pingPong = JSON.stringify({ type: 'ping' });
    clientMap.forEach((client) => {
        client.ws.send(pingPong);
    });
}

setInterval(pingPong, 5000);

// Broadcast messages to all connected WebSocket clients
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

function sendPushToAll(title, message) {
    const query = "SELECT `user_id` FROM `push_subscriptions`";
    
    db.query(query, (err, results) => {
        if (err) {
            console.error("Error fetching users:", err);
            return;
        }

        results.forEach(row => {
            sendPushNotification(row.user_id, title, message);
        });
    });
}

// Example Usage:
// sendPushToAll('System Update', 'We have a new update available!');

// Send message to client
function sendMessage(clientId, message) {
    const clientSocket = clientMap.get(clientId);
    if (clientSocket && clientSocket.ws.readyState === WebSocket.OPEN) {
        clientSocket.ws.send(JSON.stringify({
            type: 'broadcast',
            message: message
        }));
        console.log(`Message sent to client ${clientId}`);
    } else {
        console.log(`Cannot send message. Client ${clientId} not found or WebSocket not open.`);
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

function sendPushNotification(userId, title, message) {
    const query = "SELECT endpoint, auth, p256dh FROM push_subscriptions WHERE user_id = ?";
    
    db.query(query, [userId], (err, results) => {
        if (err) {
            console.error(`❌ Error fetching push subscription for ${userId}:`, err);
            return;
        }

        if (results.length === 0) {
            console.log(`⚠️ No push subscription found for user ${userId}`);
            return;
        }

        const subscription = {
            endpoint: results[0].endpoint,
            keys: {
                auth: results[0].auth,
                p256dh: results[0].p256dh
            }
        };

        const payload = JSON.stringify({
            title: title || 'Notification',
            body: message || 'You have a new notification!',
            icon: 'https://skybyn.com/assets/images/logo_faded_clean.png'
        });

        webPush.sendNotification(subscription, payload).catch(error => {
            console.error(`❌ Error sending push notification to ${userId}:`, error);

            if (error.statusCode === 410) { // 410 = Subscription expired
                console.log(`🗑️ Removing invalid subscription for ${userId}`);
                db.query("DELETE FROM push_subscriptions WHERE user_id = ?", [userId]);
            }
        });
    });
}

function savePushSubscription(userId, subscription) {
    if (!userId || !subscription || !subscription.endpoint || !subscription.keys.auth || !subscription.keys.p256dh) {
        console.error("❌ Invalid subscription data");
        return;
    }

    // Validate if the user exists in the database before saving subscription
    db.query("SELECT id FROM users WHERE id = ?", [userId], (err, results) => {
        if (err) {
            console.error("❌ Error checking user ID:", err);
            return;
        }

        if (results.length === 0) {
            console.error(`❌ User ID ${userId} does not exist in database. Subscription not saved.`);
            return;
        }

        const query = `
            INSERT INTO push_subscriptions (user_id, endpoint, auth, p256dh)
            VALUES (?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE endpoint=?, auth=?, p256dh=?`;
        
        const values = [
            userId, subscription.endpoint, subscription.keys.auth, subscription.keys.p256dh,
            subscription.endpoint, subscription.keys.auth, subscription.keys.p256dh
        ];

        db.query(query, values, (err) => {
            if (err) {
                console.error("❌ Error saving subscription:", err);
            } else {
                console.log(`✅ Subscription saved for user ${userId}`);
            }
        });
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
        console.log('subscribers - Show total push notification subscribers');
        console.log('list_subscribers - List all push notification subscribers');
        console.log('broadcast:message, bc:message - Send message to all clients');
        console.log('msgto:clientId msg:message - Send message to specific client');
        console.log('push:clientId msg:message - Send push notification to specific user');
        console.log('push_all:message - Send push notification to all subscribers');
        console.log('reload:clientId - Force reload specific client');
        console.log('refresh - Force all clients to clear cache');
        console.log('kick:clientId url:URL - Redirect client to specified URL');
        console.log('check:userId - Check if user is connected\n');
    }
    
    // Clear the console
    if (input === 'cls' || input === 'clear') {
        console.clear();
    }

    // Send push notification to a specific user
    if (input.startsWith('push:')) {
        const clientMatch = input.match(/push:([a-zA-Z0-9]+)/);
        const messageMatch = input.match(/push:[a-zA-Z0-9]+ msg:(.*)/);

        if (clientMatch && messageMatch) {
            const clientId = clientMatch[1]; // Extract client ID
            const message = messageMatch[1].trim(); // Extract the message

            console.log(`Sending push notification to ${clientId}: ${message}`);
            
            sendPushNotification(clientId, 'New Notification', message);
        } else {
            console.log('Invalid input format. Use: push:clientId msg:Your message');
        }
    }

    // Count all subscribers
    if (input === "subscribers") {
        const query = "SELECT COUNT(*) AS count FROM push_subscriptions";
        
        db.query(query, (err, results) => {
            if (err) {
                console.error("❌ Error fetching subscribers:", err);
                return;
            }
    
            console.log(`Total Push Subscribers: ${results[0].count}`);
        });
    }
    
    // List all subscribers
    if (input === "list_subscribers") {
        const query = "SELECT user_id FROM push_subscriptions";
        
        db.query(query, (err, results) => {
            if (err) {
                console.error("❌ Error fetching subscriber list:", err);
                return;
            }
    
            console.log("📜 List of Subscribers:");
            results.forEach(row => {
                console.log(`- ${row.user_id}`);
            });
        });
    }

    // Send push notification to all subscribers
    if (input.startsWith("push_all:")) {
        const message = input.replace("push_all:", "").trim();
        
        const query = "SELECT user_id FROM push_subscriptions";
        
        db.query(query, (err, results) => {
            if (err) {
                console.error("❌ Error fetching users:", err);
                return;
            }
    
            results.forEach(row => {
                sendPushNotification(row.user_id, "Global Notification", message);
            });
    
            console.log("📢 Sent push notification to all subscribers.");
        });
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
                console.log(`Client with ID ${clientId} not found.`);
            }
        } else {
            console.log('Invalid input format. Use "reload:clientId"');
        }
    }

    // Reload a specific client
    if (input.startsWith('refresh')) {
        console.log(`Forcing clients to clear website cache.`);
        
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
            } else {
                console.log(`Client with ID ${clientId} not found.`);
            }
        } else {
            console.log('Invalid input format. Use "kick:clientId url:YourURL"');
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
        } else {
            console.log('Invalid input format. Use "msgto:clientId msg:Your message"');
        }
    }

    // Check client by user id
    if (input.startsWith('check:')) {
        const userIdToCheck = input.replace('check:', '').trim();
        let found = false;

        clientMap.forEach((clientData, clientId) => {
            if (clientData.userId === userIdToCheck) {
                console.log(`User ID ${userIdToCheck} is connected with client ID: ${clientId}`);
                found = true;
            }
        });

        if (!found) {
            console.log(`User ID ${userIdToCheck} is not connected.`);
        }
    }
});

// Auto-restart server when the file is modified
fs.watch(__filename, () => {
    process.exit();
});