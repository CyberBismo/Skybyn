document.addEventListener('DOMContentLoaded', function () {
    const cloudsContainer = document.getElementById('clouds');

    // Generate a specified number of clouds
    for (let i = 0; i < 50; i++) {
        let cloud = document.createElement('div');
        cloud.className = 'cloud';
        cloudsContainer.appendChild(cloud);

        // Random size and position
        cloud.style.width = `${Math.random() * 100 + 100}px`; // Cloud width between 100px and 200px
        cloud.style.height = `${Math.random() * 50 + 30}px`; // Cloud height between 30px and 80px
        cloud.style.top = `${Math.random() * (window.innerHeight - 50)}px`; // Random vertical position
        cloud.style.left = `${Math.random() * window.innerWidth}px`; // Random horizontal position across the entire screen width

        // Randomize the direction
        let direction = Math.random() > 0.5 ? 1 : -1; // Choose direction: 1 for right, -1 for left

        animateCloud(cloud, direction);
    }

    function animateCloud(cloud, direction) {
        let speed = Math.random() * 0.05 + 0; // Slower speed between 0.2 and 1.2

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
});
