<?php include_once "./assets/header.php";

$func = $_GET['func'] ?? '';
?>

<div class="page-container">
    <div class="center_form">
        <div class="form">
            <h2>Admin Panel</h2>
        </div>
    </div><br>

<?php
if ($func == 'emails') {
    $getEmails = $conn->query("SELECT * FROM `users` ORDER BY `id` DESC");
    if ($getEmails->num_rows > 0) {
        while ($email = $getEmails->fetch_assoc()) {
            $id = $email['id'];
            $email = $email['email'];
            echo "<div class='email'><span>#$id</span> | <span>".decrypt($email)."</span> | <span>$email</span></div>";
        }
    } else {
        echo "<div class='no_emails'>No emails found.</div>";
    }
} else
if ($func == 'encryption') {
    ?>
    <div class="encryption">
        <h2>Encryption</h2>
        <form action="" method="GET">
            <input type="hidden" name="func" value="encryption">
            <input type="text" name="text" placeholder="Text to encrypt" required>
            <input type="submit" value="Encrypt">
        </form>
    </div>
    <?php
    if (isset($_GET['text'])) {
        $text = $_GET['text'];
        $encryptedText = encrypt($text);
        echo "<div class='result'>Encrypted Text: <span>$encryptedText</span></div>";
    }
} else
if ($func == 'decryption') {
    ?>
    <div class="decryption">
        <h2>Decryption</h2>
        <form action="" method="GET">
            <input type="hidden" name="func" value="decryption">
            <input type="text" name="text" placeholder="Text to decrypt" required>
            <input type="submit" value="Decrypt">
        </form>
    </div>
    <?php
    if (isset($_GET['text'])) {
        $text = $_GET['text'];
        $decryptedText = decrypt($text);
        echo "<div class='result'>Decrypted Text: <span>$decryptedText</span></div>";
    }
} else {
    ?>
    <div class="admin_options">
        <h2>Admin Options</h2>
        <a href="?func=emails">View Emails</a>
        <a href="?func=encryption">Encrypt Text</a>
        <a href="?func=decryption">Decrypt Text</a>
        <a href="?func=logout">Logout</a>
    </div>
    <?php
}?>
</div>