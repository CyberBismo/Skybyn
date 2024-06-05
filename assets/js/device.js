function detectDeviceInfo() {
    // Detect browser information
    const browserInfo = {
        userAgent: navigator.userAgent,
        appVersion: navigator.appVersion,
        platform: navigator.platform,
        language: navigator.language,
    };

    // Detect device information
    const deviceInfo = {
        isMobile: /Mobi|Android/i.test(navigator.userAgent),
        isTablet: /Tablet|iPad/i.test(navigator.userAgent),
        isDesktop: !(/Mobi|Android|Tablet|iPad/i.test(navigator.userAgent)),
    };

    // Detect location information
    function getLocation() {
        return new Promise((resolve, reject) => {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        const location = {
                            latitude: position.coords.latitude,
                            longitude: position.coords.longitude,
                            accuracy: position.coords.accuracy,
                        };
                        resolve(location);
                    },
                    (error) => {
                        reject(error);
                    }
                );
            } else {
                reject(new Error('Geolocation is not supported by this browser.'));
            }
        });
    }

    return {
        browserInfo,
        deviceInfo,
        getLocation,
    };
}

// Example usage
const deviceInfo = detectDeviceInfo();

console.log('Browser Info:', deviceInfo.browserInfo);
console.log('Device Info:', deviceInfo.deviceInfo);

deviceInfo.getLocation().then(location => {
    console.log('Location Info:', location);
}).catch(error => {
    console.error('Error getting location:', error);
});