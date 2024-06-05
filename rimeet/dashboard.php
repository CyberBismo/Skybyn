<?php include_once "assets/header.php";
include_once "assets/navigation.php";

if (!isset($_SESSION['driver'])) {
    header("Location: ./car.php?signin");
}

if (isset($_SESSION['driver'])) {
    $id = $_SESSION['driver'];
    $username = driver("id", $id, "username");
    $meets = $conn->query("SELECT * FROM `meets` WHERE `driver` != '$id'");
} else {
    $meets = $conn->query("SELECT * FROM `meets`");
}

if ($meets->num_rows > 0) {
    $meetInfo = $meets->fetch_assoc();
    $meet = $meetInfo['id'];
    $meet_driver = $meetInfo['driver'];
    $meet_name = $meetInfo['name'];
    $meet_time = $meetInfo['time'];
    $meet_location = $meetInfo['location'];
    $meet_visibility = $meetInfo['address_visible'];
    $meet_cancelled = $meetInfo['cancelled'];
    $meet_warning = $meetInfo['warning'];
    $meet_info = $meetInfo['info'];
    $meet_police = $meetInfo['police'];

    if (!empty($meet_warning)) {
        $warning = true;
    } else {
        $warning = false;
    }

    if (!empty($meet_info)) {
        $info = true;
    } else {
        $info = false;
    }

    if (!empty($meet_police)) {
        $police = true;
    } else {
        $police = false;
    }

    $checkVideos = $conn->query("SELECT * FROM `videos` WHERE `meet_id` = $meet");
    if ($checkVideos->num_rows > 0) {
        $videos = true;
    } else {
        $videos = false;
    }
    
    if (isset($_SESSION['driver'])) {
        if ($meet_driver == $id) {
            $meet = false;
        } else {
            $meet = true;
        }
    } else {
        $meet = true;
    }
} else {
    $police = false;
    $videos = false;
    $warning = false;
    $info = false;
    $meet = false;
}

?>

<style>
</style>
<?php if (isset($_SESSION['driver'])) {?>
<div class="car">
    <div class="driver">
        <div class="profile">
            Brukernavn:<br>
            <b><?=$username?></b><br>
            <br>
            Biler:<br>
            <?php
            $cars = $conn->query("SELECT * FROM `cars` WHERE `driver` = '$id'");
            $carsCount = $cars->num_rows;
            if ($carsCount == 0) {?>
            <form method="post" class="add_vehicle">
                <input type="text" name="license_plate" placeholder="+ Legg til bil" alt="Skiltnummer (AA12345)" required>
                <button type="submit" name="add_car"></button>
            </form>
            <?php } else {
                while($car = $cars->fetch_assoc()) {
                    $plate = $car['license_plate'];

                    if ($default_car == $plate) {
                        $default = ' <i class="fa-solid fa-star"></i>';
                    } else {
                        $default = ' <i class="fa-regular fa-star" onclick="makeDefault(\''.$plate.'\')"></i>';
                    }
            ?>
            <b class="profile_car"><span onclick="showCar('<?=$plate?>')"><?=$plate?></span><?=$default?></b>
            <?php }?>
            <form method="post" class="add_vehicle">
                <input type="text" name="license_plate" placeholder="+ Legg til bil" alt="Skiltnummer (AA12345)" required>
                <button type="submit" name="add_car"></button>
            </form>
            <?php }?>
        </div>
        <div class="buttons">
            <button onclick="window.location.href='.?logout'" class="signout">Logg ut</button>
            <?php if (checkMeet($id) == false) {?>
            <button onclick="window.location.href='./meet'" class="meet_btn">Opprett treff</button>
            <?php } else {?>
            <button onclick="window.location.href='./meet?id=<?=checkMeet($id)?>'" class="meet_btn">Treff info</button>
            <?php }?>
        </div>
        <script>
            function showCar(plate) {
                window.location.href = "./car?s="+plate;
            }
            function makeDefault(plate) {
                window.location.href = "./?setDefault="+plate;
            }
            function deleteCar(plate) {
                if (confirm("Er du sikker på at du vil fjerne bilen?")) {
                    window.location.href = "./?removeCar="+plate;
                }
            }
        </script>
    </div>
</div>
<?php }?>
<div class="dashboard">
    <?php if ($warning == true) {?>
    <div class="card red">
        <h1>Advarsel</h1>
        <p><?=$meet_warning?></p>
    </div>
    <?php }?>
    <?php if ($info == true) {?>
    <div class="card yellow">
        <h1>Informasjon</h1>
        <p><?=$meet_info?></p>
    </div>
    <?php }?>
    <?php if ($police == true) {?>
    <div class="card blue">
        <h1>Politi</h1>
        <p><?=$meet_police?></p>
    </div>
    <?php }?>
    <?php if ($videos == true) {?>
    <div class="card green">
        <h1>Videoer</h1>
        <p>Det er delt videoer for dette treffet.</p>
        <button onclick="window.location.href='./video'">Se videoer</button>
    </div>
    <?php }



    if (isset($_SESSION['driver'])) {
        $myMeetInfo = $conn->query("SELECT * FROM `meets` WHERE `driver` = '$id'");
        if ($myMeetInfo->num_rows > 0) {
            $myMeetInfo = $myMeetInfo->fetch_assoc();
            $myMeet_host = $myMeetInfo['driver'];
            $myMeet_name = $myMeetInfo['name'];
            $myMeet_time = $myMeetInfo['time'];
            $myMeet_location = $myMeetInfo['location'];
            $myMeet_visibility = $myMeetInfo['address_visible'];
            $myMeet_cancelled = $myMeetInfo['cancelled'];
            $myMeet_warning = $myMeetInfo['warning'];
            $myMeet_info = $myMeetInfo['info'];
            $myMeet_police = $myMeetInfo['police'];
        ?>
    <div class="card white">
        <h1><?=$meet_name?></h1>
        <?php if ($myMeet_cancelled == 1) {?>
        <p>Treffet er avlyst</p>
        <?php } else {?>
        <?php if ($meet_visibility == 1) {?>
        <p>Adresse: <?=$meet_location?></p>
        <p>Tidspunkt: <?=$meet_time?></p>
        <?php } else {
        if ($meet_host == $id) {?>
        <button onclick="window.location.href='./meet?id=<?=$meetInfo['id']?>'">Treff info</button>
        <?php } else {?>
        <p>Informasjon utilgjengelig</p>
        <?php }}}?>
    </div>
    <?php } else {
        $checkJoiners = $conn->query("SELECT * FROM `joiners` WHERE `joiner` = '$id'");
        if ($checkJoiners->num_rows > 0) {
        $joiner = $checkJoiners->fetch_assoc();
        $meet_id = $joiner['meet_id'];
        $meetInfo = $conn->query("SELECT * FROM `meets` WHERE `id` = '$meet_id'");
        $meetInfo = $meetInfo->fetch_assoc();
        $meet_host = $meetInfo['driver'];
        $meet_name = $meetInfo['name'];
        $meet_time = $meetInfo['time'];
        $meet_location = $meetInfo['location'];
        $meet_visibility = $meetInfo['address_visible'];
        $meet_cancelled = $meetInfo['cancelled'];
        $meet_warning = $meetInfo['warning'];
        $meet_info = $meetInfo['info'];
        $meet_police = $meetInfo['police'];
    ?>
    <div class="card white">
        <h1><?=$meet_name?></h1>
        <?php if ($meet_cancelled == 1) {?>
        <p>Treffet er avlyst</p>
        <?php } else {?>
        <?php if ($meet_visibility == 1) {?>
        <p>Adresse: <?=$meet_location?></p>
        <p>Tidspunkt: <?=$meet_time?></p>
        <?php } else {?>
        <p>Informasjon utilgjengelig</p>
        <?php }}?>
    </div>
    <?php }}
    }



    if ($meet == true) {?>
    <div class="card white">
        <h1><?=$meet_name?></h1>
        <?php if ($meet_cancelled == 1) {?>
        <p>Treffet er avlyst</p>
        <?php } else {?>
        <?php if ($meet_visibility == 1) {?>
        <p>Adresse: <?=$meet_location?></p>
        <p>Tidspunkt: <?=$meet_time?></p>
        <?php } else {?>
        <p>Informasjon utilgjengelig</p>
        <?php }}?>
    </div>
    <?php } else {?>
    <div class="card white">
        <h1>Ingen planlagte treff</h1>
    </div>
    <?php }?>
</div>