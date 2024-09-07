<?php
$path = "./logs/";
$file = "clients.json";

$fileContent = file_get_contents($path.$file);
$clients = json_decode($fileContent, true);

$currentTimestamp = time();
$fiveMinutesAgo = $currentTimestamp - (5 * 60);

$cleaned = false;

foreach ($clients as &$client) {
    foreach ($client as $key => $entry) {
        $clientTimestamp = strtotime($entry['time']);
        if ($clientTimestamp >= $fiveMinutesAgo) {
            unset($client[$key]);
            $cleaned = true;
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