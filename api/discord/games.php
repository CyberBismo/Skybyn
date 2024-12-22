<?php include '../../assets/conn.php';

if (isset($_GET['update'])) {
    $games = $_GET['games'];
    $conn->query("DELETE FROM `discord_games` WHERE `popular` = '1'");
    foreach ($games as $game) {
        if ($conn->query("SELECT * FROM `discord_games` WHERE `title` = '$game'")->num_rows > 0) {
            continue;
        }
        $game = $conn->real_escape_string($game);
        $conn->query("INSERT INTO `discord_games` (`title`,`popular`) VALUES ('$game','1')");
    }
    exit();
}

if (isset($_GET['popular'])) {
    $getGames = $conn->query("SELECT * FROM `discord_games` WHERE `popular` = '1' LIMIT 10");
    $games = array();
    while($row = $getGames->fetch_assoc()){
        $games[] = $row['title'];
    }

    header('Content-Type: application/json');
    echo json_encode($games, JSON_PRETTY_PRINT);
    return;
}

if (isset($_GET['custom'])) {
    $getGames = $conn->query("SELECT * FROM `discord_games` WHERE `popular` = '0' LIMIT 10");
    $games = array();
    while($row = $getGames->fetch_assoc()){
        $games[] = $row['title'];
    }

    header('Content-Type: application/json');
    echo json_encode($games, JSON_PRETTY_PRINT);
    return;
}

?>