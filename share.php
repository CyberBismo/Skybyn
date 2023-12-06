<?php include_once "assets/header.php"?>
<?php
if (!isset($_SESSION['user'])) {
    ?><meta http-equiv="Refresh" content="url='./'" /><?php
}
?>
        <form method="post" enctype="multipart/form-data" id="share" class="create_post">
            <textarea name="text" placeholder="What's on your mind?" autofocus></textarea>
            <div class="create_post_actions">
                <input type="file" name="file" accept="image/*">
                <div class="create_post_send" onclick="share()"><i class="fa-solid fa-paper-plane"></i></div>
            </div>
            <input type="hidden" name="share">
        </form>

        <script>
            function share() {
                const share = document.getElementById('share');
                share.submit();
            }
        </script>
    </body>
</html>
