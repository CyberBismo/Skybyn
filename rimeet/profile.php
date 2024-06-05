<?php include_once "assets/header.php";
include_once "assets/navigation.php";

$username = driver("id", $_SESSION['driver'], "username");
$email = driver("id", $_SESSION['driver'], "email");
$phone = driver("id", $_SESSION['driver'], "phone");

if (isset($_POST['updateProfile'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    $stmt = $conn->prepare("UPDATE `drivers` SET `username` = ?, `email` = ?, `phone` = ? WHERE `id` = ?");
    $stmt->bind_param("sssi", $username, $email, $phone, $_SESSION['driver']);
    $stmt->execute();
    $stmt->close();
    setcookie("success", "Profilen din ble oppdatert", time() + 1, "/");
    echo "<script>window.location.href = './profile';</script>";
}

if (!isset($_SESSION['driver'])) {
    header("Location: ./car?signin");
}?>
<style>
    .profile {
        width: 90%;
        margin: 0 auto;
        padding: 20px;
        box-sizing: border-box;
    }

    .profile form {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .profile input {
        padding: 5px;
        border: 1px solid black;
        border-radius: 5px;
    }

    .profile button {
        padding: 5px;
        border: 1px solid black;
        border-radius: 5px;
        background-color: black;
        color: white;
    }
</style>
<div class="profile">
    <form method="post">
        <label for="username">Brukernavn:</label><br>
        <input type="text" name="username" id="username" value="<?=$username?>" required><br>
        <br>
        <label for="email">E-post adresse:</label><br>
        <input type="email" name="email" id="email" value="<?=$email?>" required><br>
        <br>
        <label for="phone">Telefon:</label><br>
        <input type="tel" name="phone" id="phone" value="<?=$phone?>" required><br>
        <br>
        <label for="doors">Digitale dører:</label><br>
        <input type="checkbox" name="doors" id="doors"><?php if($doors != "closed") {echo "Åpne";} else {echo "Lukk";}?> dine dører for passasjerer.<br>
        <button type="submit" name="updateProfile">Lagre</button>
    </form>
</div>