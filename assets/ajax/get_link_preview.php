<?php
error_log("get_link_preview.php: Starting request");

require_once "../functions.php";

if (isset($_GET['url'])) {
    $url = $_GET['url'];
    error_log("get_link_preview.php: Processing URL: " . $url);
    
    $data = getLinkData($url);
    error_log("get_link_preview.php: Fetched data: " . json_encode($data));
    
    header('Content-Type: application/json');
    echo json_encode($data);
} else {
    error_log("get_link_preview.php: No URL provided");
    header('HTTP/1.1 400 Bad Request');
    echo json_encode(['error' => 'No URL provided']);
}
?> 