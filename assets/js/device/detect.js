if (navigator.userAgent.includes("Tesla") && navigator.userAgent.includes("Linux")) {
    console.log("Tesla browser detected");
} else {
    console.log("Non-Tesla browser detected");
}

const device = document.getElementById('device');

device.innerHTML = JSON.stringify({
    type: 'device_info',
    device: navigator.userAgent.includes("Tesla") ? 'Tesla' : 'Non-Tesla',
    browser: navigator.userAgent
});