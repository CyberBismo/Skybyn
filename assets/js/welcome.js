window.addEventListener('DOMContentLoaded', function() {
    const welcomeInner = document.getElementById('welcome-inner');

    setTimeout(function() {
        welcomeInner.classList.add('show');
    }, 1000);

    const welcomeClouds = document.getElementById('welcome-clouds');

    for (let i = 0; i < 10; i++) {
        let cloud = document.createElement('div');
        cloud.className = 'cloud';
        welcomeClouds.appendChild(cloud);

        let img = document.createElement('img');
        img.src = '../assets/images/cloud.png';
        cloud.appendChild(img);

        cloud.style.top = `${Math.random() * (window.innerHeight - 50)}px`;
        cloud.style.left = `${Math.random() * window.innerWidth}px`;

        img.style.width = `${Math.random() * 300 + 25}px`;

        let direction = Math.random() > 0.5 ? 1 : -1;
        
        if (Math.random() > 0.5) {
            cloud.style.transform = 'scaleX(-1)';
        }

        animateCloud(cloud, direction);
    }

    function animateCloud(cloud, direction) {
        let speed = Math.random() * 0.25 + 0; // Slower speed between 0.2 and 1.2

        function moveCloud() {
            let pos = parseFloat(cloud.style.left);
            pos += direction * speed;
            cloud.style.left = `${pos}px`;

            // Check if cloud has moved beyond the screen and reset its position
            if (direction === 1 && pos > window.innerWidth) {
                // Reset to start from the left side
                cloud.style.left = `${-cloud.offsetWidth}px`;
            } else if (direction === -1 && pos + cloud.offsetWidth < 0) {
                // Reset to start from the right side
                cloud.style.left = `${window.innerWidth}px`;
            }
            requestAnimationFrame(moveCloud);
        }

        moveCloud();
    }

    setTimeout(() => {
        hideWelcome();
    }, 3500);
});

function hideWelcome() {
    const welcomeScreen = document.getElementById('welcome-screen');
    const login_email = document.getElementById('login-email');

    welcomeScreen.style.pointerEvents = "none";
    welcomeScreen.style.opacity = "0";

    setTimeout(() => {
        if (welcomeScreen) {
            welcomeScreen.remove();
        }
    }, 1000);
    if (login_email) {
        login_email.focus();
    }
}