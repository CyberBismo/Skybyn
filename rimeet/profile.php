<?php include_once "assets/header.php";
include_once "assets/navigation.php";

$username = driver("id", $_SESSION['driver'], "username");
$email = driver("id", $_SESSION['driver'], "email");
$phone = driver("id", $_SESSION['driver'], "phone");

if (isset($_POST['updateProfile'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    
    if (isset($_POST['doors'])) {
        $doors = "closed";
    } else {
        $doors = "open";
    }

    $stmt = $conn->prepare("UPDATE `drivers` SET `username` = ?, `email` = ?, `phone` = ?, `doors` = ? WHERE `id` = ?");
    $stmt->bind_param("ssssi", $username, $email, $phone, $doors, $_SESSION['driver']);
    $stmt->execute();
    $stmt->close();
    setcookie("success", "Profilen din ble oppdatert", time() + 1, "/");
    echo "<script>window.location.href = './profile';</script>";
}

if (isset($_POST['updateAvatar'])) {
    $avatar = $_FILES['avatar'];
    $avatarName = $avatar['name'];
    $avatarTmp = $avatar['tmp_name'];
    $avatarSize = $avatar['size'];
    $avatarError = $avatar['error'];
    $avatarExt = explode(".", $avatarName);
    $avatarActualExt = strtolower(end($avatarExt));
    $allowed = array("jpg", "jpeg", "png");

    if (in_array($avatarActualExt, $allowed)) {
        if ($avatarError === 0) {
            if ($avatarSize < 1000000) {
                $avatarNameNew = "avatar_".$_SESSION['driver'].".".$avatarActualExt;
                $avatarDestination = "uploads/avatars/".$_SESSION['driver']."/".$avatarNameNew;
                if (!file_exists("uploads/avatars/".$_SESSION['driver'])) {
                    mkdir("uploads/avatars/".$_SESSION['driver']);
                }
                move_uploaded_file($avatarTmp, $avatarDestination);
                $stmt = $conn->prepare("UPDATE `drivers` SET `avatar` = ? WHERE `id` = ?");
                $stmt->bind_param("si", $avatarNameNew, $_SESSION['driver']);
                $stmt->execute();
                $stmt->close();
                setcookie("success", "Profilbilde ble oppdatert", time() + 1, "/");
                echo "<script>window.location.href = 'profile';</script>";
            } else {
                setcookie("error", "Filen er for stor", time() + 1, "/");
                echo "<script>window.location.href = 'profile';</script>";
            }
        } else {
            setcookie("error", "Det oppstod en feil", time() + 1, "/");
            echo "<script>window.location.href = 'profile';</script>";
        }
    } else {
        setcookie("error", "Du kan ikke laste opp filer av denne typen", time() + 1, "/");
        echo "<script>window.location.href = 'profile';</script>";
    }
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

    .profile img {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        object-fit: cover;
        margin: 0 auto;
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
    <form method="post" enctype="multipart/form-data">
        <img src="<?=$avatar?>" id="avatar_preview" onclick="document.getElementById('avatar').click()">
        <input type="file" name="avatar" id="avatar" onchange="previewAvatar()" hidden>
        <button type="submit" name="updateAvatar" id="updateAvatar">Oppdater profilbilde</button>
    </form>
    <script>
        function previewAvatar() {
            var file = document.getElementById("avatar").files[0];
            var reader = new FileReader();
            reader.onloadend = function() {
                document.getElementById("avatar_preview").src = reader.result;
            }
            if (file) {
                reader.readAsDataURL(file);
            }
        }
    </script>
    <form method="post">
        <label for="username">Brukernavn:</label>
        <input type="text" name="username" id="username" value="<?=$username?>" required>
        <label for="email">E-post adresse:</label>
        <input type="email" name="email" id="email" value="<?=$email?>" required>
        <label for="phone">Telefon:</label>
        <input type="tel" name="phone" id="phone" value="<?=$phone?>" required>
        <div class="split">
            <label for="doors"><?php if($doors != "closed") {echo "Lukk";} else {echo "Åpne";}?> digitale dører:</label>
            <input type="checkbox" name="doors" id="doors">
        </div>
        <button type="submit" name="updateProfile" id="updateProfile" hidden></button>
    </form>
</div>

<script>
    document.getElementById("updateProfileBtn").addEventListener("click", function() {
        document.getElementById("updateProfile").click();
    });
</script>