<?php include("assets/functions.php");
createCookie("logged",null,null,"7");
createCookie("user",null,null,"7");
session_start();
session_destroy();

header("location: ../");
?>
<meta http-equiv="Refresh" content="0; url='../'" />