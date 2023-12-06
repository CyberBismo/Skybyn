<!DOCTYPE html>
<html>
	<head>
		<style>
			body {
				background: black;
			}
		</style>
	</head>
	<body>
		<?php if (isset($_GET['*'])) {
			?><meta http-equiv="refresh" content="0;url=https://wesocial.space/api/test.php?noti&profile&mail&msg&freq&firebase&push"><?php
		}?>
		<?php if (isset($_GET['noti'])) {?>
		<form method="post" action="notification/add.php">
			<input type="text" name="id" placeholder="From" autofocus><br>
			<input type="text" name="uid" placeholder="To"><br>
			<input type="text" name="title" placeholder="Title"><br>
			<input type="text" name="content" placeholder="Content"><br>
			<input type="text" name="type" placeholder="Type"><br>
			<input type="submit" value="Sent notification">
		</form><br>
		<?php }?>
		<?php if (isset($_GET['profile'])) {?>
		<form method="post" action="profile.php">
			<input type="number" name="userID" value="1" autofocus><br>
			<input type="submit" value="profile">
		</form><br>
		<?php }?>
		<?php if (isset($_GET['mail'])) {?>
		<form method="post" action="register.php">
			<input type="text" name="username" placeholder="test" autofocus><br>
			<input type="text" name="password" placeholder="test"><br>
			<input type="text" name="email" placeholder="test@test.com"><br>
			<input type="submit" value="mail">
		</form><br>
		<?php }?>
		<?php if (isset($_GET['msg'])) {?>
		<form method="post" action="message/add.php">
			<input type="text" name="userID" placeholder="From user id" autofocus><br>
			<input type="text" name="friendID" placeholder="To user id"><br>
			<input type="text" name="content" placeholder="Message"><br>
			<input type="submit" value="msg">
		</form><br>
		<?php }?>
		<?php if (isset($_GET['freq'])) {?>
		<form method="post" action="friend/add.php">
			<input type="text" name="userID" placeholder="From user id" autofocus><br>
			<input type="text" name="friendID" placeholder="To user id"><br>
			<input type="submit" value="Send friend request">
		</form><br>
		<?php }?>
		<?php if (isset($_GET['push'])) {?>
		<input id="text" type="text" placeholder="Notification text" autofocus>
		<button onclick="notifyMe()">Notify me!</button><br><br>
		<?php }?>
		<?php if (isset($_GET['firebase'])) {?>
		<form method="post" action="notification/firebase.php">
			<input type="text" name="token" placeholder="User" autofocus><br>
			<input type="text" name="title" placeholder="Title of notification"><br>
			<input type="text" name="body" placeholder="Message of notification"><br>
			<input type="text" name="type" value="chat"><br>
			<input type="submit" value="Send notification">
		</form><br>
		<?php }?>

		<script>
		function notifyMe() {
		    var options = {
		        body: document.getElementById('text').value,
		        icon: 'ws.png',
		        image: 'banner.png',
		        silent: true
		    }
		
		    if (Notification.permission !== "denied") {
		        Notification.requestPermission().then(function (permission) {
		            if (permission === "granted") {
		                var notification = new Notification('[We Social]', options);
		            } else {
						Notification.requestPermission().then(function(result) {
							console.log(result);
						});
						Notification.requestPermission();
					}
		        });
		    }
		}

		function askNotificationPermission() {
		    function handlePermission(permission) {
		        if(Notification.permission === 'denied' || Notification.permission === 'default') {
		            notificationBtn.style.display = 'block';
		        } else {
		            notificationBtn.style.display = 'none';
		        }
		    }
		
		    if (!('Notification' in window)) {
		        console.log("This browser does not support notifications.");
		    } else {
		        if(checkNotificationPromise()) {
		            Notification.requestPermission()
		            .then((permission) => {
		                handlePermission(permission);
		            })
		        } else {
		            Notification.requestPermission(function(permission) {
		                handlePermission(permission);
		            });
		        }
		    }
		}

		function checkNotificationPromise() {
		    try {
		        Notification.requestPermission().then();
		    } catch(e) {
		        return false;
		    }
		
		    return true;
		}
		</script>
	</body>
</html>