<?php include_once "./functions.php";

$text = $_POST['text'];

// Check if the text starts with "/pages "
if (strpos($text, "/page ") === 0) {
    // Extract the page name or identifier from the text
    $pageName = substr($text, 6); // 7 is the length of "/page "

    $getPages = $conn->query("SELECT * FROM `pages` WHERE `name` LIKE '$pageName%' OR `id` LIKE '$pageName%'");
    if ($getPages->num_rows > 0) {
        while($page = $getPages->fetch_assoc()) {
            $pid = $page['id'];
            $name = $page['name'];
            $icon = "./".$page['icon'];
            if ($icon == "./") {
                $icon = "./assets/images/logo_faded_clean.png";
            }
            ?>
            <div class="search_res_page" onclick="window.location.href='./page?id=<?=$pid?>'">
                <div class="search_res_page_icon">
                    <img src="<?=$icon?>">
                </div>
                <?=$name?>
            </div>
            <?php
        }
    }
}

?>
