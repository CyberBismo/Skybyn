<script>
    async function fetchDeviceInfo() {
        const response = await fetch('http://localhost:3000/api/detect');
        const data = await response.json();
        console.log('Detected Info:', data);

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                (position) => {
                    data.location = {
                        latitude: position.coords.latitude,
                        longitude: position.coords.longitude,
                        accuracy: position.coords.accuracy,
                    };
                    console.log('Final Data with Location:', data);
                },
                (error) => {
                    console.error('Error getting location:', error);
                }
            );
        } else {
            console.error('Geolocation is not supported by this browser.');
        }
    }

    fetchDeviceInfo();
</script>