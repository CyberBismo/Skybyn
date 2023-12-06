<!DOCTYPE html>
<html>
<head>
    <title>Your Website</title>
</head>
<body>
    <div>
        <h1>Welcome to Your Website</h1>
        <p>Login using your Facebook account:</p>

        <!-- Add Facebook login button -->
        <fb:login-button
            scope="public_profile,email"
            onlogin="checkLoginState();">
        </fb:login-button>
    </div>

    <!-- Include Facebook JavaScript SDK -->
    <script src="https://connect.facebook.net/en_US/sdk.js"></script>
    <script>
        // Initialize the SDK
        FB.init({
            appId: '1022467615422681',
            cookie: true,
            xfbml: true,
            version: 'v12.0'
        });

        // Define the checkLoginState and getUserInfo functions here
        function checkLoginState() {
            FB.getLoginStatus(function(response) {
                if (response.status === 'connected') {
                    getUserInfo();
                } else {
                    console.log('User is not logged in.');
                }
            });
        }

        function getUserInfo() {
            FB.api('/me', { fields: 'id,name,email' }, function(response) {
                var userId = response.id;
                var userName = response.name;
                var userEmail = response.email;

                // You can send this information to your server for authentication and user registration
                // For now, let's just log the information
                console.log('User ID: ' + userId);
                console.log('User Name: ' + userName);
                console.log('User Email: ' + userEmail);
            });
        }
    </script>
</body>
</html>
