// Prioritixe loading speed by loading images "lazy"
document.addEventListener("DOMContentLoaded", function() {
    const images = document.querySelectorAll('img');
    images.forEach(img => {
        img.setAttribute('loading', 'lazy');
    });
    
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
        let speed = Math.random() * .01 + .05; // Slower speed between 0.2 and 1.2

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

    const welcomeScreen = document.getElementById('welcome-screen');
    welcomeScreen.style.background = 'linear-gradient(to top, #48c6ef 0%, #6f86d6 100%)';
    const welcomeScreenElements = welcomeScreen.getElementsByTagName('*');
    for (let i = 0; i < welcomeScreenElements.length; i++) {
        welcomeScreenElements[i].style.color = 'white';
    }
    
    updateBackground();
    setInterval(updateBackground, 60000); // Update every minute
});

// Check if device is in dark mode, change the dark mode toggle button accordingly
setTimeout(() => {
    if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
        const darkModeButton = document.getElementById('dark-mode-toggle');
        const darkModeIcon = darkModeButton.querySelector('i');
        darkModeIcon.classList.remove('fa-moon');
        darkModeIcon.classList.add('fa-sun');
        darkModeButton.onclick = toggleLightMode;
    } else {
        const darkModeButton = document.getElementById('dark-mode-toggle');
        const darkModeIcon = darkModeButton.querySelector('i');
        darkModeIcon.classList.remove('fa-sun');
        darkModeIcon.classList.add('fa-moon');
        darkModeButton.onclick = toggleDarkMode;
    }
}, 1000);

function updateBackground() {
    const now = new Date();
    const hours = now.getHours();
    const minutes = now.getMinutes();
    const totalMinutes = hours * 60 + minutes;
    let gradient;

    if (totalMinutes >= 300 && totalMinutes <= 420) { // Dawn: 5:00 AM - 7:00 AM
        toggleLightMode('keepInterval');
        gradient = "linear-gradient(to top, #feb47b 0%, #ff7e5f 100%)"; // Warm orange
    } else if (totalMinutes > 420 && totalMinutes <= 720) { // Morning: 7:01 AM - 12:00 PM
        toggleLightMode('keepInterval');
        gradient = "linear-gradient(to top, #fbd786 0%, #6dd5ed 100%)"; // Light yellow to light blue
    } else if (totalMinutes > 720 && totalMinutes <= 1080) { // Afternoon: 12:01 PM - 6:00 PM
        toggleLightMode('keepInterval');
        gradient = "linear-gradient(to top, #48c6ef 0%, #6f86d6 100%)"; // Light blue
    } else if (totalMinutes > 1080 && totalMinutes <= 1260) { // Evening: 6:01 PM - 9:00 PM
        toggleDarkMode('keepInterval');
        gradient = "linear-gradient(to top, #4e4376 0%, #2b5876 100%)"; // Deep sunset purple
    } else { // Night: 9:01 PM - 4:59 AM
        toggleDarkMode('keepInterval');
        gradient = "linear-gradient(to top, #243B55 0%, #141E30 100%)"; // Dark blue night
    }

    document.body.style.background = gradient;
    document.getElementById('welcome-screen').style.background = gradient;
}

function toggleLightMode(x) {
    const body = document.body;
    const darkModeButton = document.getElementById('dark-mode-toggle');
    const darkModeIcon = darkModeButton.querySelector('i');
    const allElements = document.querySelectorAll('*');
    const header = document.getElementsByClassName('header')[0];
    const search = document.getElementsByClassName('search')[0];
    const new_comments = document.querySelectorAll('post_comment_new_content');
    const comments = document.querySelectorAll('post_comment_content');

    if (x !== 'keepInterval') {
        if (window.updateBackgroundInterval) {
            clearInterval(window.updateBackgroundInterval); // Stop updating the background gradient
        }
    } else {
        window.updateBackgroundInterval = setInterval(updateBackground, 60000); // Update every minute
    }

    darkModeIcon.classList.remove('fa-sun');
    darkModeIcon.classList.add('fa-moon');
    
    allElements.forEach(element => {
        element.style.color = 'black';
        const background = window.getComputedStyle(element).background;
        if (background.includes('rgba(0, 0, 0')) {
            element.style.background = background.replace('0, 0, 0', '255, 255, 255');
        }
        if (element.id === 'searchInput') {
            element.style.border = '1px solid white';
        }
    });

    new_comments.forEach(element => {
        const background = window.getComputedStyle(element).background;
        if (background.includes('rgba(0, 0, 0')) {
            element.style.background = background.replace('255, 255, 255', '0, 0, 0');
        }
    });
    comments.forEach(element => {
        const background = window.getComputedStyle(element).background;
        if (background.includes('rgba(0, 0, 0')) {
            element.style.background = background.replace('255, 255, 255', '0, 0, 0');
        }
    });

    darkModeButton.style.color = 'black';
    darkModeButton.onclick = toggleDarkMode;
    
    document.body.style.background = 'linear-gradient(to top, #48c6ef 0%, #6f86d6 100%)';

    const welcomeScreen = document.getElementById('welcome-screen');
    welcomeScreen.style.background = 'linear-gradient(to top, #48c6ef 0%, #6f86d6 100%)';
    const welcomeScreenElements = welcomeScreen.getElementsByTagName('*');
    for (let i = 0; i < welcomeScreenElements.length; i++) {
        welcomeScreenElements[i].style.color = 'white';
    }

    const headerElements = header.getElementsByTagName('*');
    for (let i = 0; i < headerElements.length; i++) {
        headerElements[i].style.color = 'white';
    }

    if (search) {
        const searchElements = search.getElementsByTagName('*');
        for (let i = 0; i < searchElements.length; i++) {
            searchElements[i].style.color = 'white';
        }
    }
}
function toggleDarkMode() {
    const body = document.body;
    const darkModeButton = document.getElementById('dark-mode-toggle');
    const darkModeIcon = darkModeButton.querySelector('i');
    const allElements = document.querySelectorAll('*');
    const header = document.getElementsByClassName('header')[0];
    const search = document.getElementsByClassName('search')[0];
    const new_comments = document.querySelectorAll('post_comment_new_content');
    const comments = document.querySelectorAll('post_comment_content');

    if (window.updateBackgroundInterval) {
        clearInterval(window.updateBackgroundInterval); // Stop updating the background gradient
    }
    
    darkModeIcon.classList.remove('fa-moon');
    darkModeIcon.classList.add('fa-sun');

    allElements.forEach(element => {
        element.style.color = 'white';
        const background = window.getComputedStyle(element).background;
        if (background.includes('rgba(255, 255, 255')) {
            element.style.background = background.replace('255, 255, 255', '0, 0, 0');
        }
        if (element.id === 'searchInput') {
            element.style.border = '1px solid black';
        }
    });

    for (let i = 0; i < new_comments.length; i++) {
        new_comments[i].background = background.replace('0, 0, 0', '255, 255, 255');
    }
    for (let i = 0; i < comments.length; i++) {
        comments[i].background = background.replace('0, 0, 0', '255, 255, 255');
    }

    darkModeButton.style.color = 'white';
    darkModeButton.onclick = toggleLightMode;
    
    document.body.style.background = 'linear-gradient(to top, #243B55 0%, #141E30 100%)';

    const welcomeScreen = document.getElementById('welcome-screen');
    welcomeScreen.style.background = 'linear-gradient(to top, #243B55 0%, #141E30 100%)';
    const welcomeScreenElements = welcomeScreen.getElementsByTagName('*');
    for (let i = 0; i < welcomeScreenElements.length; i++) {
        welcomeScreenElements[i].style.color = 'white';
    }

    const headerElements = header.getElementsByTagName('*');
    for (let i = 0; i < headerElements.length; i++) {
        headerElements[i].style.color = 'white';
    }

    if (search) {
        const searchElements = search.getElementsByTagName('*');
        for (let i = 0; i < searchElements.length; i++) {
            searchElements[i].style.color = 'white';
        }
    }
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
        type: "POST"
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



function showPassword(x) {
    const password = document.getElementById(x);
    if (password.type == "password") {
        password.type = "text";
    } else {
        password.type = "password";
    }
}
function hitEnterLogin(input) {
    const button = document.getElementById('login');

    function handleKeyPress(event) {
        if (event.keyCode === 13) {
            login();
        }
    }

    input.addEventListener('keydown', handleKeyPress, { once: true });
}

function login() {
    let normal_login = document.querySelector('.normal_login');
    let nlh = document.getElementById('normal_login_header');
    let email = document.getElementById('login-email');
    let password = document.getElementById('login-password');
    let lmsg = document.getElementById('login_msg');
    let remember = document.getElementById('login-remember');

    if (remember.checked) {
        remember = "true";
    } else {
        remember = "false";
    }

    $.ajax({
        url: './assets/login.php',
        type: "POST",
        data: {
            email: email.value,
            password: password.value,
            remember: remember
        },
        beforeSend: function() {
            nlh.innerHTML = "Logging in...";
            normal_login.style.opacity = "0.5";
            normal_login.style.pointerEvents = "none";
            normal_login.style.userSelect = "none";
            normal_login.style.cursor = "wait";
        },
        success: function(response) {
            if (response.responseCode === "ok") {
                nlh.innerHTML = response.message;
                window.location.href = "./";
            } else {
                nlh.innerHTML = response.message;
                setTimeout(() => {
                    nlh.innerHTML = "Sign in";
                }, 3000);
                normal_login.style.opacity = "1";
                normal_login.style.pointerEvents = "auto";
                normal_login.style.userSelect = "auto";
                normal_login.style.cursor = "auto";
            }
        },
        error: function() {
            nlh.innerHTML = "An error occurred. Please try again.";
            setTimeout(() => {
                nlh.innerHTML = "Sign in";
            }, 3000);
            normal_login.style.opacity = "1";
            normal_login.style.pointerEvents = "auto";
            normal_login.style.userSelect = "auto";
            normal_login.style.cursor = "auto";
        },
        complete: function() {
            normal_login.style.opacity = "1";
            normal_login.style.pointerEvents = "auto";
            normal_login.style.userSelect = "auto";
            normal_login.style.cursor = "auto";
        }
    });
}

function deleteCookie(name) {
    document.cookie = name + '=; expires=Thu, 01 Jan 1970 00:00:01 GMT;';
    console.clear();
}
function tglLogin() {
    let qr_tgl = document.getElementById('qr_tgl');
    let qr = document.querySelector('.qr_login');
    let normal = document.querySelector('.normal_login');
    if (qr.style.display === "flex") {
        qr.style.display = "none";
        normal.style.display = "block";
        if (cookieExists('qr')) {
            let code = getCookieValue('qr');
            deleteCookie('qr');
            document.getElementById('login_qr').src = "#";
            qr_tgl.innerHTML = "<i class='fa-solid fa-qrcode'></i>";
            $.ajax({
                url: './qr/api.php',
                type: "POST",
                data: {
                    delete : code
                }
            }).done(function(response) {
                console.clear();
            });
        }
    } else {
        qr.style.display = "flex";
        normal.style.display = "none";
        qr_tgl.innerHTML = "Sign in with <i class='fa-solid fa-at'></i>";
        getLoginQR();
        
        setQRSize();
        window.addEventListener('resize', setQRSize);
    }
}

function setCookie(name, value, days) {
    const date = new Date();
    date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
    const expires = `expires=${date.toUTCString()}`;
    document.cookie = `${name}=${value};${expires};path=/`;
}

function cookieExists(cookieName) {
    const pattern = new RegExp('(^|; )' + encodeURIComponent(cookieName) + '=([^;]*)');
    return pattern.test(document.cookie);
}

function getCookieValue(cookieName) {
    const pattern = new RegExp('(^|; )' + encodeURIComponent(cookieName) + '=([^;]*)');
    const match = document.cookie.match(pattern);
    return match ? decodeURIComponent(match[2]) : null;
}

function generateRandomString(length) {
    const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    let result = '';
    const charactersLength = characters.length;
    for (let i = 0; i < length; i++) {
        result += characters.charAt(Math.floor(Math.random() * charactersLength));
    }
    return result;
}

function getLoginQR() {
    let code;
    if (cookieExists('qr')) {
        code = getCookieValue('qr');
        if (code.length < 10) {
            code = generateRandomString(10);
            setCookie('qr', code, 1);
        }
    } else {
        code = generateRandomString(10);
        setCookie('qr', code, 1);
    }
    $.ajax({
        url: './qr/api.php',
        type: "POST",
        data: {
            data : code
        }
    }).done(function(response) {
        if (response === "404") {
            setTimeout(() => {
                getLoginQR();
            }, 1000);
        } else {
            document.getElementById('login_qr').src = "../qr/temp/" + response + ".png";
            checkQR(code);
        }
    });
}
function checkQR(code) {
    $.ajax({
        url: './qr/api.php',
        type: "POST",
        data: {
            check : code
        }
    }).done(function(response) {
        if (response === "pending") {
            setTimeout(() => {
                checkQR(code);
            }, 1000);
        } else
        if (response === "404") {
            return;
        } else
        if (response === "expired") {
            getLoginQR(code);
        } else
        if (response === "success"){
            delQR();
            window.location.href = "./";
        }
    });
}

function delQR(code) {
    deleteCookie('qr');
    document.getElementById('login_qr').src = "#";
    $.ajax({
        url: './qr/api.php',
        type: "POST",
        data: {
            delete : code
        }
    }).done(function(response) {
        console.clear();
    });
}

function setQRSize() {
    const qrImage = document.getElementById('qr_login_img');
    const qrWidth = qrImage.style.width;
    qrImage.style.height = qrWidth + 'px';
}