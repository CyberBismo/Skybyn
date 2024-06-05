<?php include_once "assets/header.php";
include_once "assets/navigation.php";

if (!isset($_SESSION['driver'])) {
    header("Location: ./car.php?signin");
}

if (isset($_GET['id'])) {
    if (!empty($_GET['id']) && is_numeric($_GET['id']) && $_GET['id'] > 0 && $_GET['id'] != null) {
        $meet_id = $_GET['id'];
        $stmt = $conn->prepare("SELECT * FROM `meets` WHERE `id` = '$meet_id'");
        $stmt->execute();
        $result = $stmt->get_result();
        $meet = $result->fetch_assoc();
        $stmt->close();
    } else {
        echo "<script>window.location.href = './meet';</script>";
    }
}

if (isset($_POST['return_meet'])) {
    if (isset($_GET['id'])) {
        echo "<script>window.location.href = './meet?id=$meet_id';</script>";
    } else {
        echo "<script>window.location.href = './meet';</script>";
    }
}
?>
<style>
</style>
<?php if (isset($_GET['id'])) {?>
<div class="meet">
    <h1><?=$meet['name']?></h1>
    <div class="meet_info">
        <p>Dato: <?=date('d.M - H:i:s', strtotime($meet['time']))?></p>
        <div class="address">
            <p>Adresse: <?=$meet['address_visible'] == 1 ? $meet['location'] : "Skjult"?></p>
            <?php if (isset($_SESSION['driver'])) {
            if ($meet['driver'] == $_SESSION['driver']) {?>
            <form method="post">
                <input type="hidden" name="id" value="<?=$meet_id?>">
                <label for="address_visible"><?=$meet['address_visible'] == 1 ? "Skjul" : "Vis"?></label>
                <input type="checkbox" name="address_visible" id="address_visible" <?=$meet['address_visible'] == 1 ? "checked" : ""?> hidden>
                <input type="submit" name="meet_visibility" id="meet_visibility" hidden>
            </form>
            <script>
                document.getElementById('address_visible').addEventListener('change', function() {
                    setTimeout(() => {
                        this.form.submit();
                    }, 200);
                });
            </script>
            <?php }}?>
        </div>
    </div>
    <?php if (isset($_SESSION['driver'])) {?>
    <div class="meet_options">
        <?php if (isset($_POST['cancel_meet'])) {?>
        <form method="post">
            <input type="hidden" name="id" value="<?=$meet_id?>">
            <p>Er du sikker på at du vil avlyse treffet?</p>
            <button type="submit" name="cancelMeet">Ja</button>
            <button type="submit" name="editMeet">Nei</button>
        </form>
        <hr>
        <?php } else
        if (isset($_POST['editMeet'])) {?>
        <form method="post">
            <input type="hidden" name="id" value="<?=$meet_id?>">
            <label for="name">Navn</label>
            <input type="text" name="name" id="name" value="<?=$meet['name']?>" required>
            <label for="date">Dato</label>
            <input type="date" name="date" id="date" value="<?=date('Y-m-d', strtotime($meet['time']))?>" required>
            <label for="time">Tid</label>
            <input type="time" name="time" id="time" value="<?=date('H:i', strtotime($meet['time']))?>" required>
            <label for="location">Sted</label>
            <input type="text" name="location" id="location" value="<?=$meet['location']?>" required>
            <button type="submit" name="updateMeet">Oppdater</button>
        </form>
        <form method="post">
            <button type="submit" name="return_meet">Avbryt</button>
            <hr>
            <button type="submit" name="cancel_meet">Avlys Treff</button>
        </form>
        <hr>
        <?php } else {?>
        <form method="post" class="meet_btns">
            <button type="submit" name="editMeet">Endre treffet</button>
        </form>
        <?php }?>
    </div>
    <?php }?>
    <div class="joiners">
        <h2>Deltakere</h2>
        <?php
        $stmt = $conn->prepare("SELECT * FROM `joiners` WHERE `meet_id` = $id");
        $stmt->execute();
        $joiners = $stmt->get_result();
        $stmt->close();

        if ($joiners->num_rows > 0) {
            while ($joiner = $joiners->fetch_assoc()) {
                $stmt = $conn->prepare("SELECT * FROM `drivers` WHERE `license_plate` = ?");
                $stmt->bind_param("s", $joiner['driver']);
                $stmt->execute();
                $driver = $stmt->get_result()->fetch_assoc();
                $stmt->close();
                ?>
        <div class="joiner">
            <p><?=$driver['username']?></p>
            <p><?=$driver['license_plate']?></p>
        </div>
        <?php }
        } else {?>
        <p>Ingen deltakere</p>
        <?php }?>
    </div>
</div>
<?php } else {?>
<div class="meet">
    <h1>Opprett treff</h1>
    <form method="post">
        <label for="name">Navn</label><br>
        <input type="text" name="name" id="name" placeholder="<?=driver("id",$id,"username")?>'s treff" autocomplete="off"><br>
        <label for="date">Dato</label><br>
        <input type="date" name="date" id="date" required><br>
        <label for="time">Tid</label><br>
        <input type="time" name="time" id="time" required><br>
        <label for="location">Sted</label><br>
        <input type="text" name="location" id="location" placeholder="Adresse" required autocomplete="off"><br>
        <br>
        <button type="submit" name="newMeet">Opprett</button>
    </form>
</div>
<?php }?>