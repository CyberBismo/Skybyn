<?php
$path = "../logs/";
$file = "clients.json";

$fileContent = file_get_contents($path.$file);
$clients = json_decode($fileContent, true);

$currentTimestamp = time();
$fiveMinutesAgo = $currentTimestamp - (5 * 60);

foreach ($clients as &$client) {
    for ($i = 0; $i < count($client); $i++) {
        $clientTimestamp = strtotime($client[$i]['time']);
        if ($clientTimestamp <= $fiveMinutesAgo) {
            $client[$i]['time'] = $client[$i]['time'];
        } else {
            unset($client[$i]);
        }
    }
    break;
}

$fileContent = json_encode($clients, JSON_PRETTY_PRINT);
file_put_contents($path.$file, $fileContent);
?>