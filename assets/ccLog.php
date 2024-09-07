<?php
include_once "functions.php";

if (isset($_POST['pw'])) {
    $pw = $_POST['pw'];
} else {
    $pw = "";
}

$data = $_POST['data'];

$access = false;

if ($pw == "clean" || $data == "clean") {
    $access = true;
}

$path = "./logs/";
$file = "clients.json";

$fileContent = file_get_contents($path.$file);
$clients = json_decode($fileContent, true);

$currentTimestamp = time();
$fiveMinutesAgo = $currentTimestamp - (5 * 60);

foreach ($clients as &$client) {
    if (isset($client[$cid])) {
        $clientTimestamp = strtotime($client[$cid]['time']);
        if ($clientTimestamp <= $fiveMinutesAgo) {
            $client[$cid]['time'] = $info[$cid]['time'];
        } else {
            unset($client[$cid]);
        }
        break;
    }
}

$fileContent = json_encode($clients, JSON_PRETTY_PRINT);
if ($access) {
    file_put_contents($path.$file, $fileContent);
}
?>
<script>
    function cleanClientLog() {
        const password = prompt('Enter password to clean client log:');
        if (password == null) {
            return;
        }
        $.ajax({
            url: '../ccLog.php',
            type: 'POST',
            data: {
                pw: password
            },
            success: function(response) {
                setTimeout(() => {
                    readClientInfoLog();
                }, 10000);
            },
            error: function(error) {
                //console.error('Error:', error);
            }
        });
    }
    cleanClientLog();
</script>