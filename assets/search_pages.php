<?php include_once "./functions.php";

$text = $_POST['text'];

$getPages = $conn->query("SELECT * FROM `pages` WHERE `id` LIKE '$text%'");
if ($getPages->num_rows > 0) {
    while($page = $getPages->fetch_assoc()) {
        $pid = $page['id'];
        $name = $page['name'];
        $description = $page['description'];
        $icon = "./".$page['icon'];
        ?>
        <div class="search_res_page" onclick="window.location.href='./page?id=<?=$pid?>'">
            <div class="search_res_page_icon">
                <img src="<?=$icon?>">
            </div>
            <?=$name?>
        </div>
        <?php
    }
} else {
    echo "error";
}

?>