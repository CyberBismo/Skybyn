<?php
include("assets/security.php");
include("assets/header.php");
?>

<style>
    form {
        display: flex;
        flex-direction: column;
        width: 50%;
        margin: 0 auto;
        margin-top: 100px;
    }

    input {
        margin: 10px 0;
        padding: 10px;
    }

    .result {
        width: 50%;
        margin: 0 auto;
        text-align: center;
    }
</style>

<form method="post">
    <input type="text" name="string" placeholder="Enter the string to encrypt" autofocus>
    <input type="submit" name="encrypt" value="Encrypt">
</form>
<div class="result">
    <?php if (isset($_POST['string']) && isset($encrypted)) {
        echo "Original String: <br>" . $_POST['string'] . "<br>";
        echo "Encrypted String: <br>" . $encrypted;
    } ?>
</div>