<?php include_once "./functions.php";

$text = $_POST['text'];

$getGroups = $conn->query("SELECT * FROM `groups` WHERE `id` LIKE '$text%'");
if ($getGroups->num_rows > 0) {
    while($group = $getGroups->fetch_assoc()) {
        $gid = $group['id'];
        $name = $group['name'];
        ?>
        <div class="search_res_group" onclick="window.location.href='./page?id=<?=$gid?>'">
            <?=$name?>
        </div>
        <?php
    }
} else {
    echo "error";
}

?>