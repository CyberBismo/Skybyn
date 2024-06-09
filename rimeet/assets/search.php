<?php
if (isset($_GET['s'])) {
    $search = $_GET['s'];
} else {
    $search = "";
}
?>
<div class="car_search">
            <form method="get" id="search">
                <label for="search">Søk biler og sjåfører:</label>
                <input type="text" name="s" placeholder="Brukernavn eller skiltnummer" value="<?=$search?>" minLength="2" autofocus autocomplete="new-password">
            </form>
        </div>

        <script>
            document.getElementById("searchBtn").addEventListener("click", function() {
                if (this.value.length >= 2) {
                    document.getElementById("search").submit();
                }
            });
        </script>