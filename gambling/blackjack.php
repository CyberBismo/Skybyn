<?php
session_start();

function createDeck(): array {
    $suits = ['♠', '♥', '♦', '♣'];
    $ranks = ['2', '3', '4', '5', '6', '7', '8', '9', '10', 'J', 'Q', 'K', 'A'];
    $deck = [];

    foreach ($suits as $suit) {
        foreach ($ranks as $rank) {
            $deck[] = ['rank' => $rank, 'suit' => $suit];
        }
    }

    shuffle($deck);
    return $deck;
}

function calculateTotal(array $hand): int {
    $total = 0;
    $aces = 0;

    foreach ($hand as $card) {
        $rank = $card['rank'];
        if (is_numeric($rank)) {
            $total += intval($rank);
        } elseif ($rank === 'A') {
            $total += 11;
            $aces++;
        } else {
            $total += 10;
        }
    }

    while ($total > 21 && $aces > 0) {
        $total -= 10;
        $aces--;
    }

    return $total;
}

function dealCard() {
    return array_pop($_SESSION['deck']);
}

if (!isset($_SESSION['initialized'])) {
    $_SESSION['deck'] = [];
    $_SESSION['player'] = [];
    $_SESSION['dealer'] = [];
    $_SESSION['game_over'] = false;
    $_SESSION['message'] = '';
    $_SESSION['game_started'] = false;
    $_SESSION['initialized'] = true;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['start_game'])) {
        $_SESSION['deck'] = createDeck();
        $_SESSION['player'] = [dealCard(), dealCard()];
        $_SESSION['dealer'] = [dealCard(), dealCard()];
        $_SESSION['game_over'] = false;
        $_SESSION['message'] = '';
        $_SESSION['game_started'] = true;
    } elseif (isset($_POST['hit'])) {
        $_SESSION['player'][] = dealCard();
        if (calculateTotal($_SESSION['player']) > 21) {
            $_SESSION['game_over'] = true;
            $_SESSION['message'] = 'You busted! Dealer wins.';
        }
    } elseif (isset($_POST['stand'])) {
        while (calculateTotal($_SESSION['dealer']) < 17) {
            $_SESSION['dealer'][] = dealCard();
        }

        $playerTotal = calculateTotal($_SESSION['player']);
        $dealerTotal = calculateTotal($_SESSION['dealer']);

        if ($dealerTotal > 21 || $playerTotal > $dealerTotal) {
            $_SESSION['message'] = 'You win!';
        } elseif ($playerTotal < $dealerTotal) {
            $_SESSION['message'] = 'Dealer wins!';
        } else {
            $_SESSION['message'] = "It's a tie!";
        }

        $_SESSION['game_over'] = true;
    } elseif (isset($_POST['new_game'])) {
        session_destroy();
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
}

function renderCards(array $hand, bool $hideFirst = false): string {
    $html = '';
    foreach ($hand as $index => $card) {
        if ($hideFirst && $index === 0) {
            $html .= "<div class='card back'></div>";
        } else {
            $color = ($card['suit'] === '♥' || $card['suit'] === '♦') ? 'red' : 'black';
            $html .= "<div class='card $color'>{$card['rank']}<br>{$card['suit']}</div>";
        }
    }
    return $html;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Blackjack Game</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #1c1c1c;
            color: #fff;
            text-align: center;
            padding: 2rem;
        }
        .table {
            background: #006400;
            border-radius: 12px;
            padding: 2rem;
            max-width: 800px;
            margin: auto;
            box-shadow: 0 0 20px rgba(0,0,0,0.5);
        }
        .hand {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin: 1rem 0;
            flex-wrap: wrap;
        }
        .card {
            background: #fff;
            color: black;
            border-radius: 10px;
            width: 60px;
            height: 80px;
            padding: 10px;
            box-shadow: 2px 2px 8px rgba(0,0,0,0.2);
            font-size: 1.2rem;
            animation: drawCard 0.3s ease-out;
        }
        .back {
            background: repeating-linear-gradient(45deg, #999, #999 10px, #ccc 10px, #ccc 20px);
        }
        .red {
            color: red;
        }
        .black {
            color: black;
        }
        .deck {
            position: absolute;
            top: 20px;
            right: 20px;
            width: 60px;
            height: 80px;
            background: #444;
            border-radius: 10px;
            box-shadow: 2px 2px 8px rgba(0,0,0,0.2);
        }
        @keyframes drawCard {
            from { transform: translate(-50px, -50px); opacity: 0; }
            to { transform: translate(0, 0); opacity: 1; }
        }
        button {
            background: #ffcc00;
            border: none;
            padding: 10px 20px;
            margin: 0 10px;
            font-size: 1rem;
            border-radius: 8px;
            cursor: pointer;
            transition: 0.2s;
        }
        button:hover {
            background: #e6b800;
        }
    </style>
</head>
<body>
<div class="deck"></div>
<div class="table">
    <h1>🃏 Blackjack</h1>

    <?php if (!$_SESSION['game_started']): ?>
        <form method="post">
            <button type="submit" name="start_game">Start Game</button>
        </form>
    <?php else: ?>
        <h2>Dealer's Hand</h2>
        <div class="hand">
            <?= renderCards($_SESSION['dealer'], !$_SESSION['game_over']) ?>
        </div>

        <h2>Your Hand (<?= calculateTotal($_SESSION['player']) ?>)</h2>
        <div class="hand">
            <?= renderCards($_SESSION['player']) ?>
        </div>

        <?php if ($_SESSION['game_over']): ?>
            <h3><?= $_SESSION['message'] ?></h3>
            <form method="post">
                <button type="submit" name="new_game">New Game</button>
            </form>
        <?php else: ?>
            <form method="post">
                <button type="submit" name="hit">Hit</button>
                <button type="submit" name="stand">Stand</button>
            </form>
        <?php endif; ?>
    <?php endif; ?>
</div>
</body>
</html>
