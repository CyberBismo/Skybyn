<?php
define('SECRET_KEY', 'your-64-character-secure-key-here'); // 512-bit key

function encrypt_email($email) {
    // Generate a 256-bit key from a 512-bit SHA-512 hash
    $key = substr(hash('sha512', SECRET_KEY, true), 0, 32); // 256-bit AES key

    // Generate a unique 16-byte IV for each encryption
    $iv = openssl_random_pseudo_bytes(16);

    // Encrypt the email using AES-256-CBC
    $encrypted = openssl_encrypt($email, 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $iv);

    // Store IV with ciphertext (Base64 encoded)
    return base64_encode($iv . $encrypted);
}

function decrypt_email($encrypted_email) {
    // Generate the same 256-bit key from SHA-512
    $key = substr(hash('sha512', SECRET_KEY, true), 0, 32);

    // Decode the Base64 encrypted string
    $data = base64_decode($encrypted_email);

    // Extract the IV (first 16 bytes)
    $iv = substr($data, 0, 16);

    // Extract the encrypted email
    $encrypted_data = substr($data, 16);

    // Decrypt the email
    return openssl_decrypt($encrypted_data, 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $iv);
}

if (isset($_POST['encrypt'])) {
    $encrypt = encrypt_email($_POST['string']);
    $decrypted = decrypt_email($encrypt);
}
?>
