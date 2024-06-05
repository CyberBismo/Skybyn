<?php
if (isset($_GET['s'])) {
    $search = $_GET['s'];
} else {
    $search = "";
}
?>
<div class="car_search">
            <form method="get">
                <label for="search">Søk biler og sjåfører:</label>
                <input type="text" name="s" placeholder="Brukernavn eller skiltnummer" value="<?=$search?>" autofocus autocomplete="new-password">
            </form>
        </div>