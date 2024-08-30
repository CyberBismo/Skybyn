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
    welcomeScreen.style.pointerEvents = "none";
    welcomeScreen.style.opacity = "0";
    setTimeout(() => {
        welcomeScreen.remove();
    }, 1000);
    login_email.focus();
}