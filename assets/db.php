<?php

define("dbhost","localhost");
define("dbun","elitesys_skybyna");
define("dbpw","W_ALGH90W6_9ATXz");
define("dbname","elitesys_skybyn");

$conn = mysqli_connect(dbhost,dbun,dbpw,dbname);
$conn -> set_charset("utf8mb4");
global $conn;

session_start();

?>