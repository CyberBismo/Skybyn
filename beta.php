<?php include_once "./assets/header.php";

if ($beta == true) {
    ?><script>
        setTimeout(() => {
            window.location.href="../register";
        }, 3000);
    </script><?php
}

?>
        <div class="page-container">
            <div class="beta">
                <?php if ($beta == true) {?>
                <h3>You have BETA access</h3>
                <?php } else {?>
                <h3>BETA access</h3>
                <input type="text" placeholder="Enter code" onkeyup="checkBetaCode(this)">
                <p>Have you recieved a beta key? Enter it here to get started.</p>
                <?php }?>
            </div>
        </div>
    </body>
</html>