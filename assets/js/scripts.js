// Prioritixe loading speed by loading images "lazy"
document.addEventListener("DOMContentLoaded", function() {
    const images = document.querySelectorAll('img');
    images.forEach(img => {
        img.setAttribute('loading', 'lazy');
    });
});

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
