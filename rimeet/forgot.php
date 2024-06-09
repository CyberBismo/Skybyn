<?php include_once "assets/header.php";
include_once "assets/navigation.php";

if (isset($_POST['forgot'])) {
    if (strlen($_POST['email']) == 4) {
        $token = $conn->real_escape_string($_POST['email']);
        $checkToken = $conn->query("SELECT * FROM `drivers` WHERE `token` = '$token'");
        if ($checkToken->num_rows > 0) {
            $_SESSION['reset'] = $token;
            echo "<script>window.location.href = 'forgot';</script>";
        } else {
            setcookie("error", "Ugyldig kode", time() + 1, "/");
            echo "<script>window.location.href = 'forgot';</script>";
        }
    } else {
        $email = $conn->real_escape_string($_POST['email']);
        $checkEmail = $conn->query("SELECT * FROM `drivers` WHERE `email` = '$email'");
        if ($checkEmail->num_rows > 0) {
            $driverInfo = $checkEmail->fetch_assoc();
            $id = $driverInfo['id'];
            $username = $driverInfo['username'];
            $fullname = $driverInfo['full_name'];

            if ($fullname != "") {
                $username = $fullname;
            }

            $token = rand(1000, 9999);

            $to = $email;
            $subject = "[RIMEET] Nytt passord?";
            $message = "Hei $username,\n\nHar du etterspurt nytt passord?\nHer er koden din for å sette et nytt:\n\n<b>$token<b>\n\n\nMvh,\nRIMEET";
            $headers = "From: RiMeet <no-reply@rimeet.com>\r\n";
            $headers .= "Reply-To: <> \r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
            mail($to, $subject, $message, $headers);
            setcookie("success", "Nytt passord er sendt til $email", time() + 1, "/");
            echo "<script>window.location.href = 'car?signin';</script>";
            return;
        } else {
            setcookie("error", "E-post adressen er ikke registrert", time() + 1, "/");
            echo "<script>window.location.href = 'forgot';</script>";
        }
    }
}

if (isset($_POST['reset'])) {
    $password = $conn->real_escape_string($_POST['password']);
    $confirm = $conn->real_escape_string($_POST['confirm']);
    $token = $_SESSION['reset'];

    if ($password == $confirm) {
        $password = generateRandomString(8);
        $encrypt = hash("sha512", $password);
        $pw = hash("sha512", $encrypt.$salt);
        
        $stmt = $conn->prepare("UPDATE `drivers` SET `password` = ? WHERE `token` = ?");
        $stmt->bind_param("ss", $pw, $token);
        $stmt->execute();
        $stmt->close();
        setcookie("success", "Passordet ble oppdatert", time() + 1, "/");
        echo "<script>window.location.href = 'car?signin';</script>";
    } else {
        setcookie("error", "Passordene er ikke like", time() + 1, "/");
        echo "<script>window.location.href = 'forgot';</script>";
    }
}

if (isset($_SESSION['driver'])) {
    header("Location: ./profile");
}?>
<style>
    .forgot {
        width: 90%;
        margin: 0 auto;
        padding: 20px;
        color: white;
        box-sizing: border-box;
    }
    .forgot form {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    .forgot input {
        padding: 10px;
        border: 1px solid black;
        border-radius: 5px;
    }
    .forgot button {
        padding: 15px;
        border: 1px solid black;
        border-radius: 5px;
        background: black;
        color: white;
    }
</style>
<div class="forgot">
    <form method="post">
        <?php if (isset($_SESSION['reset'])) {?>
        <h2>Opprett nytt passord</h2>
        <label for="password">Nytt passord:</label>
        <input type="password" name="password" id="password" placeholder="Nytt passord" autofocus autocomplete="new-password" required><br>
        <label for="confirm">Bekreft passord:</label>
        <input type="password" name="confirm" id="confirm" placeholder="Bekreft passord" autocomplete="new-password" required><br>
        <button type="submit" name="reset">Bekreft</button>
        <?php } else {?>
        <h2>Glemt passordet?</h2>
        <label for="email">Oppgi koden eller e-post adressen knyttet til kontoen din:</label>
        <input type="text" name="email" id="email" placeholder="E-post adresse / Kode" autofocus autocomplete="new-password" required><br>
        <br>
        <button type="submit" name="forgot">Bekreft</button>
        <?php }?>
    </form>
</div>