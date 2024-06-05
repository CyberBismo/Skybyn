<?php include_once "assets/header.php";
include_once "assets/navigation.php";

if (isset($_GET['id'])) {
    if (!empty($_GET['id']) && is_numeric($_GET['id']) && $_GET['id'] > 0 && $_GET['id'] != null) {
        $meet_id = $_GET['id'];
        $stmt = $conn->prepare("SELECT * FROM `meets` WHERE `id` = '$meet_id'");
        $stmt->execute();
        $result = $stmt->get_result();
        $meet = $result->fetch_assoc();
        $stmt->close();
        if ($meet['private'] == 1) {
            if (isset($_GET['code']) && !empty($_GET['code'])) {
                $code = $_GET['code'];
                if ($code == $meet['code']) {
                    $access = true;
                } else {
                    $access = false;
                }
            }
        } else {
            $access = true;
        }
    } else {
        $meet_id = $_GET['id'];
        $checkMeet = $conn->query("SELECT * FROM `meets` WHERE `id` = '$meet_id'");
        if ($checkMeet->num_rows == 0) {
            echo "<script>window.location.href = './';</script>";
        }
    }
} else {
    if (!isset($_SESSION['driver'])) {
        echo "<script>window.location.href = './';</script>";
    }
}

if ($meet['private'] == 1) {
    if (isset($_POST['unlock'])) {
        $password = $_POST['password'];
        if ($password == $meet['code']) {
            $access = true;
        } else {
            $access = false;
        }
    }
}

if ($meet['driver'] == $id) {
    $access = true;
}

if (isset($_POST['return_meet'])) {
    if (isset($_GET['id'])) {
        echo "<script>window.location.href = './meet?id=$meet_id';</script>";
    } else {
        echo "<script>window.location.href = './meet';</script>";
    }
}

if (isset($_POST['return'])) {
    echo "<script>window.location.href = './';</script>";
}
?>
<style>
</style>
<div class="meet">
<?php if (isset($_GET['id'])) {
    if ($access == false) {?>
    <h1>Privat treff</h1>
    <form method="post">
        <input type="hidden" name="id" value="<?=$meet_id?>">
        <label for="password">Adgangskode:</label>
        <input type="password" name="password" id="password" required><br>
        <br>
        <button type="submit" name="unlock">Lås opp</button>
    </form>
    <form method="post">
        <button type="submit" name="return">Tilbake</button>
    </form>
<?php } else {?>
    <?php if ($meet['driver'] != $id && $meet['cancelled'] == "1") {?>
    <h1>Treffet er avlyst</h1>
    <?php } else {?>
    <?php if ($meet['cancelled'] == "1") {?>
    <h1>Avlyst</h1>
    <?php } else {?>
    <h1><?=$meet['name']?></h1>
    <div class="meet_info">
        <p>Dato: <?=date('d.M - H:i', strtotime($meet['time']))?></p>
        <div class="address">
            <p>Adresse: <?=$meet['location']?></p>
        </div>
    </div>
    <?php }}?>
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
        if (isset($_POST['editMeet'])) {
            if ($meet['cancelled'] == 0) {?>
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
            <label for="private">Adgangskode</label>
            <input type="text" name="code" id="private" placeholder="Gjør treffet privat" value="<?=$meet['code']?>" title="Fyll dette feltet for å gjøre informasjon utilgjengelig før man har skrevet inn koden." autocomplete="new-password">
            <label for="info">Informasjon</label>
            <textarea name="info" id="info" rows="5" placeholder="Informasjon om treffet"><?=$meet['info']?></textarea>
            <label for="police">Politiet</label>
            <input type="text" name="police" id="police" value="<?=$meet['police']?>" placeholder="Politiet er varslet" title="Fyll dette feltet for å informere om at politiet er varslet.">
            <label for="warning">Advarsel</label>
            <input type="text" name="warning" id="warning" value="<?=$meet['warning']?>" placeholder="Advarsel" title="Fyll dette feltet for å informere om at det er en advarsel.">
            <button type="submit" name="updateMeet">Oppdater</button>
        </form>
        <?php }?>
        <form method="post">
            <input type="hidden" name="id" value="<?=$meet_id?>">
            <?php if ($meet['cancelled'] == 1) {?>
            <button type="submit" name="deleteMeet">Slett treff</button>
            <?php } else {?>
            <button type="submit" name="return_meet">Avbryt</button>
            <hr>
            <button type="submit" name="cancel_meet">Avlys Treff</button>
            <?php }?>
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
<?php }} else {
    if (isset($_SESSION['driver'])) {?>
    <h1>Opprett treff</h1>
    <form method="post">
        <label for="name">Navn</label><br>
        <input type="text" name="name" id="name" placeholder="<?=driver("id",$id,"username")?>'s treff" autocomplete="off"><br><br>
        <label for="date">Dato</label><br>
        <input type="date" name="date" id="date" min="<?= date('Y-m-d') ?>" required><br><br>
        <label for="time">Tid</label><br>
        <input type="time" name="time" id="time" required><br><br>
        <label for="location">Sted</label><br>
        <input type="text" name="location" id="location" placeholder="Adresse" required autocomplete="off"><br><br>
        <label for="private">Lukket treff?</label><br>
        <div class="split">
            <input type="text" name="code" id="private" placeholder="Sett adgangskode her" title="Fyll dette feltet for å gjøre informasjon utilgjengelig uten kode" autocomplete="new-password">
            <button type="button" onclick="genCode()"><i class="fa-solid fa-repeat"></i></button>
        </div>
        <br>
        <button type="submit" name="newMeet">Opprett</button>
    </form>
    <script>
        function genCode() {
            var code = Math.random().toString(36).substring(4, 10).toUpperCase();
            document.getElementById('private').value = code;
        }
    </script>
<?php }}?>
</div>