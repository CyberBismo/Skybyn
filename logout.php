<?php include("assets/functions.php");
createCookie("login_token","","1","7");
session_destroy();
session_unset();

header("location: ./");
?>