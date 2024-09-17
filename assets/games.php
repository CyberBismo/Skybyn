<?php include 'conn.php';
if (isset($_GET['update'])) {
    if ($_GET['update'] == "popular") {
        $games = [
            [
                "title" => "Minecraft",
                "popular" => 1
            ],
            [
                "title" => "ROBLOX",
                "popular" => 1
            ],
            [
                "title" => "Fortnite",
                "popular" => 1
            ],
            [
                "title" => "The Sims 4",
                "popular" => 1
            ],
            [
                "title" => "Counter-Strike 2",
                "popular" => 1
            ],
            [
                "title" => "League of Legends",
                "popular" => 1
            ],
            [
                "title" => "Valorant",
                "popular" => 1
            ],
            [
                "title" => "Call of Duty",
                "popular" => 1
            ],
            [
                "title" => "Grand Theft Auto V",
                "popular" => 1
            ],
            [
                "title" => "Rocket League",
                "popular" => 1
            ]
        ];
        $json = json_encode($games, JSON_PRETTY_PRINT);
    } else
    if ($_GET['update'] == "custom") {
        $games = [
            [
                "title" => "Arena Blackout Infinite",
                "popular" => 0
            ]
        ];
        $json = json_encode($games, JSON_PRETTY_PRINT);
    } else {
        header("Location: games.php?list");
        exit();
    }

    #$rawg = 'https://api.rawg.io/api/games/';
    #$rawg .= '?key=604552aebd2648f9ad296802dfee8e06';
#
    #$url = $rawg;
#
    #$ch = curl_init();
    #curl_setopt($ch, CURLOPT_URL, $url);
    #curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    #$response = curl_exec($ch);
    #curl_close($ch);
    #$data = json_decode($response, true);
#
    #$appNames = array_column($data['applist']['apps'], 'name');
    #foreach ($appNames as $appName) {
    #    if (!empty($appName) && !preg_match('/test\d$/i', $appName) && !preg_match('/Bonus/i', $appName)) {
    #        $games[] = ["title" => $appName];
    #    }
    #}

    if (file_exists('games.json')) {
        $jsonContent = file_get_contents('games.json');
        $existingGames = json_decode($jsonContent, true);
        $updatedGames = array_merge($existingGames, $games);
    
        // Remove duplicates
        $updatedGames = array_map("unserialize", array_unique(array_map("serialize", $updatedGames)));
    
        $updatedJson = json_encode($updatedGames, JSON_PRETTY_PRINT);
        file_put_contents('games.json', $updatedJson);
    } else {
        file_put_contents('games.json', $json);
    }
    header("Location: games.php?list");
    exit();
}

if (isset($_GET['list'])) {
    $search = $_GET['list'];
    $games = json_decode(file_get_contents('games.json'), true);

    header('Content-Type: application/json');
    echo json_encode($games, JSON_PRETTY_PRINT);
}

if (isset($_GET['add'])) {
    $add = $_GET['add'];
    if (empty($add)) {
    ?>
    <form method="POST">
        <input type="text" id="game" placeholder="Game Title" autofocus onkeyup="checkGameName(this.value)">
        <button type="submit" disabled>Add Game</button><br>
        <select name="add" id="games" style="width:200px;color:black"></select>
    </form>
    <script>
        function checkGameName(game) {
            var addButton = document.querySelector('button[type="submit"]');
            var gamesSection = document.getElementById('games');
            if (game.length > 0) {
                gamesSection.style.display = 'block';
                var xhr = new XMLHttpRequest();
                xhr.open('GET', 'games.php?list=' + game, true);
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        var games = JSON.parse(xhr.responseText);
                        gamesSection.innerHTML = '';

                        for (var gameId in games) {
                            if (games.hasOwnProperty(gameId)) {
                                var gameItem = games[gameId];
                                var option = document.createElement('option');
                                option.value = gameItem.title;
                                option.innerHTML = gameItem.title;
                                gamesSection.appendChild(option);
                            }
                        }

                        addButton.disabled = false;
                    }
                };
                xhr.send();
            } else {
                gamesSection.style.display = 'none';
                addButton.disabled = true;
            }
        }
        var option = document.querySelector('option');
        if (option) {
            option.addEventListener('click', function() {
                document.getElementById('game').value = this.value;
            });
        }
    </script>
    <?php
    }
    if (isset($_POST['add'])) {
        $game = $_POST['add'];
        $conn->query("INSERT INTO `discord_games` (`title`,`popular`) VALUES ('$game','0')");
        header("Location: games?list");
        exit();
    }
}
?>