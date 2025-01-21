<?php require_once "./functions.php";

$group = $_POST['group'];
$user = $_POST['user'];

$folder = "../data/groups/";
$filename = $group . ".json";
$filePath = $folder . $filename;

if (file_exists($filePath)) {
    $json = file_get_contents($filePath);
    $data = json_decode($json, true);
    
    if (isset($data["members"][$user])) {
        unset($data["members"][$user]);

        $jsonData = json_encode($data, JSON_PRETTY_PRINT);
        file_put_contents($filePath, $jsonData);
    } else {
        if (isset($data["guests"][$user])) {
            unset($data["guests"][$user]);
    
            $jsonData = json_encode($data, JSON_PRETTY_PRINT);
            file_put_contents($filePath, $jsonData);
        }
    }
}
?>
