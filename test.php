<?php
$to = isset($_POST['to_email']) ? $_POST['to_email'] : '';
$subject = 'Test Email';
$message = 'This is a test email sent to ' . htmlspecialchars($to) . '.';
$headers = 'From: noreply@skybyn.no' . "\r\n" .
           'Reply-To: noreply@skybyn.no' . "\r\n" .
           'X-Mailer: PHP/' . phpversion();
?>

<form method="post">
    <label>Emails will be sent from <b>noreply@skybyn.no</b></label><br><br>
    <label for="to_email">To Email:</label><br>
    <input type="email" id="to_email" name="to_email" required><br>
    <button type="submit">Send Email</button>
</form>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (mail($to, $subject, $message, $headers)) {
        echo 'Email sent successfully to ' . htmlspecialchars($to);
    } else {
        echo 'Email sending to ' . htmlspecialchars($to) . ' failed.';
    }
}
?>