<?php include_once "assets/functions.php";
if (isset($_SESSION['driver'])) {
    $id = $_SESSION['driver'];
} else {
    $id = null;
}
?>
<style>
</style>
<div class="nav">
    <div class="nav-item">
        <?=navItem("left", $id)?>
    </div>
    <div class="nav-item nav-center">
        <?=navItem("center", $id)?>
    </div>
    <div class="nav-item">
        <?=navItem("right", $id)?>
    </div>
</div>