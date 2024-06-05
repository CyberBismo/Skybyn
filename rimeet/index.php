        <?php include_once "assets/header.php";
        if (isset($_COOKIE['start'])) {
            echo "<script>window.location.href = './dashboard';</script>";
        } else {
            echo "<script>window.location.href = './start';</script>";
        }
        ?>
    </body>
</html>
