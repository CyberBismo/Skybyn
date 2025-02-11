<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

define("dbhost","localhost");
define("dbun","elitesys_skybyna");
define("dbpw","W_ALGH90W6_9ATXz");
define("dbname","elitesys_skybyn");

$conn = new mysqli(dbhost, dbun, dbpw, dbname);
$conn->set_charset('utf8mb4');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

define('SECRET_KEY', '093cee001dd6a2c41d66382b849f86706aa836824e3da3e0f6feb006c1b23ad8');
?>