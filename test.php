<?php include_once "assets/header.php";
if (!isset($_SESSION['user'])) {
    ?><meta http-equiv="Refresh" content="0; url='./'" /><?php
}
?>
<?php if (isset($_SESSION['user'])) {?>
<div class="page-container">
    <h1><?=getIP()?></h1>
    <p>
        <?php
        $getData = json_decode(geoData(null), true);
        echo '<table>';
        foreach ($getData as $key => $value) {
            echo '<tr>';
            echo '<td>' . htmlspecialchars($key) . '</td>';
            echo '<td>' . htmlspecialchars($value) . '</td>';
            echo '</tr>';
        }
        echo '</table>';
        ?>
    </p>
</div>
<?php }?>