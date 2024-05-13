<?php include "../../assets/conn.php";

header('Content-Type: application/json');

if (isset($_POST['version']) && isset($_POST['platform'])) {
    $platform = $_POST['platform'];
    $installed = $_POST['version'];
    
    if ($platform == 'android') {
        $app = "apk_version";
    } else {
        $app = "ios_version";
    }

    $result = $conn->query("SELECT * FROM `app_versions`");
    $row = $result->fetch_assoc();
    $latest_version_code = $row[$app];
    if ($latest_version_code > $installed) {
        $response = [
            'status' => 'success',
            'message' => 'There is a newer version available.'
        ];
    } else {
        $response = [
            'status' => 'success',
            'message' => 'Hurray! You are up to date.'
        ];
    }

    echo json_encode($response);

    $conn->close();
} else {
    $response = [
        'status' => 'error',
        'message' => 'Please provide the installed version code and platform.'
    ];
    echo json_encode($response);
    exit();
}

?>