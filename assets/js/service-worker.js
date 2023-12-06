if ('serviceWorker' in navigator) {
    navigator.serviceWorker.register('/sw.js')
    .then(function(registration) {
        console.log('Service Worker registered with scope:', registration.scope);
    })
    .catch(function(error) {
        console.error('Service Worker registration failed:', error);
    });
}

let deferredPrompt;

window.addEventListener('beforeinstallprompt', (event) => {
    // Prevent the browser's default install prompt
    event.preventDefault();
    // Store the event for later use
    deferredPrompt = event;
    // Show the install button
    document.getElementById('install-button').style.display = 'block';
});

const installButton = document.getElementById('install-button');

installButton.addEventListener('click', () => {
    // Show the browser's install prompt when the button is clicked
    if (deferredPrompt) {
        deferredPrompt.prompt();
        // Wait for the user to respond to the prompt
        deferredPrompt.userChoice.then((choiceResult) => {
        if (choiceResult.outcome === 'accepted') {
            console.log('User accepted the install prompt');
        } else {
            console.log('User dismissed the install prompt');
        }
        // Reset the deferredPrompt variable
        deferredPrompt = null;
        });
    }
});

// Hide the button if the PWA is already installed
window.addEventListener('appinstalled', (event) => {
    console.log('App installed');
    installButton.style.display = 'none';
});