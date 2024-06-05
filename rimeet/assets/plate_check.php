<?php
$vegvesen_api = "d2e8dde7-2f70-4622-af60-ac31d0da54a0";
$plate = $_GET['nr'];

$url = "https://akfell-datautlevering.atlas.vegvesen.no/enkeltoppslag/kjoretoydata?kjennemerke=$plate";
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    "SVV-Authorization: $vegvesen_api",
    "Accept: application/json"
));

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

header('Content-Type: application/json');

if ($http_code == 200) {
    $data = json_decode($response, true);
    echo json_encode($data, JSON_PRETTY_PRINT);
} else {
    $error_message = array("error" => "Vehicle not found or another error occurred.", "http_code" => $http_code);
    echo json_encode($error_message, JSON_PRETTY_PRINT);
}