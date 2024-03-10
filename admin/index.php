<?php include("../assets/functions.php");
$devDomain = 'dev.skybyn.no';
$currentUrl = domain();
if ($currentUrl == $devDomain) {
    $dev_access = true;
}

if ($dev_access) {
    include("../assets/functions.php");
} else {
    include("https://skybyn.no/assets/functions.php");
}

session_start();

if (!isset($_SESSION['user'])) {
    if ($dev_access) {
        header('Location: ../');
    } else {
        header('Location: https://skybyn.no/');
    }
    exit;
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Skybyn Administration</title>
</head>
<body>
    
</body>
</html>