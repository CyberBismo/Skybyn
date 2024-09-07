<?php
$path = "./logs/";
$file = "clients.json";

$fileContent = file_get_contents($path.$file);
$clients = json_decode($fileContent, true);

$currentTimestamp = time();
$fiveMinutesAgo = $currentTimestamp - (5 * 60);

$cleaned = false; // Variable to track if any entries were cleaned

foreach ($clients as &$client) {
    for ($i = 0; $i < count($client); $i++) {
        $clientTimestamp = strtotime($client[$i]['time']);
        if ($clientTimestamp >= $fiveMinutesAgo) {
            unset($client[$i]);
            $cleaned = true; // Set cleaned to true if any entry is removed
        }
    }
}

$fileContent = json_encode($clients, JSON_PRETTY_PRINT);
file_put_contents($path.$file, $fileContent);

if ($cleaned) {
    $data = [
        'status' => 'success',
        'message' => 'Client log cleaned successfully'
    ];
} else {
    $data = [
        'status' => 'no_changes',
        'message' => 'No entries were cleaned'
    ];
}

header('Content-Type: application/json');
echo json_encode($data);
?>