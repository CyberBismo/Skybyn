<?php
include_once "functions.php";
$data = json_decode($_POST['clientInfo'], true);

$path = "./logs/";
$file = "clients.json";

if (!file_exists($path)) {
    mkdir($path, 0777, true);
}

$fileContent = file_get_contents($path.$file);
$clients = json_decode($fileContent, true) ?? [];

if (isset($_SESSION['client'])) {
    $cid = $_SESSION['client'];
} else {
    $cid = rand(100000, 999999);
    $_SESSION['client'] = $cid;
}

$info = [
    $cid => [
        'ip' => $_SERVER['REMOTE_ADDR'],
        'time' => $data['time'],
        'platform' => $data['platform'],
        'screenWidth' => $data['screenWidth'],
        'screenHeight' => $data['screenHeight'],
        'screenResolution' => $data['screenResolution'],
        'windowDevicePixelRatio' => $data['windowDevicePixelRatio'],
        'windowLocation' => [
            'href' => $data['windowLocation']['href'],
            'origin' => $data['windowLocation']['origin'],
            'protocol' => $data['windowLocation']['protocol'],
            'host' => $data['windowLocation']['host'],
            'hostname' => $data['windowLocation']['hostname'],
            'port' => $data['windowLocation']['port'],
            'pathname' => $data['windowLocation']['pathname'],
            'search' => $data['windowLocation']['search'],
            'hash' => $data['windowLocation']['hash']
        ],
        'browser' => [
            'appName' => $data['browser']['appName'],
            'appVersion' => $data['browser']['appVersion'],
            'product' => $data['browser']['product'],
            'userAgent' => $data['browser']['userAgent'],
            'vendor' => $data['browser']['vendor'],
            'cookieEnabled' => $data['browser']['cookieEnabled'],
            'doNotTrack' => $data['browser']['doNotTrack'],
            'hardwareConcurrency' => $data['browser']['hardwareConcurrency']
        ],
        'languages' => $data['languages'],
        'connection' => [
            'effectiveType' => $data['connection']['effectiveType'],
            'downlink' => $data['connection']['downlink'],
            'rtt' => $data['connection']['rtt'],
            'saveData' => $data['connection']['saveData']
        ],
        'deviceMemory' => $data['deviceMemory']
    ]
];

$exists = false;
foreach ($clients as &$client) {
    if (isset($client[$cid])) {
        $client[$cid]['ip'] = $info[$cid]['ip'];
        $client[$cid]['time'] = $info[$cid]['time'];
        $client[$cid]['platform'] = $info[$cid]['platform'];
        $client[$cid]['screenWidth'] = $info[$cid]['screenWidth'];
        $client[$cid]['screenHeight'] = $info[$cid]['screenHeight'];
        $client[$cid]['screenResolution'] = $info[$cid]['screenResolution'];
        $client[$cid]['windowDevicePixelRatio'] = $info[$cid]['windowDevicePixelRatio'];
        $client[$cid]['windowLocation'] = $info[$cid]['windowLocation'];
        $client[$cid]['browser'] = $info[$cid]['browser'];
        $client[$cid]['languages'] = $info[$cid]['languages'];
        $client[$cid]['connection'] = $info[$cid]['connection'];
        $client[$cid]['deviceMemory'] = $info[$cid]['deviceMemory'];
        $exists = true;
        break;
    }
}


if (!$exists) {
    $clients[] = $info;
}

$fileContent = json_encode($clients, JSON_PRETTY_PRINT);
file_put_contents($path.$file, $fileContent);

$data = [
    'responseCode' => 1,
    'message' => "Client info saved/updated.",
    'client' => $cid
];
header('Content-Type: application/json; charset=utf-8');
echo json_encode($data);
?>