window.addEventListener('load', function() {
    const welcomeInner = document.getElementById('welcome-inner');
    const welcomeClick = document.getElementById('welcome-click');

    setTimeout(function() {
        welcomeInner.classList.add('show');
        welcomeClick.classList.add('show');
    }, 1000);
});
function hideWelcome() {
    const welcomeScreen = document.getElementById('welcome-screen');
    const welcomeClick = document.getElementById('welcome-click');
    const login_email = document.getElementById('login-email');
    welcomeScreen.style.pointerEvents = "none";
    welcomeScreen.style.opacity = "0";
    welcomeClick.style.opacity = "0";
    setTimeout(() => {
        welcomeScreen.remove();
    }, 1000);
    login_email.focus();
}