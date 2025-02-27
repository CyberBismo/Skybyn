<?php
require_once "../functions.php";

if (isset($_GET['url'])) {
    $url = $_GET['url'];
    $data = fetchLinkData($url);
    header('Content-Type: application/json');
    echo json_encode($data);
}
?> 