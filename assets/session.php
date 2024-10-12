<?php include "./conn.php";

if (isset($_POST['user'])) {
    $user = $_POST['user'];
    $checkUser = $conn->query("SELECT * FROM `users` WHERE `id`='$user'");
    if ($checkUser->num_rows == 0) {
        echo json_encode(['user' => '']);
        exit();
    } else {
        $row = $checkUser->fetch_assoc();
        $_SESSION['user'] = $row['id'];
        echo json_encode(['user' => $row['id']]);
    }
}
?>