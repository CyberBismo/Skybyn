<?php require_once "assets/conn.php";

$today = strtotime(date("Y-m-d"));

function isLocalhost() {
    $url = $_SERVER['HTTP_HOST'];
    return preg_match('/localhost/', $url);
}

# Get driver information
function driver($x,$y,$z) {
    global $conn;
    if ($x == "id") {
        $stmt = $conn->prepare("SELECT * FROM `drivers` WHERE `id` = ?");
        $stmt->bind_param("i", $y);
    } else {
        $stmt = $conn->prepare("SELECT * FROM `drivers` WHERE `username` = ?");
        $stmt->bind_param("s", $y);
    }
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $driver = $result->fetch_assoc();
        return $driver[$z];
    }
    
    $stmt->close();
}

# Check if driver is in a meet
function checkMeet($driver) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM `meets` WHERE `driver` = ?");
    $stmt->bind_param("s", $driver);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();

    if ($result->num_rows > 0) {
        $meetInfo = $result->fetch_assoc();
        $meet_id = $meetInfo['id'];
        $meet_cancelled = $meetInfo['cancelled'];
        if ($meet_cancelled == 0) {
            return false;
        } else {
            return true;
        }
    } else {
        return false;
    }
}

# Validate car plate with Vegvesen API
function verifyPlate($plate) {
    $vegvesen_api = "d2e8dde7-2f70-4622-af60-ac31d0da54a0";
    
    $url = "https://akfell-datautlevering.atlas.vegvesen.no/enkeltoppslag/kjoretoydata?kjennemerke=$plate";
    $ch = curl_init();
    
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        "SVV-Authorization: $vegvesen_api",
        "Accept: application/json"
    ));
    
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($http_code == 200) {
        return true;
    } else {
        return false;
    }
}

# Check if car plate is valid
function checkCarPlate($plate) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM `cars` WHERE `license_plate` = ?");
    $stmt->bind_param("s", $plate);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $error = "Skiltnummeret er allerede registrert";
        setcookie("error", $error, time() + (10), "/"); # 1 minute
    } else
    if (verifyPlate($plate) == false) {
        $error = "Skiltnummeret er ikke gyldig";
        setcookie("error", $error, time() + (10), "/"); # 1 minute
    } else {
        return true;
    }

    $stmt->close();
}

# Driver functions
if (isset($_POST['register'])) {
    $username = htmlspecialchars($_POST['username'], ENT_QUOTES, "UTF-8");
    $password = hash("sha512", $_POST['password']);
    $salt = hash("sha512", rand());
    $password = hash("sha512", $password.$salt);
    $error = false;

    $stmt = $conn->prepare("SELECT * FROM `drivers` WHERE `username` = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $error = "Brukernavnet er allerede i bruk";
    }

    if ($error == false) {
        $stmt = $conn->prepare("INSERT INTO `drivers` (`username`, `password`, `salt`) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $password, $salt);
        $stmt->execute();

        $id = $conn->insert_id;
        $_SESSION['driver'] = $id;
        setcookie("driver", $id, time() + (7 * 24 * 60 * 60), "/"); # 7 days
        header("Location: ./");
    } else {
        setcookie("error", $error, time() + (10), "/"); # 1 minute
        header("Location: ./car?signup");
    }

    $stmt->close();
}

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = hash("sha512", $_POST['password']);
    $stmt = $conn->prepare("SELECT * FROM `drivers` WHERE LOWER(`username`) = LOWER(?)");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $driver = $result->fetch_assoc();
        $id = $driver['id'];
        $salt = $driver['salt'];
        $password = hash("sha512", $password.$salt);

        if ($password == $driver['password']) {
            setcookie("driver", $id, time() + (7 * 24 * 60 * 60), "/"); # 7 days
            $_SESSION['driver'] = $id;
            header("Location: ./");
        } else {
            $error = "Feil passord";
            setcookie("error", $error, time() + (60), "/"); # 1 minute
            header("Location: ./car?signin");
        }
    } else {
        $error = "Brukeren eksisterer ikke";
        setcookie("error", $error, time() + (60), "/"); # 1 minute
        header("Location: ./car?signin");
    }

    $stmt->close();
}

if (isset($_GET['logout'])) {
    session_destroy();
    session_unset();
    setcookie("driver", null, time() - 3600, "/");
    header("Location: ./car?signin");
}

if (isset($_SESSION['driver'])) {
    $id = $_SESSION['driver'];
    $drivers = $conn->query("SELECT * FROM `drivers` WHERE `id` = '$id'");
    if ($drivers->num_rows == 0) {
        session_destroy();
        setcookie("driver", "", time() - 3600, "/");
        header("Location: ./car?signin");
    } else {
        $driver = $drivers->fetch_assoc();
        $username = $driver['username'];
        $default_car = $driver['default_car'];
        $phone = $driver['phone'];
        $fullname = $driver['full_name'];
    }
}

if (isset($_POST['add_car'])) {
    $plate = $_POST['license_plate'];
    if (checkCarPlate($plate) == true) {
        $stmt = $conn->prepare("INSERT INTO `cars` (`license_plate`, `driver`) VALUES (?, ?)");
        $stmt->bind_param("si", $plate, $id);
        $stmt->execute();
        $stmt->close();
        header("Location: ./");
    } else {
        setcookie("error", "Ugyldig skiltnummer", time() + (10), "/"); # 1 minute
        header("Location: ./");
    }
}

if (isset($_GET['setDefault'])) {
    $plate = $_GET['setDefault'];
    $stmt = $conn->prepare("UPDATE `drivers` SET `default_car` = ? WHERE `id` = ?");
    $stmt->bind_param("si", $plate, $id);
    $stmt->execute();
    $stmt->close();
    header("Location: ./");
}

if (isset($_GET['removeCar'])) {
    $plate = $_GET['removeCar'];
    $stmt = $conn->prepare("DELETE FROM `cars` WHERE `license_plate` = ? AND `driver` = ?");
    $stmt->bind_param("si", $plate, $id);
    $stmt->execute();
    $stmt->close();
    header("Location: ./");
}

if (isset($_GET['stolen'])) {
    $plate = $_GET['stolen'];
    $checkPlate = $conn->query("SELECT * FROM `cars` WHERE `license_plate` = '$plate' AND `driver` = '$id'");
    if ($checkPlate->num_rows == 1) {
        $stmt = $conn->prepare("UPDATE `cars` SET `stolen` = 1 WHERE `license_plate` = ? AND `driver` = ?");
        $stmt->bind_param("si", $plate, $id);
        $stmt->execute();
        $stmt->close();
        header("Location: ./car?s=$plate");
    }
}
if (isset($_GET['found'])) {
    $plate = $_GET['found'];
    $checkPlate = $conn->query("SELECT * FROM `cars` WHERE `license_plate` = '$plate' AND `driver` = '$id'");
    if ($checkPlate->num_rows == 1) {
        $stmt = $conn->prepare("UPDATE `cars` SET `stolen` = 0 WHERE `license_plate` = ? AND `driver` = ?");
        $stmt->bind_param("si", $plate, $id);
        $stmt->execute();
        $stmt->close();
        header("Location: ./car?s=$plate");
    }
}

# Meet functions
if (isset($_POST['newMeet'])) {
    $name = htmlspecialchars($_POST['name'], ENT_QUOTES, "UTF-8");
    $date = $_POST['date'];
    $time = $_POST['time'];
    $location = htmlspecialchars($_POST['location'], ENT_QUOTES, "UTF-8");

    $datetime = date("Y-m-d H:i:s", strtotime($_POST['date'] . ' ' . $_POST['time']));

    if (empty($name)) {
        $name = $username."'s treff";
    }

    $stmt = $conn->prepare("INSERT INTO `meets` (`name`, `time`, `location`, `driver`,`created`) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sissi", $name, $datetime, $location, $id, $today);
    $stmt->execute();
    $stmt->close();
    $meet_id = $conn->insert_id;
    header("Location: ./meet?id=$meet_id");
}

if (isset($_POST['meet_visibility'])) {
    $meet_id = $_POST['id'];
    if (isset($_POST['address_visible'])) {
        $address_visible = 1;
    } else {
        $address_visible = 0;
    }
    $stmt = $conn->query("UPDATE `meets` SET `address_visible` = '$address_visible' WHERE `id` = '$meet_id'");
    $stmt->close();
    header("Location: ./meet?id=$meet_id");
}

if (isset($_POST['updateMeet'])) {
    $meet_id = $_POST['id'];
    $name = htmlspecialchars($_POST['name'], ENT_QUOTES, "UTF-8");
    $date = $_POST['date'];
    $time = $_POST['time'];
    $location = htmlspecialchars($_POST['location'], ENT_QUOTES, "UTF-8");

    $datetime = date("Y-m-d H:i:s", strtotime($_POST['date'] . ' ' . $_POST['time']));
    
    $stmt = $conn->prepare("UPDATE `meets` SET `name` = ?, `time` = ?, `location` = ? WHERE `id` = ?");
    $stmt->bind_param("sisi", $name, $datetime, $location, $meet_id);
    $stmt->execute();
    $stmt->close();

    header("Location: ./meet?id=$meet_id");
}

if (isset($_POST['cancelMeet'])) {
    $meet_id = $_POST['id'];
    $stmt = $conn->prepare("UPDATE `meets` SET `expired` = 1 WHERE `id` = ?");
    $stmt->bind_param("i", $meet_id);
    $stmt->execute();
    $stmt->close();
    header("Location: ./");
}

if (isset($_POST['returnMeet'])) {
    $meet_id = $_POST['id'];
    header("Location: ./meet?id=$meet_id");
}

?>