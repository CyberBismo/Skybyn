<?php
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'elitesys_bismoa');
define('DB_PASSWORD', 'fEIvzleT-tYl');
define('DB_NAME', 'elitesys_bismo');

$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

session_start();

$password = "9c?F&Hj43";

if (isset($_SESSION['access'])) {
    $access = true;
} else {
    $access = false;
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Svar på invitasjoner</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="">
        <style>
            body {
                font-family: Arial, sans-serif;
                margin: 0;
                padding: 0;
            }
            table {
                width: 100%;
                border-collapse: collapse;
            }
            th, td {
                border: 1px solid black;
                padding: 5px;
            }

            th {
                background-color: #f2f2f2;
            }

            tr:nth-child(even) {
                background-color: #f2f2f2;
            }

            tr:hover {
                background-color: #f5f5f5;
            }

            form {
                margin-top: 20px;
            }

            input[type="password"] {
                padding: 5px;
                font-size: 16px;
                box-sizing: border-box;
            }

            input[type="submit"] {
                padding: 5px 10px;
                font-size: 16px;
                background-color: #4CAF50;
                color: white;
                border: none;
                cursor: pointer;
                box-sizing: border-box;
            }

            input[type="submit"]:hover {
                background-color: #45a049;
            }

            input[type="submit"]:active {
                background-color: #3e8e41;
            }

            input[type="submit"]:focus {
                outline: none;
            }

            p {
                margin-top: 20px;
            }

            a {
                text-decoration: none;
                color: #4CAF50;
            }
        </style>
    </head>
    <body>
        <?php
        if ($access) {
            $sql = "SELECT * FROM `invitasjoner`";
            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) > 0) {?>
                <table>
                    <tr>
                        <th>Navn</th>
                        <th>Dag</th>
                        <th>Kveld</th>
                        <th>Har med barn</th>
                        <th>Kommer ikke</th>
                    </tr>
                    <?php
                while ($row = mysqli_fetch_assoc($result)) {
                    $navn = $row['navn'];
                    $dag = $row['dag'];
                    $kveld = $row['kveld'];
                    $barn = $row['har med barn'];
                    $kommer_ikke = $row['kommer ikke'];
                    ?>
                    <tr>
                        <td><?php echo $navn; ?></td>
                        <td><?php echo $dag; ?></td>
                        <td><?php echo $kveld; ?></td>
                        <td><?php echo $barn; ?></td>
                        <td><?php echo $kommer_ikke; ?></td>
                    </tr>
                    <?php
                }
                ?>
                </table>
                <?php
            } else {?>
                <p>Ingen har svart enda</p>
                <?php
            }
            $sql = "SELECT * FROM `allergier`";
            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) > 0) {?>
                <table>
                    <tr>
                        <th>Allergier</th>
                    </tr>
                    <?php
                while ($row = mysqli_fetch_assoc($result)) {
                    $tekst = $row['tekst'];
                    ?>
                    <tr>
                        <td><?php echo $tekst; ?></td>
                    </tr>
                    <?php
                }
                ?>
                </table>
                <?php
            }
        } else {
            if (isset($_POST['password'])) {
                if ($_POST['password'] == $password) {
                    $_SESSION['access'] = true;
                    header("Location: svar");
                } else {?>
                    <form method="post">
                        <input type="password" name="password" placeholder="Passord">
                        <input type="submit" value="Logg inn">
                    </form>
                    <?php
                }
            } else {?>
                <form method="post">
                    <input type="password" name="password" placeholder="Passord">
                    <input type="submit" value="Logg inn">
                </form>
                <?php
            }
        }
        ?>
    </body>
</html>