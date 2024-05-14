<?php include_once "functions.php";

$id = $_SESSION['registration_complete'];
$pack = $_POST['pack'];

if (isset($_POST['private'])) {
    $private = "1";
} else {
    $private = "0";
}

if (isset($_POST['visible'])) {
    $visible = "1";
} else {
    $visible = "0";
}

if ($pack == "op") {
    $conn->query("UPDATE `users` SET `private`='0',`visible`='1' WHERE `id`='$id'");
}
if ($pack == "pp") {
    $conn->query("UPDATE `users` SET `private`='1',`visible`='0' WHERE `id`='$id'");
}
if ($pack == "cp") {
    $conn->query("UPDATE `users` SET `private`='$private',`visible`='$visible' WHERE `id`='$id'");
}
session_destroy();
session_start();
$_SESSION['username'] = $id;

$conn->close();
?>