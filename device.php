<html>
    <head>
        <title>Device Info</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                margin: 0;
                padding: 0;
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                height: 100vh;
                background-color: #f0f0f0;
            }

            .device {
                background-color: #fff;
                padding: 20px;
                border-radius: 5px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            }
        </style>
    </head>
    <body>
        <h1>Device Type</h1>
        <div id="device" class="device"></div>

        <script>
        document.getElementById('device').innerHTML = `
            <p><strong>Device:</strong> ${navigator.userAgent}</p>
            <p><strong>Platform:</strong> ${navigator.platform}</p>
            <p><strong>Language:</strong> ${navigator.language}</p>
            <p><strong>Cookie enabled:</strong> ${navigator.cookieEnabled}</p>
        `;
        </script>
    </body>
</html>