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
        <h1>Device Info</h1>
        <div id="device" class="device"></div>

        <script>
        if (navigator.userAgent.includes("Tesla") && navigator.userAgent.includes("Linux")) {
            console.log("Tesla browser detected");
        } else {
            console.log("Non-Tesla browser detected");
        }

        const device = document.getElementById('device');

        device.innerHTML = navigator.userAgent.includes("Tesla") ? 'Tesla' : 'Non-Tesla';
        </script>
    </body>
</html>