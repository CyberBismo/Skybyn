//// Register Service worker for push notifications
//if ('serviceWorker' in navigator && 'PushManager' in window) {
//    navigator.serviceWorker.register('/assets/js/service-worker.js')
//    .then(swReg => {
//        //console.log('Service Worker registered:', swReg);
//
//        return Notification.requestPermission().then(permission => {
//            if (permission === 'granted') {
//                subscribeUser(swReg);
//            }
//        });
//    });
//}
//
//function subscribeUser(swReg) {
//    const applicationServerKey = urlBase64ToUint8Array('BNmqMQ9fopNj8r1bsuTLuXSXXeVchRCzOrAF04xHQNNvZzIAsARBBAvuFCrSg8J6FCOktIR4NyN-wVa-40llJks');
//    swReg.pushManager.subscribe({
//        userVisibleOnly: true,
//        applicationServerKey
//    }).then(subscription => {
//        // Send subscription to your server
//        fetch('../assets/save-subscription.php', {
//            method: 'POST',
//            body: JSON.stringify(subscription),
//            headers: { 'Content-Type': 'application/json' }
//        });
//    });
//}

function urlBase64ToUint8Array(base64String) {
    const padding = '='.repeat((4 - base64String.length % 4) % 4);
    const base64 = (base64String + padding).replace(/\-/g, '+').replace(/_/g, '/');
    const rawData = atob(base64);
    return new Uint8Array([...rawData].map(char => char.charCodeAt(0)));
}

document.addEventListener('click', function(event) {
});

// Prioritixe loading speed by loading images "lazy"
document.addEventListener("DOMContentLoaded", function() {
    const images = document.querySelectorAll('img');
    images.forEach(img => {
        img.setAttribute('loading', 'lazy');
    });
    
    const cloudsContainer = document.getElementsByClassName('clouds')[0];

    for (let i = 0; i < 10; i++) {
        let cloud = document.createElement('div');
        cloud.className = 'cloud';
        cloudsContainer.appendChild(cloud);

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
    
    let dayTop = '48c6ef';
    let dayBottom = '6f86d6';

    const welcomeScreen = document.getElementById('welcome-screen');
    welcomeScreen.style.background = "linear-gradient(to top, #" + dayTop + " 0%, #" + dayBottom + " 100%)";
    const welcomeScreenElements = welcomeScreen.getElementsByTagName('*');
    for (let i = 0; i < welcomeScreenElements.length; i++) {
        welcomeScreenElements[i].style.color = 'white';
    }
});

const detectDarkModeToggle = () => {
    const handleDarkModeChange = (event) => {
        if (event.matches) {
            document.getElementsByTagName('body').classList.add('darkmode');
        } else {
            document.getElementsByTagName('body').classList.remove('darkmode');
        }
    };
  
    // Create a MediaQueryList object
    const darkModeMediaQuery = window.matchMedia("(prefers-color-scheme: dark)");
  
    // Listen for changes to the media query
    darkModeMediaQuery.addEventListener("change", handleDarkModeChange);
  
    // Check the initial state
    if (darkModeMediaQuery.matches) {
        console.log("Dark mode is currently enabled");
    } else {
        console.log("Dark mode is currently disabled");
    }
};

async function getTimeZoneByIP() {
    try {
        const response = await fetch('https://ipwhois.app/json/');
        const data = await response.json();
        return data.timezone; // Returns the timezone as a string, e.g., "America/New_York"
    } catch (error) {
        console.error('Error fetching timezone:', error);
        return null;
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

function skybynAlert(type,text) {
    if (type == "ok") color = "green";
    if (type == "err") color = "red";

    // Create the main notification alert div
    const alertDiv = document.createElement('div');
    alertDiv.className = 'notification_alert';
    alertDiv.style.background = color;
    alertDiv.innerHTML = text;

    // Add the element to the body (or another container)
    document.body.appendChild(alertDiv);

    setTimeout(() => {
        alertDiv.id = 'noti_alert';
        setTimeout(() => {
            alertDiv.remove();
        }, 2000);
    }, 5000);
}

function showPassword(x) {
    const password = document.getElementById(x);
    if (password.type == "password") {
        password.type = "text";
    } else {
        password.type = "password";
    }
}
function hitEnterLogin(input) {
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
    let username = document.getElementById('login-username');
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
            username: username.value,
            password: password.value,
            remember: remember
        },
        beforeSend: function(response) {
            console.log(response);
            nlh.innerHTML = "Logging in...";
            normal_login.style.opacity = "0.5";
            normal_login.style.pointerEvents = "none";
            normal_login.style.userSelect = "none";
            normal_login.style.cursor = "wait";
        },
        success: function(response) {
            console.log(response);
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
        error: function(response) {
            console.error(response);
            nlh.innerHTML = "Error logging in";
            setTimeout(() => {
                nlh.innerHTML = "Sign in";
            }, 3000);
        },
        complete: function(response) {
            console.log(response);
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

function checkBetaCode(code) {
    const value = code.value;
    if (typeof value === 'string' && value.length > 9) {
        const date = new Date();
        date.setTime(date.getTime() + (30 * 24 * 60 * 60 * 1000)); // 30 days
        
        $.ajax({
            url: './assets/checkBetaCode.php',
            type: "POST",
            data: {
                code : value
            }
        }).done(function(response) {
            if (response === "ok") {
                document.cookie = "beta=" + value + "; expires=" + date.toUTCString() + "; path=/";
                window.location.reload();
            } else {
                code.value = "Invalid code";
                code.style.color = "red";
                setTimeout(() => {
                    code.value = "";
                    code.style.color = "white";
                }, 3000);
            }
        });
    }
}