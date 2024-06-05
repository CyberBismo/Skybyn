<?php session_start();
include_once "assets/conn.php";

$today = strtotime(date("Y-m-d"));
setlocale(LC_TIME, 'no_NO');

function isLocalhost() {
    $url = $_SERVER['HTTP_HOST'];
    return preg_match('/localhost/', $url);
}

function checkUrl() {
    $url = $_SERVER['REQUEST_URI'];
    if (strpos($url, "car") !== false) {
        return "car";
    } else
    if (strpos($url, "video") !== false) {
        return "video";
    } else
    if (strpos($url, "info") !== false) {
        return "info";
    } else
    if (strpos($url, "meet") !== false) {
        return "meet";
    } else
    if (strpos($url, "profile") !== false) {
        return "profile";
    } else {
        return "home";
    }
}

function navItem($x) {
    global $nav_item_left, $nav_item_center, $nav_item_right;
    $url = checkUrl();
    if ($url == "car") {
        $nav_item_left = '<a href="meet" class="nav-link"><i class="fa-solid fa-car"></i></a>'; # Meet
        $nav_item_center = '<a href="./" class="nav-link"><i class="fa-solid fa-house"></i></a>'; # Home
        $nav_item_right = '<a href="info" class="nav-link"><i class="fa-solid fa-circle-info"></i></a>'; # Info
    } else
    if ($url == "video") {
        $nav_item_left = '<a href="car" class="nav-link"><i class="fa-solid fa-car"></i></a>'; # Search
        $nav_item_center = '<a id="uploadVideo" class="nav-link"><i class="fa-solid fa-upload"></i></a>'; # Upload video
        $nav_item_right = '<a href="./" class="nav-link"><i class="fa-solid fa-home"></i></a>'; # Home
    } else
    if ($url == "meet") {
        $nav_item_left = '<a href="car" class="nav-link"><i class="fa-solid fa-car"></i></a>'; # Search
        $nav_item_center = '<a href="./" class="nav-link"><i class="fa-solid fa-house"></i></a>'; # Home
        $nav_item_right = '<a href="video" class="nav-link"><i class="fa-solid fa-video"></i></a>'; # Video
    } else
    if ($url == "info") {
        $nav_item_left = '<a href="./" class="nav-link"><i class="fa-solid fa-arrow-left"></i></a>'; # Search
        $nav_item_center = '<a href="info" class="nav-link"><i class="fa-solid fa-info"></i></a>'; # Home
        $nav_item_right = ''; # Video
    } else
    if ($url == "profile") {
        $nav_item_left = '<a href="./" class="nav-link"><i class="fa-solid fa-arrow-left"></i></a>'; # Search
        $nav_item_center = '<a href="info" class="nav-link"><i class="fa-solid fa-info"></i></a>'; # Home
        $nav_item_right = ''; # Video
    } else {
        $nav_item_left = '<a href="car" class="nav-link"><i class="fa-solid fa-car"></i></a>'; # Search
        $nav_item_center = '<a href="meet" class="nav-link"><i class="fa-solid fa-bullhorn"></i></a>'; # Meet
        $nav_item_right = '<a href="video" class="nav-link"><i class="fa-solid fa-video"></i></a>'; # Video
    }

    if ($x == "left") {
        return $nav_item_left;
    } else
    if ($x == "center") {
        return $nav_item_center;
    } else {
        return $nav_item_right;
    }
}

# Get driver/joiner information
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

function joiner($y,$z) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM `joiners` WHERE `joiner` = ?");
    $stmt->bind_param("i", $y);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $joiner = $result->fetch_assoc();
        return $joiner[$z];
    }
    
    $stmt->close();
}

# Check if driver is in a meet
function checkMeet($driver) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM `meets` WHERE `driver` = ?");
    $stmt->bind_param("i", $driver);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();

    if ($result->num_rows > 0) {
        $meetInfo = $result->fetch_assoc();
        $meet_id = $meetInfo['id'];
        $meet_cancelled = $meetInfo['cancelled'];
        if ($meet_cancelled == 0) {
            return $meet_id;
        } else {
            return $meet_id;
        }
    } else {
        return null;
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

# Detect device
function detectDevice() {
    $userAgent = $_SERVER['HTTP_USER_AGENT'];
    if (strpos($userAgent, 'iPhone') !== false || strpos($userAgent, 'iPad') !== false || strpos($userAgent, 'iPod') !== false) {
        return 'iOS';
    } elseif (strpos($userAgent, 'Android') !== false) {
        return 'Android';
    } else {
        return 'Desktop';
    }
}

# Create a clickabla address
function openMaps($address,$x) {
    $address = htmlspecialchars($address);
    $encoded_address = urlencode($address);

    $google_maps_link = "https://www.google.com/maps/search/?api=1&query={$encoded_address}";
    $ios_maps_link = "maps:{$encoded_address}";
    $android_maps_link = "geo:0,0?q={$encoded_address}";

    if ($x == "iOS") {
        return $ios_maps_link;
    } else
    if ($x == "Android") {
        return $android_maps_link;
    } else {
        return $google_maps_link;
    }
}

if (!isset($_SESSION['driver'])) {
    if (isset($_COOKIE['driver'])) {
        $_SESSION['driver'] = $_COOKIE['driver'];
    }
}

# Passenger functions
if (isset($_POST['join_driver'])) {
    $passenger = rand();
    $_SESSION['passenger'] = $passenger;
    $plate = $_POST['plate'];
    $stmt = $conn->prepare("INSERT INTO `passengers` (`id`, `ip`, `license_plate`) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $passenger, $_SERVER['REMOTE_ADDR'], $plate);
    $stmt->execute();
    $stmt->close();
    header("Location: ./car?s=$plate");
}

# Driver functions
if (isset($_POST['register'])) {
    $fullname = htmlspecialchars($_POST['fullname'], ENT_QUOTES, "UTF-8");
    $email = htmlspecialchars($_POST['email'], ENT_QUOTES, "UTF-8");
    $phone = htmlspecialchars($_POST['phone'], ENT_QUOTES, "UTF-8");
    $username = htmlspecialchars($_POST['username'], ENT_QUOTES, "UTF-8");
    $password = hash("sha512", $_POST['password']);
    $salt = hash("sha512", rand());
    $password = hash("sha512", $password.$salt);
    $error = false;

    $unc = $conn->prepare("SELECT * FROM `drivers` WHERE `username` = ?");
    $unc->bind_param("s", $username);
    $unc->execute();
    $unc = $unc->get_result();

    if ($unc->num_rows > 0) {
        $error = "Brukernavnet er allerede i bruk";
    }

    $ec = $conn->prepare("SELECT * FROM `drivers` WHERE `email` = ?");
    $ec->bind_param("s", $email);
    $ec->execute();
    $ec = $ec->get_result();

    if ($ec->num_rows > 0) {
        $error = "E-post adressen er allerede i bruk";
    }

    if ($error == false) {
        $stmt = $conn->prepare("INSERT INTO `drivers` (`full_name`, `email`, `phone`, `username`, `password`, `salt`) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $fullname, $email, $phone, $username, $password, $salt);
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
            setcookie("error", "", time() - 3600, "/");
            setcookie("driver", $id, time() + (7 * 24 * 60 * 60), "/"); # 7 days
            $_SESSION['driver'] = $id;
            echo "<script>window.location.href = './';</script>";
        } else {
            $error = "Feil passord";
            setcookie("error", $error, time() + (60), "/"); # 1 minute
            echo "<script>window.location.href = 'car?signin';</script>";
        }
    } else {
        $error = "Brukeren eksisterer ikke";
        setcookie("error", $error, time() + (60), "/"); # 1 minute
        echo "<script>window.location.href = 'car?signin';</script>";
    }

    $stmt->close();
}

if (isset($_GET['logout'])) {
    session_destroy();
    setcookie("driver", "", time() - 3600, "/");
    echo "<script>window.location.href = './';</script>";
}

if (isset($_SESSION['driver'])) {
    $id = $_SESSION['driver'];
    $drivers = $conn->query("SELECT * FROM `drivers` WHERE `id` = '$id'");
    if ($drivers->num_rows == 0) {
        session_destroy();
        setcookie("driver", "", time() - 3600, "/");
        echo "<script>window.location.href = './';</script>";
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
        setcookie("error", "", time() - 3600, "/");
        echo "<script>window.location.href = './';</script>";
    } else {
        setcookie("error", "Ugyldig skiltnummer", time() + (10), "/"); # 1 minute
        echo "<script>window.location.href = './';</script>";
    }
}

if (isset($_GET['setDefault'])) {
    $plate = $_GET['setDefault'];
    $stmt = $conn->prepare("UPDATE `drivers` SET `default_car` = ? WHERE `id` = ?");
    $stmt->bind_param("si", $plate, $id);
    $stmt->execute();
    $stmt->close();
    echo "<script>window.location.href = './';</script>";
}

if (isset($_GET['removeCar'])) {
    $plate = $_GET['removeCar'];
    $stmt = $conn->prepare("DELETE FROM `cars` WHERE `license_plate` = ? AND `driver` = ?");
    $stmt->bind_param("si", $plate, $id);
    $stmt->execute();
    $stmt->close();
    echo "<script>window.location.href = './';</script>";
}

if (isset($_GET['stolen'])) {
    $plate = $_GET['stolen'];
    $checkPlate = $conn->query("SELECT * FROM `cars` WHERE `license_plate` = '$plate' AND `driver` = '$id'");
    if ($checkPlate->num_rows == 1) {
        $stmt = $conn->prepare("UPDATE `cars` SET `stolen` = 1 WHERE `license_plate` = ? AND `driver` = ?");
        $stmt->bind_param("si", $plate, $id);
        $stmt->execute();
        $stmt->close();
        echo "<script>window.location.href = 'car?s=$plate';</script>";
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
        echo "<script>window.location.href = 'car?s=$plate';</script>";
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
    echo "<script>window.location.href = 'meet?id=$meet_id';</script>";
}

if (isset($_POST['updateMeet'])) {
    $meet_id = $_POST['id'];
    $name = htmlspecialchars($_POST['name'], ENT_QUOTES, "UTF-8");
    $date = $_POST['date'];
    $time = $_POST['time'];
    $location = htmlspecialchars($_POST['location'], ENT_QUOTES, "UTF-8");
    $code = htmlspecialchars($_POST['code'], ENT_QUOTES, "UTF-8");
    $private = 0;
    $info = htmlspecialchars($_POST['info'], ENT_QUOTES, "UTF-8");
    $warning = htmlspecialchars($_POST['warning'], ENT_QUOTES, "UTF-8");
    $police = htmlspecialchars($_POST['police'], ENT_QUOTES, "UTF-8");

    if (!empty($code)) {
        $private = 1;
    } else {
        $code = null;
    }

    $datetime = date("Y-m-d H:i:s", strtotime($_POST['date'] . ' ' . $_POST['time']));
    
    $stmt = $conn->prepare("UPDATE `meets` SET `name` = ?, `time` = ?, `location` = ?, `code` = ?, `private` = ?, `info` = ?, `warning` = ?, `police` = ? WHERE `id` = ?");
    $stmt->bind_param("ssssisssi", $name, $datetime, $location, $code, $private, $info, $warning, $police, $meet_id);
    $stmt->execute();
    $stmt->close();
    echo "<script>window.location.href = './meet?id=$meet_id';</script>";
}

if (isset($_POST['cancelMeet'])) {
    $meet_id = $_POST['id'];
    $stmt = $conn->prepare("UPDATE `meets` SET `cancelled` = '1' WHERE `id` = ?");
    $stmt->bind_param("i", $meet_id);
    $stmt->execute();
    $stmt->close();
    echo "<script>window.location.href = './';</script>";
}
if (isset($_POST['deleteMeet'])) {
    $meet_id = $_POST['id'];
    $stmt = $conn->prepare("DELETE FROM `meets` WHERE `id` = ?");
    $stmt->bind_param("i", $meet_id);
    $stmt->execute();
    $stmt->close();
    echo "<script>window.location.href = './';</script>";
}

if (isset($_POST['returnMeet'])) {
    $meet_id = $_POST['id'];
    echo "<script>window.location.href = 'meet?id=$meet_id';</script>";
}

?>