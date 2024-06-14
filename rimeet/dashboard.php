<?php include_once "assets/navigation.php";?>

<style>
</style>
<div class="dashboard">
    <?php if (isset($_SESSION['driver'])) {?>
    <div class="car">
        <div class="driver profile">
            <div class="split">
                <img src="<?=$avatar?>">
                <b><?=$username?></b>
            </div>
            <br>
            Mine biler:<br>
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
            <b class="profile_car"><span onclick="showCar('<?=$plate?>')"><?=strtoupper($plate)?></span><?=$default?></b>
            <?php }?>
            <form method="post" class="add_vehicle">
                <input type="text" name="license_plate" placeholder="+ Legg til bil" alt="Skiltnummer (AA12345)" required>
                <button type="submit" name="add_car"></button>
            </form>
            <?php }?>
            <div class="buttons">
                <button onclick="window.location.href='meet'" class="meet_btn"><i class="fa-solid fa-flag-checkered"></i> Nytt treff</button>
                <button onclick="window.location.href='profile'" class="profile_btn">Profil <i class="fa-solid fa-address-card"></i></button>
            </div>
            <script>
                function showCar(plate) {
                    window.location.href = "car?s="+plate;
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
    <?php } else {?>
    <div class="car">
        <div class="car_btns">
            <button onclick="window.location.href='car?signin'" class="login_btn">Logg inn / Bli sjåfør</button>
        </div>
    </div>
    <?php }?>
    
    <?php
    $meet = false;
    if (isset($_SESSION['driver'])) {
        $id = $_SESSION['driver'];
        $myMeetInfo = $conn->query("SELECT * FROM `meets` WHERE `deleted` = 0 AND `driver` = '$id'");
        if ($myMeetInfo->num_rows > 0) {
            $myMeetInfo = $myMeetInfo->fetch_assoc();
            $myMeet_id = $myMeetInfo['id'];
            $myMeet_host = $myMeetInfo['driver'];
            $myMeet_name = $myMeetInfo['name'];
            $myMeet_date = date("d. M", $myMeetInfo['date']);
            $myMeet_time = date("H:i", $myMeetInfo['time']);
            $myMeet_location = $myMeetInfo['location'];
            $myMeet_public = $myMeetInfo['public'];
            $myMeet_deleted = $myMeetInfo['deleted'];
            $myMeet_warning = $myMeetInfo['warning'];
            $myMeet_info = $myMeetInfo['info'];
            $myMeet_police = $myMeetInfo['police'];
            $myMeet_code = $myMeetInfo['code'];
        ?>
        
    <?php if (!empty($meet_warning)) {?>
    <div class="card red">
        <h1>Advarsel</h1>
        <p><?=$meet_warning?></p>
    </div>
    <?php }?>
    <?php if (!empty($meet_info)) {?>
    <div class="card yellow">
        <h1>Informasjon</h1>
        <p><?=$meet_info?></p>
    </div>
    <?php }?>
    <?php if (!empty($meet_police)) {?>
    <div class="card blue">
        <h1>Politi</h1>
        <p><?=$meet_police?></p>
    </div>
    <?php }?>

    <div class="card white">
        <h1><?=$myMeet_name?></h1>
        <p>Dato: <?=$myMeet_date?></p>
        <p>Tidspunkt: <?=$myMeet_time?></p>
        <p>Adresse: <a href="<?=openMaps($myMeet_location,detectDevice())?>"><?=$myMeet_location?></a></p>
        <?php if ($myMeet_code != "") {?>
        <p>Adgangskode: <?=$myMeet_code?></p>
        <?php }?>
        <?php if ($myMeet_public == 1) {?>
            <p>Treffet er offentlig</p>
        <?php } else {?>
            <p>Treffet er privat</p>
        <?php }?>
        <div class="btns">
            <button onclick="window.location.href='meet?id=<?=$myMeet_id?>'">Se treff</button>
            <button onclick="window.location.href='meet?id=<?=$myMeet_id?>&edit'">Rediger treff</button>
            <button onclick="window.location.href='meet?id=<?=$myMeet_id?>&delete'">Slett treff</button>
        </div>
    </div>
    <?php }
    } else
    if (isset($_SESSION['passenger'])) {
        $id = $_SESSION['passenger'];
        $checkJoiners = $conn->query("SELECT * FROM `joiners` WHERE `joiner` = '$id'");
        if ($checkJoiners->num_rows > 0) {
            $joiner = $checkJoiners->fetch_assoc();
            $meet_id = $joiner['meet_id'];
            $meetInfo = $conn->query("SELECT * FROM `meets` WHERE `id` = '$meet_id'");
            $meetInfo = $meetInfo->fetch_assoc();
            $meet_host = $meetInfo['driver'];
            $meet_name = $meetInfo['name'];
            $meet_date = date("d. M", $meetInfo['date']);
            $meet_time = date("H:i", $meetInfo['time']);
            $meet_location = $meetInfo['location'];
            $meet_public = $meetInfo['public'];
            $meet_deleted = $meetInfo['deleted'];
            $meet_warning = $meetInfo['warning'];
            $meet_info = $meetInfo['info'];
            $meet_police = $meetInfo['police'];
            $meet_code = $meetInfo['code'];
            $meet = true;
        } else {
            $meet = false;
        }
    } else {
        $meet = false;
    }

    if (isset($_SESSION['driver'])) {
        if ($doors == "closed") {
            $checkCar = $conn->query("SELECT * FROM `passengers` WHERE `id` = '$id'");
            if ($checkCar->num_rows > 0) {
                $car = $checkCar->fetch_assoc();
                $car_plate = $car['license_plate'];

                $checkPlate = $conn->query("SELECT * FROM `cars` WHERE `license_plate` = '$car_plate'");
                if ($checkPlate->num_rows > 0) {
                    $carInfo = $checkPlate->fetch_assoc();
                    $car_driver = $carInfo['driver'];

                    $checkDriver = $conn->query("SELECT * FROM `drivers` WHERE `id` = '$car_driver'");
                    if ($checkDriver->num_rows > 0) {
                        $driver = $checkDriver->fetch_assoc();
                        $driver_name = $driver['username'];
                        
                        if ($driver['avatar'] == "" || !file_exists("./uploads/avatars/".$car_driver."/".$driver['avatar'])) {
                            $driver_avatar = "./assets/img/car.png";
                        } else {
                            $driver_avatar = "./uploads/avatars/".$car_driver."/".$driver['avatar'];
                        }
                        ?>
                        <div class="card green">
                            <div class="btns">
                                <img src="<?=$driver_avatar?>">
                                <h2><?=$driver_name?></h2>
                            </div>
                            <p onclick="window.location.href='car?s=<?=$car_plate?>'"><b><?=$car_plate?></b></p>
                            <form method="post" class="btns">
                                <button onclick="window.location.href='car?s=<?=$driver_name?>'">Sjåfør</button>
                                <input type="hidden" name="license_plate" value="<?=$car_plate?>">
                                <button type="submit" name="leave_driver">Forlat bil</button>
                            </form>
                        </div>
                        <?php
                    }
                }
            }
        } else {
            $checkCar = $conn->query("SELECT * FROM `passengers` WHERE `license_plate` = '$default_car'");
            if ($checkCar->num_rows > 0) {
                ?>
                <div class="card green passengers">
                    <h2>Passasjerer</h2>
                <?php
                while($car = $checkCar->fetch_assoc()) {
                    $car_passenger = $car['id'];
                    $car_passenger_nickname = $car['nickname'];

                    $checkDriver = $conn->query("SELECT * FROM `drivers` WHERE `id` = '$car_passenger'");
                    if ($checkDriver->num_rows > 0) {
                        $driver = $checkDriver->fetch_assoc();
                        $driver_name = $driver['username'];

                        if ($driver['avatar'] == "" || !file_exists("./uploads/avatars/".$car_passenger."/".$driver['avatar'])) {
                            $driver_avatar = "./assets/img/car.png";
                        } else {
                            $driver_avatar = "./uploads/avatars/".$car_passenger."/".$driver['avatar'];
                        }
                        ?>
                        <div class="split">
                            <img src="<?=$driver_avatar?>">
                            <p onclick="window.location.href='car?s=<?=$driver_name?>'"><b><?=$driver_name?></b></p>
                            <form method="post">
                                <input type="hidden" name="passenger" value="<?=$car_passenger?>">
                                <button type="submit" name="remove_passenger"><i class="fa-solid fa-xmark"></i></button>
                            </form>
                        </div>
                        <?php
                    } else {
                        $driver_avatar = "./assets/img/car.png";
                        ?>
                        <div class="split">
                            <img src="<?=$driver_avatar?>">
                            <p><b><?=$car_passenger_nickname?></b></p>
                            <form method="post">
                                <input type="hidden" name="passenger" value="<?=$car_passenger?>">
                                <button type="submit" name="remove_passenger"><i class="fa-solid fa-xmark"></i></button>
                            </form>
                        </div>
                        <?php
                    }
                }
                ?>
                </div>
                <?php
            }
        }
    }

    if ($meet == true) {?>
    <div class="card white">
        <h1><?=$meet_name?></h1>
        <?php if ($meet_deleted == 1) {?>
        <p>Treffet er avlyst</p>
        <?php } else {?>
        <?php if ($meet_public == 1) {?>
        <p>Tidspunkt: <?=$meet_time?></p>
        <p>Adresse: <a href="<?=openMaps($meet_location,detectDevice())?>"><?=$meet_location?></a></p>
        <?php } else {?>
        <p>Informasjon utilgjengelig</p>
        <?php }}?>
    </div>
    <?php } else {
        $getMeets = $conn->query("SELECT * FROM `meets` WHERE `deleted` = 0 AND `public` = 1");
        if ($getMeets->num_rows > 0) {
            while ($meetData = $getMeets->fetch_assoc()) {
                $meet_id = $meetData['id'];
                $meet_host = $meetData['driver'];
                $meet_name = $meetData['name'];
                $meet_date = date("d. M", $meetData['date']);
                $meet_time = date("H:i", $meetData['time']);
                $meet_location = $meetData['location'];
                $meet_public = $meetData['public'];
                $meet_deleted = $meetData['deleted'];
                $meet_warning = $meetData['warning'];
                $meet_info = $meetData['info'];
                $meet_police = $meetData['police'];
                $meet_code = $meetData['code'];

                $checkJoiners = $conn->query("SELECT * FROM `joiners` WHERE `joiner` = '$id' AND `meet_id` = '$meet_id'");
                $joiners = $checkJoiners->num_rows;
        ?>
        <div class="card white">
            <h1><?=$meet_name?></h1>
            <?php if ($meet_code == "") {?>
            <p>Dato: <?=$meet_date?></p>
            <p>Tidspunkt: <?=$meet_time?></p>
            <p>Adresse: <a href="<?=openMaps($meet_location,detectDevice())?>"><?=$meet_location?></a></p>
            <?php }?>
            <?php if ($joiners == 0) {?>
            <button onclick="window.location.href='meet?id=<?=$meet_id?>'">Bli med</button>
            <?php }?>
        </div>
        <?php }?>
    <?php } else {?>
    <div class="card white">
        <h1>Ingen planlagte treff</h1>
    </div>
    <?php }?>
<?php }?>
</div>