<?php include "./assets/functions.php";

if (!isset($_SESSION['user'])) {
    header("Location: ../");
    exit();
}

$msgId = rand(1000, 9999);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Broadcast message</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        #status {
            margin-bottom: 20px;
            color: green;
        }
        #messages {
            border: 1px solid #ccc;
            padding: 10px;
            height: 200px;
            overflow-y: scroll;
            margin-bottom: 20px;
        }
        select {
            padding: 10px;
            margin-right: 10px;
        }
        #inputMessage {
            width: 80%;
            padding: 10px;
        }
        #sendBtn {
            padding: 10px 20px;
        }
    </style>
</head>
<body>

<h1>Send message</h1>
<div>Logged in as <?php echo getUser('id',$uid,'username'); ?></div>
<div id="status">Server is offline</div>
<div id="messages"></div>
<select id="type" onchange="document.getElementById('users').hidden = this.value !== 'client'">
    <option value="broadcast">Broadcast</option>
    <option value="client">Client</option>
</select>
<select id="users" hidden>
    <?php
    $getUsers = $conn->query("SELECT * FROM `users`");
    while ($user = $getUsers->fetch_assoc()) {
        $userid = $user['id'];
        $username = $user['username'];
        echo "<option value='$userid'>$username</option>";
    }
    ?>
</select>
<input type="text" id="inputMessage" placeholder="Enter your message..." autofocus>
<button id="sendBtn" disabled>Send</button>

<script>
    const statusDiv = document.getElementById('status');
    const messagesDiv = document.getElementById('messages');
    const inputMessage = document.getElementById('inputMessage');
    const sendBtn = document.getElementById('sendBtn');
    const typeSelect = document.getElementById('type');
    const usersSelect = document.getElementById('users');

    // Connect to the WebSocket server
    const ws = new WebSocket('wss://dev.skybyn.no:4433');
    ws.onopen = () => {
        statusDiv.innerText = 'Server is online';
        sendBtn.disabled = false;
    };

    const msgId = <?php echo $msgId; ?>;

    // Handle incoming messages
    ws.onmessage = (event) => {
        const data = JSON.parse(event.data);
        const type = data.type;
        if (type == 'broadcast_response') {
            const id = data.id;
            if (id == msgId) {
                const message = data.message;
                messagesDiv.innerHTML += `<p>${message}</p>`;
            }
        } else if (type == 'client_response') {
            const id = data.id;
            if (id == msgId) {
                const message = data.message;
                messagesDiv.innerHTML += `<p>${message}</p>`;
            }
        }
    };

    // Send JSON message to the server
    sendBtn.addEventListener('click', () => {
        const message = inputMessage.value;
        const type = typeSelect.value;
        const userId = usersSelect.value;
        const data = {
            type: type,
            id: msgId,
            message: message,
        };
        ws.send(JSON.stringify(data));
        inputMessage.value = '';
    });
</script>

</body>
</html>
