<?php include_once "functions.php";

$file_path = "../data/new_users.json";

$directory = dirname($file_path);
if (!is_dir($directory)) {
    mkdir($directory, 0755, true);
}

function updateFile($file_path,$conn) {
    $getNewUsers = $conn->query("SELECT * FROM `users` ORDER BY `registration_date` DESC LIMIT 3");
    $userDataArray = [];

    while ($userData = $getNewUsers->fetch_assoc()) {
        $username = $userData['username'];
        $reg_date = $userData['registration_date'];
        
        $userDataArray[$username] = ['reg_date' => $reg_date];
    }

    file_put_contents($file_path, json_encode($userDataArray, JSON_PRETTY_PRINT));
}

updateFile($file_path,$conn);
$file_content = file_get_contents($file_path);
echo $file_content;
?>
