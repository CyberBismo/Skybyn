<?php include_once "./functions.php";

$text = $_POST['text'];

$getUsers = $conn->query("SELECT * FROM `users` WHERE `username` LIKE '$text%'");
if ($getUsers->num_rows > 0) {
    while($user = $getUsers->fetch_assoc()) {
        $username = $user['username'];
        $avatar = "./".$user['avatar'];
        if ($avatar == "./") {
            $avatar = "./assets/images/logo_faded_clean.png";
        }
        ?>
        <div class="search_res_user" onclick="window.location.href='./profile?u=<?=$username?>'">
            <div class="search_res_user_avatar">
                <img src="<?=$avatar?>">
            </div>
            <?=$username?>
        </div>
        <?php
    }
} else {
    echo "error";
}

?>