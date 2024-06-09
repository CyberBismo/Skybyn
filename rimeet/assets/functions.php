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
    } else
    if (strpos($url, "forgot") !== false) {
        return "forgot";
    } else {
        return "home";
    }
}

function navItem($x,$driver) { # x = position, y = driver
    global $nav_item_left, $nav_item_center, $nav_item_right;
    $url = checkUrl();

    if ($driver == null) {
        $meet = "";
    } else {
        $meet_id = checkMeet($driver);
        if ($meet_id != null) {
            $meet = "?id=$meet_id";
        } else {
            $meet = "";
        }
    }

    if ($url == "car") {
        $nav_item_left = '<a href="./" class="nav-link"><i class="fa-solid fa-house"></i></a>'; # Home
        $nav_item_center = '<a id="searchBtn" class="nav-link"><i class="fa-solid fa-magnifying-glass"></i></a>'; # Search
        $nav_item_right = '<a href="info" class="nav-link"><i class="fa-solid fa-circle-info"></i></a>'; # Info
    } else
    if ($url == "video") {
        $nav_item_left = '<a href="car" class="nav-link"><i class="fa-solid fa-car"></i></a>'; # Search
        $nav_item_center = '<a id="uploadVideo" class="nav-link"><i class="fa-solid fa-upload"></i></a>'; # Upload video
        $nav_item_right = '<a href="./" class="nav-link"><i class="fa-solid fa-home"></i></a>'; # Home
    } else
    if ($url == "meet") {
        $nav_item_left = '<a href="./" class="nav-link"><i class="fa-solid fa-house"></i></a>'; # Home
        $nav_item_center = '<a id="createMeet" class="nav-link"><i class="fa-solid fa-flag-checkered"></i></a>'; # Create meet
        $nav_item_right = '<a href="video" class="nav-link"><i class="fa-solid fa-video"></i></a>'; # Video
    } else
    if ($url == "info") {
        $nav_item_left = '<a href="./" class="nav-link"><i class="fa-solid fa-arrow-left"></i></a>'; # Return
        $nav_item_center = '<a href="info" class="nav-link"><i class="fa-solid fa-info"></i></a>'; # Home
        $nav_item_right = ''; # Video
    } else
    if ($url == "profile") {
        $nav_item_left = '<a href="./" class="nav-link"><i class="fa-solid fa-arrow-left"></i></a>'; # Return
        $nav_item_center = '<a id="updateProfileBtn" class="nav-link"><i class="fa-solid fa-check"></i></a>'; # Save
        $nav_item_right = '<a href="./?logout" class="nav-link"><i class="fa-solid fa-arrow-right-from-bracket"></i></a>'; # Logout
    } else
    if ($url == "forgot") {
        $nav_item_left = '<a href="./" class="nav-link"><i class="fa-solid fa-arrow-left"></i></a>'; # Return
        $nav_item_center = '<a id="forgotBtn" class="nav-link"><i class="fa-solid fa-paper-plane"></i></a>'; # Forgot
        $nav_item_right = '<a href="./?logout" class="nav-link"><i class="fa-solid fa-arrow-right-from-bracket"></i></a>'; # Logout
    } else {
        $nav_item_left = '<a href="car" class="nav-link"><i class="fa-solid fa-magnifying-glass"></i></a>'; # Search
        $nav_item_center = '<a href="./" class="nav-link"><i class="fa-solid fa-arrows-rotate"></i></a>'; # Refresh
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
        $meet_deleted = $meetInfo['deleted'];
        if ($meet_deleted == 0) {
            return $meet_id;
        } else {
            return null;
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

# Generate random string
function generateRandomString($length = 8) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

# Passenger functions
if (isset($_POST['join_driver'])) {
    $passenger = rand();
    setcookie("passenger", $passenger, time() + (7 * 24 * 60 * 60), "/"); # 7 days
    $_SESSION['passenger'] = $passenger;
    $plate = $_POST['plate'];
    $stmt = $conn->prepare("INSERT INTO `passengers` (`id`, `ip`, `license_plate`) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $passenger, $_SERVER['REMOTE_ADDR'], $plate);
    $stmt->execute();
    $stmt->close();
    header("Location: ./car?s=$plate");
}

if (isset($_SESSION['passenger'])) {
    $passenger = $_SESSION['passenger'];
    $passengers = $conn->query("SELECT * FROM `passengers` WHERE `id` = '$passenger'");
    if ($passengers->num_rows == 0) {
        session_destroy();
        echo "<script>window.location.href = './';</script>";
    } else {
        $passenger = $passengers->fetch_assoc();
        $plate = $passenger['license_plate'];
        $ip = $passenger['ip'];

        if ($ip != $_SERVER['REMOTE_ADDR']) {
            $conn->query("UPDATE `passengers` SET `ip` = '$ip' WHERE `id` = '$passenger'");
        }

        $car = $conn->query("SELECT * FROM `cars` WHERE `license_plate` = '$plate'");
        $car = $car->fetch_assoc();
        $driver = $car['driver'];
    }
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
        setcookie("start", "", time() + (10), "/"); # 1 minute
        $driver = $drivers->fetch_assoc();
        $username = $driver['username'];
        $default_car = $driver['default_car'];
        $phone = $driver['phone'];
        $fullname = $driver['full_name'];
        $doors = $driver['doors'];
        $avatar = $driver['avatar'];

        if ($avatar == null) {
            $avatar = "assets/images/car.png";
        } else {
            $avatar = "uploads/avatars/$id/$avatar";
        }
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
    $id = $_SESSION['driver'];
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
    $name = htmlspecialchars_decode($_POST['name'], ENT_QUOTES | ENT_SUBSTITUTE);
    $date = strtotime($_POST['date']);
    $time = strtotime($_POST['time']);
    $location = htmlspecialchars_decode($_POST['location'], ENT_QUOTES | ENT_SUBSTITUTE);
    $code = htmlspecialchars_decode($_POST['code'], ENT_QUOTES | ENT_SUBSTITUTE);

    if (empty($name)) {
        $name = $username."s treff";
    }
    if (isset($_POST['private'])) {
        $public = 0;
    } else {
        $public = 1;
    }

    $now = strtotime(date("Y-m-d H:i:s"));

    $stmt = $conn->prepare("INSERT INTO `meets` (`driver`,`name`,`location`,`date`,`time`,`public`,`created`,`code`) VALUES (?,?,?,?,?,?,?,?)");
    $stmt->bind_param("issiiiis", $id, $name, $location, $date, $time, $public, $now, $code);
    $stmt->execute();
    $stmt->close();
    $meet_id = $conn->insert_id;
    echo "<script>window.location.href = 'meet?id=$meet_id';</script>";
}

if (isset($_POST['updateMeet'])) {
    $meet_id = $_POST['id'];
    $name = htmlspecialchars_decode($_POST['name'], ENT_QUOTES | ENT_SUBSTITUTE);
    $date = strtotime($_POST['date']);
    $time = strtotime($_POST['time']);
    $location = htmlspecialchars_decode($_POST['location'], ENT_QUOTES | ENT_SUBSTITUTE);
    $code = htmlspecialchars_decode($_POST['code'], ENT_QUOTES | ENT_SUBSTITUTE);
    $info = htmlspecialchars_decode($_POST['info'], ENT_QUOTES | ENT_SUBSTITUTE);
    $warning = htmlspecialchars_decode($_POST['warning'], ENT_QUOTES | ENT_SUBSTITUTE);
    $police = htmlspecialchars_decode($_POST['police'], ENT_QUOTES | ENT_SUBSTITUTE);

    if (isset($_POST['private'])) {
        $public = 0;
    } else {
        $public = 1;
    }
    
    $stmt = $conn->prepare("UPDATE `meets` SET `name` = ?, `location` = ?, `date` = ?, `time` = ?, `public` = ?, `info` = ?, `warning` = ?, `police` = ?, `code` = ? WHERE `id` = ?");
    $stmt->bind_param("ssiiissssi", $name, $location, $date, $time, $public, $info, $warning, $police, $code, $meet_id);
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