// Prioritixe loading speed by loading images "lazy"
document.addEventListener("DOMContentLoaded", function() {
    const images = document.querySelectorAll('img');
    images.forEach(img => {
        img.setAttribute('loading', 'lazy');
    });

    updateBackground();
    
    const cloudsContainer = document.getElementById('clouds');

    for (let i = 0; i < 25; i++) {
        let cloud = document.createElement('div');
        cloud.className = 'cloud';
        cloudsContainer.appendChild(cloud);

        let img = document.createElement('img');
        img.src = 'assets/images/cloud.png';
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
});

function updateBackground() {
    const now = new Date();
    const hours = now.getHours();
    const minutes = now.getMinutes();
    const totalMinutes = hours * 60 + minutes;
    let gradient;

    if (totalMinutes >= 300 && totalMinutes <= 420) { // Dawn: 5:00 AM - 7:00 AM
        gradient = "linear-gradient(to top, #feb47b 0%, #ff7e5f 100%)"; // Warm orange
    } else if (totalMinutes > 420 && totalMinutes <= 720) { // Morning: 7:01 AM - 12:00 PM
        gradient = "linear-gradient(to top, #fbd786 0%, #6dd5ed 100%)"; // Light yellow to light blue
    } else if (totalMinutes > 720 && totalMinutes <= 1080) { // Afternoon: 12:01 PM - 6:00 PM
        gradient = "linear-gradient(to top, #48c6ef 0%, #6f86d6 100%)"; // Light blue
    } else if (totalMinutes > 1080 && totalMinutes <= 1260) { // Evening: 6:01 PM - 9:00 PM
        gradient = "linear-gradient(to top, #4e4376 0%, #2b5876 100%)"; // Deep sunset purple
    } else { // Night: 9:01 PM - 4:59 AM
        gradient = "linear-gradient(to top, #243B55 0%, #141E30 100%)"; // Dark blue night
    }

    document.body.style.backgroundColor = gradient;
    document.getElementById('welcome-screen').style.backgroundColor = gradient;
}
//setInterval(updateBackground, 60000); // Update every minute

function toggleDarkMode() {
    const body = document.body;
    const darkModeButton = document.getElementById('dark-mode-toggle');
    const darkModeIcon = darkModeButton.querySelector('i');
    const allText = document.querySelectorAll('p, h1, h2, h3, h4, h5, h6, a, span, li, td, th, label, input, select, option, button, textarea');
    const allIcons = document.querySelectorAll('i');
    const allPlaceholders = document.querySelectorAll('input, textarea');
    
    const logoH1 = document.querySelector('.logo-name h1');
    const logoP = document.querySelector('.logo-name p');

    clearInterval(updateBackground); // Stop updating the background gradient

    if (body.classList.contains('light-mode')) {
        body.classList.add('light-mode');
        body.classList.remove('dark-mode');
        darkModeIcon.classList.remove('fa-moon');
        darkModeIcon.classList.add('fa-sun');
        allText.forEach(text => text.style.color = 'black');
        allIcons.forEach(icon => icon.style.color = 'black');
        allPlaceholders.forEach(placeholder => {
            placeholder.style.color = 'black';
            placeholder.style.setProperty('::placeholder', 'color', 'black');
        });
    } else {
        body.classList.add('dark-mode');
        body.classList.remove('light-mode');
        darkModeIcon.classList.remove('fa-sun');
        darkModeIcon.classList.add('fa-moon');
        allText.forEach(text => text.style.color = 'white');
        allIcons.forEach(icon => icon.style.color = 'white');
        allPlaceholders.forEach(placeholder => {
            placeholder.style.color = 'white';
            placeholder.style.setProperty('::placeholder', 'color', 'white');
        });
    }

    logoH1.style.color = 'white';
    logoP.style.color = 'white';
}

function timeAgo(timestamp) {
    const currentTimestamp = Math.floor(Date.now() / 1000);
    const secondsAgo = currentTimestamp - timestamp;

    if (secondsAgo < 60) {
        return `${secondsAgo} second${secondsAgo === 1 ? '' : 's'} ago`;
    } else if (secondsAgo < 3600) {
        const minutesAgo = Math.floor(secondsAgo / 60);
        return `${minutesAgo} minute${minutesAgo === 1 ? '' : 's'} ago`;
    } else if (secondsAgo < 86400) {
        const hoursAgo = Math.floor(secondsAgo / 3600);
        return `${hoursAgo} hour${hoursAgo === 1 ? '' : 's'} ago`;
    } else {
        const daysAgo = Math.floor(secondsAgo / 86400);
        return `${daysAgo} day${daysAgo === 1 ? '' : 's'} ago`;
    }
}

function checkData() {
    const new_users = document.getElementById('new_users');
    $.ajax({
        url: 'assets/update.php',
        type: "POST",
        data: {
            
        }
    }).done(function(response) {
        const data = JSON.parse(response);

        for (const key in data) {
            if (data.hasOwnProperty(key)) {
                const username = key;
                const registered = data[key].reg_date;

                const newUserDiv = document.createElement('div');
                newUserDiv.classList.add('new_user');
                const newUserLeftDiv = document.createElement('div');
                newUserLeftDiv.classList.add('new_user_left');
                const newUserNameDiv = document.createElement('div');
                newUserNameDiv.setAttribute('id', 'new_user_name');
                const newUserTimeDiv = document.createElement('div');
                newUserTimeDiv.setAttribute('id', 'new_user_time');
                newUserLeftDiv.appendChild(newUserNameDiv);
                newUserLeftDiv.appendChild(newUserTimeDiv);
                const newUserRightDiv = document.createElement('div');
                newUserRightDiv.classList.add('new_user_right');
                const icon = document.createElement('i');
                icon.classList.add('fa-solid', 'fa-circle-user');
                newUserRightDiv.appendChild(icon);
                newUserDiv.appendChild(newUserLeftDiv);
                newUserDiv.appendChild(newUserRightDiv);
                
                newUserNameDiv.textContent = `${username}`;
                newUserTimeDiv.textContent = timeAgo(`${registered}`);
                new_users.appendChild(newUserDiv);
                newUserDiv.style.opacity = '1';

                setTimeout(() => {
                    hideNewUsers();
                }, 3000);
            }
        }
    });
}

function hideNewUsers() {
    const newUserElements = document.querySelectorAll('.new_user');

    function fadeOutAndRemove(element, duration) {
        let opacity = 1;
        const interval = 50; // Duration of each step in milliseconds

        const fadeOutInterval = setInterval(() => {
            opacity -= interval / duration;
            element.style.opacity = opacity;

            if (opacity <= 0) {
                clearInterval(fadeOutInterval);
                element.style.display = 'none';
            }
        }, interval);
    }

    newUserElements.forEach((element, index) => {
        setTimeout(() => {
            fadeOutAndRemove(element, 1000); // Adjust the duration as needed
        }, index * 2000); // Adjust the delay between elements as needed
    });
}


/// / /// / /// / /// / /// / /// / /// / /// / /// / /// / ///
// SECURITY

let dynamicUpdateInProgress = false;

// Function to refresh the page
function refreshPage() {
    //location.reload();
}

// Function to observe DOM changes
function observeDOMChanges() {
    const targetNode = document.documentElement; // Monitor the entire document

    const config = {
        attributes: true, 
        childList: true, 
        subtree: true,
        characterData: true // Also monitor text changes
    };

    const callback = function(mutationsList, observer) {
        if (dynamicUpdateInProgress) {
            return;
        }

        for (const mutation of mutationsList) {
            if (mutation.type === 'childList' || mutation.type === 'attributes' || mutation.type === 'characterData') {
                // Check if the mutation was caused by a script
                if (mutation.target.ownerDocument === document) {
                    //console.log('Detected modification in the DOM by a person/client');
                    refreshPage();
                    break;
                }
            }
        }
    };

    const observer = new MutationObserver(callback);
    observer.observe(targetNode, config);
}

// Function to safely update DOM elements
function safelyUpdateDOM(callback) {
    dynamicUpdateInProgress = true;
    try {
        callback();
    } finally {
        dynamicUpdateInProgress = false;
    }
}

// Start observing DOM changes when the page is loaded
window.addEventListener('load', observeDOMChanges);

// Example of dynamically updating the DOM safely
// safelyUpdateDOM(() => {
//     // Your dynamic update code here
// });


/// / /// / /// / /// / /// / /// / /// / /// / /// / /// / ///
//
//fetch('https://dev.skybyn.no/monitor', {
//    method: 'POST',
//    headers: {
//        'Content-Type': 'application/json',
//    },
//    body: JSON.stringify({
//        message: 'Hello from dev.skybyn.com!'
//    }),
//})
//.then(response => response.json())
//.then(data => console.log('Success:', data))
//.catch((error) => console.error('Error:', error));
