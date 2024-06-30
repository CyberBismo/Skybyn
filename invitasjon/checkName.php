<?php
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'elitesys_bismoa');
define('DB_PASSWORD', 'fEIvzleT-tYl');
define('DB_NAME', 'elitesys_bismo');

$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

if (isset($_POST['navn'])) {
    $navn = $_POST['navn'];
    $sql = "SELECT * FROM `invitasjoner` WHERE `navn` = '$navn'";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        echo "Name already exists";
    } else {
        echo "Name available";
    }
}

?>