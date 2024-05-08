<?php include "../assets/db.php";
function genQR($data) {
    $PNG_TEMP_DIR = dirname(__FILE__).DIRECTORY_SEPARATOR.'temp'.DIRECTORY_SEPARATOR;
    include "qrlib.php";
    if (!file_exists($PNG_TEMP_DIR)) mkdir($PNG_TEMP_DIR);
    $filename = $PNG_TEMP_DIR.$data.'.png';
    QRcode::png($data, $filename, 'H', 5, 2);
    
    return basename($filename);
}

if (isset($_POST['data'])) {
    $code = $_SESSION['qr_session'];
    $now = time();
    $qrSessions = $conn->query("SELECT * FROM `qr_sessions` WHERE `code`='$code'");
    if ($qrSessions->num_rows == 0) {
        $conn->query("INSERT INTO `qr_sessions` (`code`, `user`,`created_date`) VALUES ('$code', '0', '$now')");
        if (!file_exists("./qr/temp/".$code.".png")) {
            genQR($code);
        }
        echo $code;
    } else {
        $qrRow = $qrSessions->fetch_assoc();
        $created = strtotime($qrRow['created_date']);
        $diff = $now - $created;
        if ($diff > 60) {
            $conn->query("DELETE FROM `qr_sessions` WHERE `code`='$code'");
            unlink("./qr/temp/".$code.".png");
            echo "repeat";
        } else {
            $code = $qrRow['code'];
            if (file_exists("./qr/temp/".$code.".png")) {
                echo $code;
            } else {
                echo "repeat";
            }
        }
    }
}
if (isset($_POST['check'])) {
    $code = $_POST['check'];
    $qrSessions = $conn->query("SELECT * FROM `qr_sessions` WHERE `code`='$code'");
    if ($qrSessions->num_rows == 0) {
        echo "repeat";
    } else {
        $qrRow = $qrSessions->fetch_assoc();
        $user = $qrRow['user'];
        $created = strtotime($qrRow['created_date']);
        $now = time();
        $diff = $now - $created;
        if ($diff > 60) {
            $conn->query("DELETE FROM `qr_sessions` WHERE `code`='$code'");
            #unlink("./qr/temp/".$code.".png");
            echo "repeat";
        } else {
            if ($user != 0) {
                $_SESSION['loggedin'] = $user;
                echo "success";
            }
        }
    }
}
?>