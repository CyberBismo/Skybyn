<?php include_once "assets/header.php";
include_once "assets/navigation.php";

if (isset($_POST['signin'])) {
    echo "<script>window.location.href = './car?signin';</script>";
}
if (isset($_POST['signup'])) {
    echo "<script>window.location.href = './car?signup';</script>";
}

if (isset($_POST['updatePhoto'])) {
    $plate = $_POST['plate'];
    $photo = $_FILES['vehicle_photo'];
    $photoName = $photo['name'];
    $photoTmp = $photo['tmp_name'];
    $photoSize = $photo['size'];
    $photoError = $photo['error'];
    $photoExt = explode(".", $photoName);
    $photoActualExt = strtolower(end($photoExt));
    $allowed = array("jpg", "jpeg", "png");

    if (in_array($photoActualExt, $allowed)) {
        if ($photoError === 0) {
            if ($photoSize < 1000000) {
                $photoNameNew = "vehicle_".$plate.".".$photoActualExt;
                $photoDestination = "uploads/vehicles/".$plate."/".$photoNameNew;
                if (!file_exists("uploads/vehicles/".$plate)) {
                    mkdir("uploads/vehicles/".$plate);
                }
                move_uploaded_file($photoTmp, $photoDestination);
                $stmt = $conn->prepare("UPDATE `cars` SET `photo` = ? WHERE `license_plate` = ?");
                $stmt->bind_param("ss", $photoNameNew, $plate);
                $stmt->execute();
                $stmt->close();
                setcookie("success", "Bilde ble oppdatert", time() + 1, "/");
                echo "<script>window.location.href = 'car?s=$plate';</script>";
            } else {
                setcookie("error", "Filen er for stor", time() + 1, "/");
                echo "<script>window.location.href = 'car?s=$plate';</script>";
            }
        } else {
            setcookie("error", "Det oppstod en feil", time() + 1, "/");
            echo "<script>window.location.href = 'car?s=$plate';</script>";
        }
    } else {
        setcookie("error", "Filtypen er ikke tillatt", time() + 1, "/");
        echo "<script>window.location.href = 'car?s=$plate';</script>";
    }
}
?>
<style>
</style>
<div class="car">
    <?php
    if (isset($_GET['signin'])) {
        ?>
        <form method="post" class="login">
            <input type="text" name="username" placeholder="Brukernavn" autofocus required>
            <input type="password" name="password" placeholder="Passord" required>
            <button type="submit" name="login">Logg inn</button>
            <a href="./forgot">Glemt passord?</a>
        </form>
        <br>
        <form method="get" class="login">
        <button type="submit" name="signup" class="signup_btn">Bli sjåfør</button>
        </form>
        <?php
    } else
    if (isset($_GET['signup'])) {
        ?>
        <form method="post" class="register">
            <input type="text" name="full_name" placeholder="Fullt navn" autocomplete="new_password" required autofocus>
            <input type="tel" name="phone" placeholder="Telefon" autocomplete="new_password" required>
            <input type="email" name="email" placeholder="E-post adresse" autocomplete="new_password" required>
            <input type="text" name="username" placeholder="Brukernavn" autocomplete="new_password" required>
            <input type="password" name="password" placeholder="Passord" autocomplete="new_password" required>
            <input type="password" name="verify_password" placeholder="Bekreft passord" autocomplete="new_password" required>
            <button type="submit" name="register">Bli sjåfør</button>
        </form>
        <br>
        <form method="get" class="register">
            <button name="signin" class="login_btn">Logg inn</button>
        </form>
        <script>
            document.querySelector("input[name='verify_password']").addEventListener("input", function() {
                if (this.value != document.querySelector("input[name='password']").value) {
                    this.setCustomValidity("Passordene er ikke like");
                } else {
                    this.setCustomValidity("");
                }
            });
        </script>
        <?php
    } else
    if (isset($_GET['s']) && !empty($_GET['s']) && strlen($_GET['s']) > 1) {
        $search = htmlspecialchars($_GET['s'], ENT_QUOTES, 'UTF-8');
        include_once "assets/search.php";

        $drivers = $conn->query("SELECT * FROM `drivers` WHERE UPPER(`username`) LIKE UPPER('%$search%')");
        if ($drivers->num_rows > 0) {?>
        <div class="drivers">
            <h1>Sjåfører</h1>
            <?php
            while ($driver = $drivers->fetch_assoc()) {
                $driverId = $driver['id'];
                $driverName = $driver['username'];
                $driverCar = $driver['default_car'];
                $driverRealName = $driver['full_name'];
                $driverPhone = $driver['phone'];
                $driverAvatar = $driver['avatar'];
                $driverDoors = $driver['doors'];

                if ($driverAvatar == "") {
                    $driverAvatar = "assets/images/car.png";
                } else {
                    $driverAvatar = "uploads/avatars/".$driverId."/".$driverAvatar;
                }

                if (!empty($driverCar)) {
                    $driverCarData = $conn->query("SELECT * FROM `cars` WHERE `license_plate` = '$driverCar'");
                    $driverCarData = $driverCarData->fetch_assoc();
                    $driverCarStolen = $driverCarData['stolen'];
                } else {
                    $driverCarStolen = 0;
                }
                ?>
                <div class="profile">
                    <h2><?=$driverName?></h2>
                    <div class="details">
                        <img src="<?=$driverAvatar?>">
                        <?php if ($driverCarStolen == 1) {?>
                        <div class="contact">
                            <?php if (!empty($driverRealName)) {?>
                            <p>Navn:<br>
                            <?=$driverRealName?></p>
                            <?php }?>
                            <?php if (!empty($driverPhone)) {?>
                            <p>Telefon:<br>
                            <b><?=$driverPhone?></b></p>
                            <?php }?>
                        </div>
                        <?php }?>
                    </div>
                    <?php if (!empty($driverCar)) {
                        $driverCarData = $conn->query("SELECT * FROM `cars` WHERE `license_plate` = '$driverCar'");
                        $driverCarData = $driverCarData->fetch_assoc();
                        $driverCarOwner = $driverCarData['driver'];
                        $driverCarPhoto = $driverCarData['photo'];

                        if ($driverCarPhoto == "") {
                            $driverCarPhoto = "assets/images/car.png";
                        } else {
                            $driverCarPhoto = "uploads/vehicles/".$driverCar."/".$driverCarPhoto;
                        }
                        if ($driverCarOwner != $id) {
                            if ($driverDoors == "open") {?>
                    <form method="post">
                        <input type="hidden" name="plate" value="<?=$driverCar?>">
                        <button type="submit" name="join_driver">Sitt på</button>
                    </form>
                    <?php }}}?>
                    <?php
                    $driversCars = $conn->query("SELECT * FROM `cars` WHERE `driver` = '$driverId'");
                    if ($driversCars->num_rows > 0) {
                    ?>
                    <div class="driver_cars" id="driver_cars_<?=$driverId?>">
                        <h3 onclick="expandCars('driver_cars_<?=$driverId?>')"><i class="fa-solid fa-angles-down"></i> Se biler <i class="fa-solid fa-angles-down"></i></h3>
                        <ul>
                            <?php while ($car = $driversCars->fetch_assoc()) {?>
                            <li onclick="window.location.href='?s=<?=$car['license_plate']?>'">
                                <b><?=$car['license_plate']?></b>
                                <img src="<?=$driverCarPhoto?>">
                            </li>
                            <?php }?>
                        </ul>
                    </div>
                </div>
                <?php }
            }?>
            </div>
            <script>
                function expandCars(id) {
                    var element = document.getElementById(id);
                    if (element.style.height === "200px") {
                        element.style.height = "50px";
                    } else {
                        element.style.height = "200px";
                    }
                }
            </script>
        <?php }
        $cars = $conn->query("SELECT * FROM `cars` WHERE UPPER(`license_plate`) LIKE UPPER('%$search%')");
        if ($cars->num_rows > 0 ) {?>
        <div class="cars">
            <h1>Biler</h1>
            <?php
            while ($carData = $cars->fetch_assoc()) {
                $carPlate = $carData['license_plate'];
                $carDriver = $carData['driver'];
                $carStolen = $carData['stolen'];
                $carPhoto = $carData['photo'];

                if ($carPhoto == "") {
                    $carPhoto = "assets/images/car.png";
                } else {
                    $carPhoto = "uploads/vehicles/".$carPlate."/".$carPhoto;
                }

                $carOwner = $conn->query("SELECT * FROM `drivers` WHERE `id` = '$carDriver'");
                $COData = $carOwner->fetch_assoc();
                $carOwner = $COData['username'];
                $COPhone = $COData['phone'];

                if ($carStolen == 1) {
                    $stolen = " stolen";
                } else {
                    $stolen = "";
                }
                ?>
                <div class="vehicle<?=$stolen?>">
                    <div class="split">
                        <div class="vehicle_photo">
                            <img src="<?=$carPhoto?>" id="previewPhoto_<?=$carPlate?>" onclick="showImg(this)">
                            <?php if ($carOwner == $username) {?>
                            <br><br>
                            <form method="post" enctype="multipart/form-data">
                                <input type="hidden" name="plate" value="<?=$carPlate?>" hidden>
                                <input type="file" name="vehicle_photo" id="vehicle_photo" onchange="previewPhoto()" hidden>
                                <button type="button" onclick="document.getElementById('vehicle_photo').click()"><i class="fa-solid fa-camera"></i></button>
                                <button type="submit" name="updatePhoto" id="updatePhoto_<?=$carPlate?>" hidden><i class="fa-solid fa-check"></i></button>
                            </form>
                            <script>
                                function showImg(x) {
                                    var newTab = window.open(x.src, '_blank');
                                    newTab.focus();
                                }
                                function previewPhoto() {
                                    var file = document.getElementById("vehicle_photo").files[0];
                                    var reader = new FileReader();
                                    reader.onloadend = function() {
                                        document.getElementById("previewPhoto_<?=$carPlate?>").src = reader.result;
                                    }
                                    if (file) {
                                        reader.readAsDataURL(file);
                                        document.getElementById("updatePhoto_<?=$carPlate?>").removeAttribute("hidden");
                                    }
                                }
                            </script>
                            <?php }?>
                        </div>
                        <div class="vehicle_info">
                            <h1><?=$carPlate?></h1>
                            <p>Eier:<br>
                            <b onclick="window.location.href='car?s=<?=$carOwner?>'"><?=$carOwner?></b>
                            </p>
                        </div>
                    </div>
                    <?php if ($carOwner == $username) {?>
                    <div class="btns">
                        <?php if ($carStolen == 1) {?>
                        <button class="found" onclick="window.location.href='car?found=<?=$carPlate?>'">Meld funnet</button>
                        <?php } else {?>
                        <button class="stolen" onclick="window.location.href='car?stolen=<?=$carPlate?>'">Meld stjålet</button>
                        <?php }?>
                        <button class="remove" onclick="removeCar('<?=$carPlate?>')"><i class="fa-solid fa-xmark"></i></button>
                    </div>
                    <?php }?>
                </div>
            <?php }?>
            <script>
                function removeCar(plate) {
                    if (confirm("Er du sikker på at du vil fjerne bilen?")) {
                        window.location.href = "car?removeCar="+plate;
                    }
                }
            </script>
        <?php }
    } else {
        if (isset($_SESSION['driver'])) {
            $cars = $conn->query("SELECT * FROM `cars` WHERE 'stolen' = 1");
            if ($cars->num_rows > 0 ) {?>
            <div class="car-carousel">
            <?php
            while ($carData = $cars->fetch_assoc()) {
                $carPlate = $carData['license_plate'];
                $carDriver = $carData['driver'];
                ?>
                <div class="vehicle stolen">
                    <h1><?=$carPlate?></h1>
                </div>
            <?php }?>
            </div>
            <script>
                $('.car-carousel').slick({
                    infinite: true,
                    slidesToShow: 3,
                    slidesToScroll: 3
                });
            </script>
        <?php }?>
    <?php include_once "assets/search.php";?>
    <?php } else {?>
    <p>Er du passasjer eller sjåfør av egen bil?</p>
    <?php include_once "assets/search.php";?>
    <?php }
    }?>
</div>