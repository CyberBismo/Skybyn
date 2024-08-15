<?php

include '../../assets/conn.php';

header('Content-Type: application/json');

if (isset($_GET['update'])) {
    $games = ["Counter-Strike 2",
        "Dota 2",
        "PUBG: BATTLEGROUNDS",
        "Rust",
        "Grand Theft Auto V",
        "Path of Exile",
        "Once Human",
        "ELDEN RING",
        "Apex Legends",
        "Team Fortress 2",
        "Baldur's Gate 3",
        "Call of Duty®",
        "Tom Clancy's Rainbow Six Siege",
        "War Thunder",
        "7 Days to Die",
        "Football Manager 2024",
        "Stardew Valley",
        "Crab Game",
        "The First Descendant",
        "EA SPORTS FC™ 24"];
    $conn->query("TRUNCATE TABLE `games`;");
    foreach ($games as $game) {
        $game = $conn->real_escape_string($game);
        $conn->query("INSERT INTO games (`title`) VALUES ('$game')");
    }
    header("Location: games.php");
} else {
    $getGames = $conn->query("SELECT * FROM games");
    $games = array();
    while($row = $getGames->fetch_assoc()){
        $games[] = $row;
    }
    echo json_encode($games);
    return;
}

?>