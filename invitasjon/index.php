<?php
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'elitesys_bismoa');
define('DB_PASSWORD', 'fEIvzleT-tYl');
define('DB_NAME', 'elitesys_bismo');

$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

$family = [
    "Inger Tangen",
    "Tor Tangen",
    "Torill Holter",
    "Ole Karlsen",
    "Lillian Holter",
    "Emil Kise",
    "Åse Holter",
    "Camilla Aarhus",
    "Gro Holter",
    "Ine Nagel",
    "Sissel Johansen",
    "Kjell Austad",
    "Anita Helene Olsen",
    "Irene Wærnes Agnor"
];
$friends = [
    "Magnus Johansen",
    "Siri Marie Madsen",
    "Jeanette Lippert",
    "Christian Bergman",
    "Siw-Hege Blåhella",
    "Alex Hagby",
    "Henrik Lervoll",
    "Jon Pettersen",
    "Christer Halvorsen",
    "Michael Ræstadholm",
    "Laila Kristine",
    "Dennis Lyngholm",
    "Jeanette Nilsen",
    "Fabian Strøm",
    "Sigrid Clinckaert",
    "Sofie Miettinen",
    "Andrea Jonskau",
    "Marius Lervik",
    "Marius Bruheim"
];

$address = "Måltrostveien 19, 3482 Tofte";
$address2 = "Meiseveien 11, 3482 Tofte";
$mapLink = "https://maps.google.com/?q=" . urlencode($address);
$mapLink2 = "https://maps.google.com/?q=" . urlencode($address2);
$mapLinkHtml = '<a href="' . $mapLink . '" target="_blank">' . $address . '</a>';
$map2LinkHtml = '<a href="' . $mapLink2 . '" target="_blank">' . $address2 . '</a>';

session_start();
$_SESSION['steg'] = 0;

if (isset($_POST['fortsett'])) {
    $_SESSION['steg'] = 1;
}

if (isset($_POST['familie'])) {
    $_SESSION['steg'] = 2;
    $_SESSION['bekreftelse'] = 'familie';
}
if (isset($_POST['venner'])) {
    $_SESSION['steg'] = 2;
    $_SESSION['bekreftelse'] = 'venner';
}

if (isset($_POST['tilbake'])) {
    if ($_SESSION['steg'] == 2) {
        $_SESSION['steg'] = 1;
    } else {
        $_SESSION['bekreftelse'] = null;
    }
}

if (isset($_POST['familie_valg'])) {
    $_SESSION['steg'] = 3;
    $familie = [];
    foreach ($_POST as $key => $value) {
        if (strpos($key, 'navn') !== false) {
            $familie[] = $value;
        }
    }
    $_SESSION['valg'] = $familie;
}
if (isset($_POST['venner_valg'])) {
    $_SESSION['steg'] = 3;
    $venner = [];
    foreach ($_POST as $key => $value) {
        if (strpos($key, 'navn') !== false) {
            $venner[] = $value;
        }
    }
    $_SESSION['valg'] = $venner;
}

if (isset($_POST['bekreft'])) {
    $barn = isset($_POST['barn']) ? 1 : 0;
    $kommer_ikke = isset($_POST['kommer_ikke']) ? 1 : 0;

    if (isset($_POST['dag'])) {
        $dag = 1;
    } else {
        $dag = 0;
    }

    if (isset($_POST['kveld'])) {
        $kveld = 1;
    } else {
        $kveld = 0;
    }
    
    $valg = $_SESSION['valg'];

    foreach ($valg as $navn) {
        $checkName = "SELECT * FROM `invitasjoner` WHERE `navn` = '$navn'";
        $result = mysqli_query($conn, $checkName);
        if (mysqli_num_rows($result) == 0) {
            $sql = "INSERT INTO `invitasjoner` (`navn`, `dag`, `kveld`, `har med barn`, `kommer ikke`) VALUES ('$navn', $dag, $kveld, $barn, $kommer_ikke)";
            mysqli_query($conn, $sql);
        }
    }
    
    if ($kommer_ikke == 0) {
        $_SESSION['steg'] = 4;
        $_SESSION['kommer'] = 1;
        if ($kveld == 1) {
            $_SESSION['kveldstid'] = 1;
        } else {
            $_SESSION['kveldstid'] = 0;
        }
    } else {
        $_SESSION['steg'] = 5;
        $_SESSION['kommer'] = 0;
        $_SESSION['kveldstid'] = 0;
    }
}

if (isset($_POST['send_allergier'])) {
    $allergier = $_POST['allergier'];

    if (!empty($allergier)) {
        $allergier = mysqli_real_escape_string($conn, $allergier);
        $sql = "INSERT INTO `allergier` (`tekst`) VALUES ('$allergier')";
        mysqli_query($conn, $sql);
    }
    $_SESSION['steg'] = 5;
}

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Inviterer deg til 30/31-års feiring!</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="">
        <style>
            body {
                font-family: Arial, sans-serif;
                max-width: 600px;
                margin: 0 auto;
                padding: 20px;
                text-align: center;
                background: url("bg.jpg") no-repeat center center fixed;
            }

            .container {
                background-color: rgba(255, 255, 255, 0.8);
                padding: 20px;
                border-radius: 10px;
            }

            h1 {
                color: #333;
            }
            h2 {
                color: #666;
            }
            h3 {
                color: #999;
            }

            p {
                color: #666;
            }

            button {
                background-color: #f44336;
                border: none;
                color: white;
                padding: 15px 32px;
                text-align: center;
                text-decoration: none;
                display: inline-block;
                font-size: 16px;
                margin: 4px 2px;
                cursor: pointer;
            }

            input[type="button"],
            input[type="submit"] {
                background-color: #4CAF50;
                border: none;
                color: white;
                padding: 15px 32px;
                text-align: center;
                text-decoration: none;
                display: inline-block;
                font-size: 16px;
                margin: 4px 2px;
                cursor: pointer;
            }
            input[type="submit"].choice {
                font-size: 36px;
            }
            .choices {
                width: 100%;
                text-align: left;
            }

            ul {
                list-style-type: none;
                padding: 0;
            }

            li {
                padding: 10px;
            }

            li:last-child {
                border-bottom: none;
            }

            textarea {
                width: 100%;
                height: 200px;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <?php if ($_SESSION['steg'] == 5) {?>
            <?php if ($_SESSION['kommer'] == 1) {?>
            <h2>Mine gaveønsker</h2>
            
            <p>
                <ul style="text-align: left !important">
                    <li style="font-size: larger">Penger eller gavekort</li>
                    <ul>
                        <li>Eksempler til Gavekort:</li>
                        <li>- Et kjøpesenter</li>
                        <li>- Floyd butikken</li>
                        <li>- Rafting</li>
                        <li>- Inngang til klatreparker</li>
                    </ul>
                    <ul>
                        <li>Guidet turer:
                            <li>- Galdhøpiggen</li>
                            <li>- Isbre vandring</li>
                        </li>
                    </ul>
                </ul>

                <i>De som ga meg gave på dagen min trenger ikke gi meg gave. Dette gjelder også de som ikke har råd.</i>
            </p>

            <p>
                <b>Kleskode:</b> Finstas
            </p>

            <p>
                Adressen er <?=$mapLinkHtml?>. Men dere må parkere ved <?=$map2LinkHtml?>, og gå opp trappen (følg ballongene). Det er få parkeringer og det kan hende dere må parkere ved barnehagen (Gulspurven barnehage) og gå opp hit.
            </p>

            <p>
                Fint om folk tar med seg campingstoler eller noe å sitte på da vi ikke har flere enn 5-8 sitteplasser.
            </p>

            <?php if ($_SESSION['kveldstid'] == 1) {?>
            <p>
                <b>Informasjon for kveldstid:</b><br>
                Det er ingen overnattingsplasser, så folk må komme seg hjem på egenhånd.
            </p>
            <?php }?>

            <?php } else {?>
            <h1>Takk for at du svarte!</h1>
            <p>Håper at vi ses om ikke så alt for lenge</p>
            <?php }?>

            <button onclick="window.location.href='./'">Lukk</button>

            <?php } else
            if ($_SESSION['steg'] == 4) {?>
            <h1>Har du matallergier eller annet vi må ta hensyn til?</h1>
            <p>Hvis du har matallergier, skriv det her:</p>
            <form method="post">
                <textarea name="allergier" id="allergier" cols="30" rows="10"></textarea>
                <input type="submit" name="send_allergier" value="Send">
            </form>

            <?php } else
            if ($_SESSION['steg'] == 3) {?>
            <h1>Velg tidspunkt og annet</h1>
            <h2>Huk av det som passer for deg/dere.</h2>

            <form method="post">
                <ul>
                    <label for="dag">
                        <li>
                            <input type="checkbox" name="dag" id="dag" onchange="checkControl('dag')"> Dag (12-16)
                        </li>
                    </label>
                    <label for="kveld">
                        <li>
                            <input type="checkbox" name="kveld" id="kveld" onchange="checkControl('kveld')">Kveld (19-02)
                        </li>
                    </label>
                    <label for="barn">
                        <li>
                            <input type="checkbox" name="barn" id="barn" onchange="checkControl('barn')"> Har med barn
                        </li>
                    </label>
                    <label for="kommer_ikke">
                        <li>
                            <input type="checkbox" name="kommer_ikke" id="kommer_ikke" onchange="checkControl('kommer_ikke')"> Kommer ikke
                        </li>
                    </label>
                </ul>
                
                <input type="submit" name="bekreft" value="Bekreft">
            </form>

            <form method="post">
                <input type="submit" name="tilbake" value="Tilbake">
            </form>

            <script>
                function checkControl(id) {
                    var element = document.getElementById(id);
                    if (element.checked) {
                        if (id == 'kommer_ikke') {
                            document.getElementById('dag').checked = false;
                            document.getElementById('kveld').checked = false;
                            document.getElementById('barn').checked = false;
                        } else {
                            document.getElementById('kommer_ikke').checked = false;
                        }
                    }
                }
            </script>

            <?php } else
            if ($_SESSION['steg'] == 2) {
                if ($_SESSION['bekreftelse'] == 'familie') {
                    $names = $family;
            ?>
            <h1>Velg ditt eget og de navnene du kommer med.</h1>
            <h2>Familie er hovedsaklig satt av til dagtid (12-16)</h2>

            <form method="post">
                <ul>
                    <?php foreach ($names as $index => $name) { ?>
                        <label class="choices" for="navn<?php echo $index + 1; ?>">
                            <li>
                                <input type="checkbox" value="<?=$name?>" name="navn<?php echo $index + 1; ?>" id="navn<?php echo $index + 1; ?>" onchange="toggleBold('navn<?php echo $index + 1; ?>');checkName('<?=$name?>')"> <span><?php echo $name; ?></span>
                            </li>
                        </label>
                    <?php } ?>
                </ul>
                <input type="submit" name="familie_valg" value="Fortsett" onclick="return validateForm()">
            </form>
            <?php } else {
                $names = $friends;
                ?>
            <h1>Velg ditt eget og de navnene du kommer med.</h1>
            <h2>Venner er hovedsaklig satt av til kvelstid (19-02)</h2>

            <form method="post">
                <ul>
                <?php foreach ($names as $index => $name) { ?>
                    <label class="choices" for="navn<?php echo $index + 1; ?>">
                        <li>
                            <input type="checkbox" value="<?=$name?>" name="navn<?php echo $index + 1; ?>" id="navn<?php echo $index + 1; ?>" onchange="toggleBold('navn<?php echo $index + 1; ?>');checkName('<?=$name?>')"> <span><?php echo $name; ?></span>
                        </li>
                    </label>
                <?php } ?>
                </ul>
                <input type="submit" name="venner_valg" value="Fortsett" onclick="return validateForm()">
            </form>

            <?php }?>
            
            <form method="post">
                <input type="submit" name="tilbake" value="Tilbake">
            </form>
            
            <script>
                function toggleBold(id) {
                    var element = document.getElementById(id);
                    var span = element.nextElementSibling;
                    if (element.checked) {
                        span.style.fontWeight = 'bold';
                    } else {
                        span.style.fontWeight = 'normal';
                    }
                }

                function validateForm() {
                    var checkboxes = document.querySelectorAll('input[type="checkbox"]');
                    var checked = false;
                    for (var i = 0; i < checkboxes.length; i++) {
                        if (checkboxes[i].checked) {
                        checked = true;
                        break;
                        }
                    }
                    if (!checked) {
                        alert("Please check at least one box.");
                        return false;
                    }
                    return true;
                }

                function checkName(name) {
                    var xhr = new XMLHttpRequest();
                    xhr.open("POST", "checkName.php", true);
                    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                    xhr.onreadystatechange = function() {
                        if (xhr.readyState == 4 && xhr.status == 200) {
                            if (xhr.responseText == "Name already exists") {
                                alert(name + " har allerede svart.");
                                var element = document.querySelector('input[value="' + name + '"]');
                                if (element) {
                                    element.checked = false;
                                    toggleBold(element.id);
                                }
                            }
                        }
                    };
                    xhr.send("navn=" + name);
                }
            </script>

            <?php } else
            if ($_SESSION['steg'] == 1) {?>
            <h1>Er du familie eller venner?</h1>
            
            <form method="post">
                <input type="submit" class="choice" name="familie" value="Familie">
                <input type="submit" class="choice" name="venner" value="Venner">
            </form>

            <?php } else {?>
            <h2>Inviterer deg til min 30/31års feiring</h2>
            <h3>Lørdag 13.juli 2024</h3>

            <p>
                På grunn av helsa har jeg måtte utsette flere bursdager. I og med at jeg har blitt litt bedre så inviterer jeg deg til en feiring av de bursdagene jeg har gått glipp av
            </p>
            
            <p>
                <b>Dagen blir delt opp i 2</b><br>
                <br>
                <b>Dagtid kl 12-16</b><br>
                De som ønsker kan komme til pølser og kake (alkoholfritt)<br>
                Hovedsakelig familie som er blitt invitert på det tidspunktet.<br>
                <br>
                <b>Kveldstid kl 19-02</b><br>
                De som vil være med å drikke alkohol kommer.<br>
                <br>
                Ønsker du å komme på begge tider må dette hukes av på kommende side slik at jeg får planlagt mat og kake.
            </p>
            
            <p>
                Ettersom du har fått invitasjonen ønsker vi at du går igjennom hele og besvarer om du kommer eller ikke.<br>
                Fristen for å svare er 8.juli 2024.
            </p>
            
            <p><b>Kun de som har fått denne meldingen er invitert. Det er IKKE GREIT å ta med andre uten å spørre først.</b></p>

            <form method="post">
                <input type="submit" name="fortsett" value="Fortsett">
            </form>
            <?php }?>
        </div>
    </body>
</html>