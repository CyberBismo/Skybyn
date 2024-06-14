<?php include_once "assets/header.php";
include_once "assets/navigation.php";

if (isset($_POST['feedback'])) {
    $message = htmlspecialchars($_POST['message'], ENT_QUOTES, 'UTF-8');
    $created = time();
    $sql = "INSERT INTO feedback (`message`, `created`) VALUES ('$message', '$created')";
    $result = $conn->query($sql);
    if ($result) {
        echo "<script>alert('Takk for tilbakemeldingen!');</script>";
    } else {
        echo "<script>alert('Noe gikk galt!');</script>";
    }
}

?>
<style>
    .feedback {
        margin: 0 auto;
        width: 100%;
        padding: 10px;
        color: white;
        box-sizing: border-box;
    }

    .feedback h2 {
        text-align: center;
    }

    .feedback form {
        margin: 0 auto;
        width: 90%;
    }

    .feedback textarea {
        width: 100%;
        margin: 0 auto;
        margin-bottom: 10px;
        padding: 10px;
        border-radius: 10px;
        box-sizing: border-box;
        outline: none;
    }
    <?php if (isset($_SESSION['driver']) && $rank == "admin") { ?>
    
    table {
        margin: 0 auto;
        width: 90%;
    }
    table th {
        padding: 10px;
        text-align: center;
        box-sizing: border-box;
    }
    table td {
        padding: 10px;
        text-align: left;
        box-sizing: border-box;
    }
    table td:nth-child(1) {
        width: 100px;
    }
    table td:nth-child(2) {
        width: auto;
    }
    #feedbackBtn i {
        display: none;
    }
    <?php } ?>
</style>
<div class="feedback">
    <?php if (isset($_SESSION['driver']) && $rank == "admin") { ?>
    <h2>Tilbakemeldinger</h2>
    <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col">Dato</th>
                <th scope="col">Melding</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT * FROM feedback ORDER BY created DESC";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $id = $row['id'];
                    $message = $row['message'];
                    $created = date("d.m.Y", $row['created']);
                    ?>
                    <tr>
                        <td><?=$created?></td>
                        <td><?=$message?></td>
                    </tr>
                    <?php
                }
            } else {
                ?>
                <tr>
                    <td><?=date("d.m.Y")?></td>
                    <td>Ingen tilbakemeldinger</td>
                </tr>
                <?php
            }
            ?>
        </tbody>
    </table>
    <?php } else { ?>
    <form method="post">
        <h2>Tilbakemelding</h2>
        <p>Vi setter pris på tilbakemeldinger fra deg.<br>
        <br>
        Skriv gjerne en tilbakemelding om hva du synes om tjenesten, eller noe du ønsker skal forbedres, endres eller legges til.<br>
        <br>
        <i>På forhånd, takk!</i></p>
        <textarea class="form-control" name="message" rows="10" placeholder="Skriv her.."></textarea>
        <button type="submit" class="btn btn-primary" id="feedback_btn" hidden>Submit</button>
    </form>
    <?php } ?>
</div>