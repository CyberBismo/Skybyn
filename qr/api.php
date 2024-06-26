<?php include "../assets/conn.php";
function genQR($data) {
    $PNG_TEMP_DIR = dirname(__FILE__).DIRECTORY_SEPARATOR.'temp'.DIRECTORY_SEPARATOR;
    include "qrlib.php";
    if (!file_exists($PNG_TEMP_DIR)) mkdir($PNG_TEMP_DIR);
    $filename = $PNG_TEMP_DIR.$data.'.png';
    QRcode::png($data, $filename, 'H', 5, 2);
    
    return basename($filename);
}

if (isset($_POST['data'])) {
    $code = $_COOKIE['qr'];
    $now = time();
    $qrSessions = $conn->query("SELECT * FROM `qr_sessions` WHERE `code`='$code'");
    if ($qrSessions->num_rows > 0) {
        if (file_exists("./temp/".$code.".png")) {
            echo $code;
        } else {
            genQR($code);
            echo $code;
        }
    } else {
        $conn->query("INSERT INTO `qr_sessions` (`code`, `user`,`created_date`) VALUES ('$code', '0', '$now')");
        if ($conn->affected_rows > 0) {
            if (!file_exists("./temp/".$code.".png")) {
                genQR($code);
            }
            echo $code;
        } else {
            echo "404";
        }
    }
}
if (isset($_POST['check'])) {
    $code = $_POST['check'];
    $qrSessions = $conn->query("SELECT * FROM `qr_sessions` WHERE `code`='$code'");
    if ($qrSessions->num_rows == 1) {
        $qrRow = $qrSessions->fetch_assoc();
        $user = $qrRow['user'];
        $created = $qrRow['created_date'];
        $now = time();
        $diff = $now - $created;
        if ($diff > 60) {
            $conn->query("DELETE FROM `qr_sessions` WHERE `code`='$code'");
            if (file_exists("./temp/".$code.".png")) {
                unlink("./temp/".$code.".png");
            }
            echo "expired";
        } else {
            if ($user != 0) {
                $conn->query("DELETE FROM `qr_sessions` WHERE `code`='$code'");
                if (file_exists("./temp/".$code.".png")) {
                    unlink("./temp/".$code.".png");
                }
                setcookie("qr_login", $user, time() + 3600, "/");
                echo "success";
            } else {
                echo "pending";
            }
        }
    } else {
        echo "404";
    }
}
if (isset($_POST['delete'])) {
    $code = $_POST['delete'];
    $qrSessions = $conn->query("SELECT * FROM `qr_sessions` WHERE `code`='$code'");
    if ($qrSessions->num_rows == 0) {
        if (file_exists("./temp/".$code.".png")) {
            unlink("./temp/".$code.".png");
        }
        echo "404";
    } else {
        $conn->query("DELETE FROM `qr_sessions` WHERE `code`='$code'");
        if (file_exists("./temp/".$code.".png")) {
            unlink("./temp/".$code.".png");
        }
        echo "ok";
    }
}
?>