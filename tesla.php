<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tesla Browser Feature Check</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin-top: 50px;
        }
        #tesla-browser {
            display: none;
            padding: 20px;
        }
    </style>
    <script>
        function detectLimitedEmbeddedBrowser() {
            const features = {
                webRTC: typeof RTCPeerConnection !== "undefined",
                webGL: (() => {
                    try {
                        const canvas = document.createElement("canvas");
                        return !!canvas.getContext("webgl") || !!canvas.getContext("experimental-webgl");
                    } catch (e) {
                        return false;
                    }
                })(),
                serviceWorker: "serviceWorker" in navigator,
                touchSupport: "ontouchstart" in window || navigator.maxTouchPoints > 0,
                webAssembly: typeof WebAssembly === "object",
                localStorage: (() => {
                    try {
                        localStorage.setItem("test", "1");
                        localStorage.removeItem("test");
                        return true;
                    } catch (e) {
                        return false;
                    }
                })(),
                audioContext: typeof (window.AudioContext || window.webkitAudioContext) !== "undefined"
            };

            const featureScore = Object.values(features).filter(v => v).length;
            const likelyTesla = featureScore < 5; // threshold can be tuned based on more tests

            return { likelyTesla, features };
        }

        function detectTeslaBrowser() {
            const teslaBrowserDiv = document.getElementById("tesla-browser");
            const { likelyTesla, features } = detectLimitedEmbeddedBrowser();

            const featureList = Object.entries(features)
                .map(([key, value]) => `<li><strong>${key}:</strong> ${value ? "✅" : "❌"}</li>`)
                .join("");

            teslaBrowserDiv.innerHTML = `
                <h1>${likelyTesla ? "Welcome to the Tesla Browser!" : "Not a Tesla Browser"}</h1>
                <p>Feature Detection Summary:</p>
                <ul style="text-align: left; display: inline-block;">${featureList}</ul>
            `;
            teslaBrowserDiv.style.display = "block";
        }
    </script>
</head>
<body onload="detectTeslaBrowser()">
    <div id="tesla-browser">
        <h1>Detecting Tesla Browser...</h1>
    </div>
</body>
</html>
