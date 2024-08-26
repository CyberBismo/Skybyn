<?php

session_unset();


define("dbhost","localhost");
define("dbun","elitesys_skybyna");
define("dbpw","W_ALGH90W6_9ATXz");
define("dbname","elitesys_skybyn");

$conn = new mysqli(dbhost, dbun, dbpw, dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>