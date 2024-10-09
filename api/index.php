<?php include "../assets/conn.php";

$p = $_GET['p'];
$k = $_GET['k'];

if (isset($_GET['p'])) {
    $checkProto = $conn->query("SELECT * FROM `api_keys` WHERE `protocol`='$p'");
    if ($checkProto->num_rows > 0) {
        $keyData = $checkProto->fetch_assoc();
        $key = $keyData['key'];
        if ($k == $key) {
            $json = [
                "status" => "success",
                "data" => []
            ];
            header('Content-Type: application/json');
            echo json_encode($json);
        }
    } else {
        $json = [
            "status" => "error",
            "message" => "Invalid request"
        ];
        header('Content-Type: application/json');
        echo json_encode($json);
    }
} else {
    $json = [
        "status" => "error",
        "message" => "Invalid request"
    ];
    header('Content-Type: application/json');
    echo json_encode($json);
}
?>