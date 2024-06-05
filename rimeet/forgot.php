<?php include_once "assets/header.php";
include_once "assets/navigation.php";

if (isset($_POST['forgot'])) {
    $email = $conn->real_escape_string($_POST['email']);
    $checkEmail = $conn->query("SELECT * FROM `drivers` WHERE `email` = '$email'");
    if ($checkEmail->num_rows > 0) {
        $driverInfo = $checkEmail->fetch_assoc();
        $id = $driverInfo['id'];
        $username = $driverInfo['username'];
        $password = $driverInfo['password'];
        $to = $email;
        $subject = "Nytt passord";
        $message = "Hei $username,\n\nDitt passord er: $password\n\nMvh\nNorsk Gjengkriminalitet";
        $headers = "From: RiMeet <no-reply@rimeet.com>\r\n";
        $headers .= "Reply-To: <> \r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
        mail($to, $subject, $message, $headers);
        setcookie("success", "Nytt passord er sendt til $email", time() + 1, "/");
        echo "<script>window.location.href = './car?signin';</script>";
        return;
    } else {
        setcookie("error", "E-post adressen er ikke registrert", time() + 1, "/");
        echo "<script>window.location.href = './forgot';</script>";
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
        padding: 5px;
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
        <h2>Glemt passordet?</h2>
        <label for="email">Oppgi e-post adressen knyttet til kontoen din:</label>
        <input type="email" name="email" id="email" placeholder="E-post adresse" autocomplete="new-password" required><br>
        <br>
        <button type="submit" name="forgot">Motta nytt passord</button>
    </form>
</div>