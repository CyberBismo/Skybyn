<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Facebook Login</title>
</head>
<body>
  <script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js"></script>
  <script>
    window.fbAsyncInit = function() {
      FB.init({
        appId      : '434789206198164', // Replace with your app ID
        cookie     : true,
        xfbml      : true,
        version    : 'v12.0' // Use the latest version available
      });

      FB.AppEvents.logPageView();   
    };
  </script>
  <div class="fb-login-button" 
    data-width="" 
    data-size="large" 
    data-button-type="login_with" 
    data-layout="default" 
    data-auto-logout-link="false" 
    data-use-continue-as="false" 
    onlogin="checkLoginState();">
  </div>
  <script>
    function checkLoginState() {
      FB.getLoginStatus(function(response) {
        statusChangeCallback(response);
      });
    }

    function statusChangeCallback(response) {
      if (response.status === 'connected') {
        FB.api('/me', {fields: 'id,name,email'}, function(response) {
          console.log('Successful login for: ' + response.name);
          // Send the response to your server
          fetch('/auth/facebook/callback', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json'
            },
            body: JSON.stringify(response)
          }).then(res => res.json())
            .then(data => {
              // Handle server response
              console.log(data);
            });
        });
      } else {
        console.log('User cancelled login or did not fully authorize.');
      }
    }
  </script>
</body>
</html>
