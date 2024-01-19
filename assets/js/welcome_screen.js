window.addEventListener('load', function() {
    const welcomeInner = document.getElementById('welcome-inner');
    const welcomeScreen = document.getElementById('welcome-screen');

    setTimeout(function() {
        welcomeInner.classList.add('show');
    }, 1000);
});
function hideWelcome() {
    const welcomeScreen = document.getElementById('welcome-screen');
    const login_email = document.getElementById('login-email');
    welcomeScreen.remove();
    login_email.focus();
}

function toggleFullScreen() {
    if (!document.fullscreenElement) {
        document.documentElement.requestFullscreen();
    } else {
        if (document.exitFullscreen) {
        document.exitFullscreen();
        }
    }
}