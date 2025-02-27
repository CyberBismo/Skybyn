<?php
require '../functions.php';

// Disable error output in response
ini_set('display_errors', 0);
error_reporting(0);

header('Content-Type: application/json');

if (!isset($_GET['url'])) {
    echo json_encode(['error' => 'No URL provided']);
    exit;
}

$url = trim($_GET['url']);

$data = fetchLinkData($url);

// Ensure only JSON is output
if (!is_array($data)) {
    echo json_encode(['error' => 'Invalid response from fetchLinkData']);
    exit;
}

echo json_encode($data);
