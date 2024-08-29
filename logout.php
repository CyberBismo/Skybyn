<?php include("assets/functions.php");
createCookie("logged","","1","7");
createCookie("user","","1","7");
session_destroy();
?>
<meta http-equiv="Refresh" content="0; url='../'" />