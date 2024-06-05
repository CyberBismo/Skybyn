<?php include_once "assets/header.php";
include_once "assets/navigation.php";

if (isset($_POST['signin'])) {
    header("Location: ./car?signin");
}
if (isset($_POST['signup'])) {
    header("Location: ./car?signup");
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
            <input type="text" name="username" placeholder="Brukernavn" autofocus required autocomplete="off">
            <input type="password" name="password" placeholder="Passord" required>
            <input type="password" name="verify_password" placeholder="Bekreft passord" required>
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
    if (isset($_GET['s']) && !empty($_GET['s'])) {
        $search = htmlspecialchars($_GET['s'], ENT_QUOTES, 'UTF-8');
        include_once "assets/search.php";

        $drivers = $conn->query("SELECT * FROM `drivers` WHERE UPPER(`username`) LIKE UPPER('%$search%')");
        if ($drivers->num_rows > 0) {?>
            <div class="drivers">
                <h1>Sjåfører</h1>
            <?php
            while ($driver = $cars->fetch_assoc()) {
                $driverId = $driver['id'];
                $driverName = $driver['username'];
                $driverCar = $driver['default_car'];?>
                <div class="profile">
                    <h2><?=$driverName?></h2>
                    <?php
                    $driversCars = $conn->query("SELECT * FROM `cars` WHERE `driver` = '$driverId'");
                    if ($driversCars->num_rows > 0) {
                    ?>
                    <div class="driver_cars">
                        <h3>Biler</h3>
                        <ul>
                            <?php while ($car = $driversCars->fetch_assoc()) {?>
                            <li><?=$car['license_plate']?></li>
                            <?php }?>
                        </ul>
                    </div>
                    <?php if (!empty($driverCar)) {?>
                    <form method="post">
                        <input type="hidden" name="driver" value="<?=$ddriverCar?>">
                        <buttom name="join_meet">Bli passasjer</buttom>
                    </form>
                    <?php }?>
                </div>
            <?php }}?>
            </div>
        <?php }
        $cars = $conn->query("SELECT * FROM `cars` WHERE UPPER(`license_plate`) LIKE UPPER('%$search%')");
        if ($cars->num_rows > 0 ) {?>
            <h1>Biler</h1>
            <div class="cars">
            <?php
            while ($carData = $cars->fetch_assoc()) {
                $carPlate = $carData['license_plate'];
                $carDriver = $carData['driver'];
                $carStolen = $carData['stolen'];

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
                    <h1><?=$carPlate?></h1>
                    <p>
                        Eier: <b onclick="window.location.href='./car?s=<?=$carOwner?>'"><?=$carOwner?></b><br>
                        <?php if ($COPhone != "") {?>
                        Telefon: <b><a href="tel:<?=$COPhone?>"><?=$COPhone?></a></b>
                        <?php }?>
                    </p>
                    <?php if ($carOwner == $username) {?>
                    <div class="btns">
                        <?php if ($carStolen == 1) {?>
                        <button class="found" onclick="window.location.href='./car?found=<?=$carPlate?>'">Meld funnet</button>
                        <?php } else {?>
                        <button class="stolen" onclick="window.location.href='./car?stolen=<?=$carPlate?>'">Meld stjålet</button>
                        <?php }?>
                        <button class="remove" onclick="removeCar('<?=$carPlate?>')"><i class="fa-solid fa-xmark"></i></button>
                    </div>
                    <?php }?>
                </div>
            <?php }?>
            <script>
                function removeCar(plate) {
                    if (confirm("Er du sikker på at du vil fjerne bilen?")) {
                        window.location.href = "./car?removeCar="+plate;
                    }
                }
            </script>
            <?php
        }
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
        <div class="car_btns">
            <button onclick="window.location.href='./car?signin'" class="login_btn">Logg inn/Bli sjåfør</button>
        </div>
    <?php }}?>
</div>