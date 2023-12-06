<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>TeamViewer Remote Control</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="">
    </head>
    <body>
        <?php
        if (isset($_GET['connect'])) {
            if (isset($_GET['id']) && !empty($_GET['id'])) {
                $id = $_GET['id'];
                if (isset($_GET['pw']) && !empty($_GET['pw'])) {
                    $pw = $_GET['pw'];
                    ?>
                    <a href="teamviewer10://remotecontrol?device=<?=$id?>&pin=<?=$pw?>">Connect TeamViewer</a>
                    <br><br>
                    <a href="./teamviewer?connect&id=<?=$id?>&pin=<?=$pw?>">Edit</a>
                    <br>
                    <a href="teamviewer10://remotecontrol">Open TeamViewer</a> instead
                    <?php
                } else {
                    ?>
                    <form>
                        <input type="hidden" name="connect">
                        <input type="number" name="id" value="<?=$id?>" required>
                        <input type="text" name="pw" placeholder="Password" required autofocus>
                        <input type="submit" value="Send">
                    </form>
                    <br><br>
                    <a href="teamviewer10://remotecontrol">Open TeamViewer</a> instead
                    <?php
                }
            } else {
                if (isset($_GET['pw']) && !empty($_GET['pw'])) {
                    $pw = $_GET['pw'];
                } else {
                    $pw = "";
                }
                ?>
                <form>
                    <input type="hidden" name="connect">
                    <input type="number" name="id" placeholder="ID" required autofocus>
                    <input type="text" name="pw" value="<?=$pw?>" placeholder="Password" required>
                    <input type="submit" value="Send">
                </form>
                <br><br>
                <a href="teamviewer10://remotecontrol">Open TeamViewer</a> instead
                <?php
            }
        } else {
            ?>
            <a href="teamviewer10://remotecontrol">Open TeamViewer</a> or <a href="./teamviewer?connect">enter connection</a>
            <?php
        }
        ?>
    </body>
</html>