<style>
    #start-btn {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 50%;
        cursor: pointer;
    }
    #start-btn:hover {
        transform: translate(-50%, -50%) rotate(1440deg);
        transition: transform 8s;
    }
</style>

<div class="start">
    <div class="start_logo"></div>
    <img src="assets/images/rimeet.png" class="start_logo">
    <img src="assets/images/rim.png" id="start-btn">
    <audio id="engineSound" src="start/start.mp3"></audio>
</div>

<script>
    const startBtn = document.getElementById('start-btn');
    const startLogo = document.querySelector('.start_logo');
    const url = "../rimeet"

    function resizeImage() {
        startBtn.style.height = `${startBtn.offsetWidth}px`;
    }

    // Initial resize
    resizeImage();

    // Resize on window resize
    window.addEventListener('resize', resizeImage);

    document.getElementById('start-btn').addEventListener('click', function() {
        var audioContext = new (window.AudioContext || window.webkitAudioContext)();

        // Ensure the audio context is resumed
        if (audioContext.state === 'suspended') {
            audioContext.resume();
        }

        fetch('assets/start.mp3')
            .then(response => response.arrayBuffer())
            .then(data => audioContext.decodeAudioData(data))
            .then(buffer => {
                var source = audioContext.createBufferSource();
                var gainNode = audioContext.createGain();

                source.buffer = buffer;
                source.connect(gainNode);
                gainNode.connect(audioContext.destination);

                // Play the sound
                source.start(0);

                // Start the animation
                startLogo.style.animation = 'fadeOut 1s forwards';
                startBtn.style.animation = 'fadeOut 1s forwards';

                // Hide the logo and button
                setTimeout(() => {
                    startLogo.style.opacity = 0;
                    startBtn.style.opacity = 0;
                    setTimeout(() => {
                    document.cookie = "start=true; path=/";
                    window.location.href = "./";
                    }, 1000);
                }, 1500);

                // Schedule the fade out
                gainNode.gain.setValueAtTime(1, audioContext.currentTime + 3);
                gainNode.gain.exponentialRampToValueAtTime(0.001, audioContext.currentTime + 4);

                // Stop the sound after 4 seconds
                source.stop(audioContext.currentTime + 4);
            })
            .catch(e => console.error(e));

        // Toggle full-screen mode
        function toggleFullScreen() {
            if (!document.fullscreenElement) {
            document.documentElement.requestFullscreen();
            } else {
            if (document.exitFullscreen) {
                document.exitFullscreen();
            }
            }
        }
    });
</script>
