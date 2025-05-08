<?php
function drawLottoNumbers(int $pool = 34, int $count = 7): array {
    $numbers = range(1, $pool);
    shuffle($numbers);
    return array_slice($numbers, 0, $count);
}

function checkTicket(array $ticket, array $drawn): int {
    return count(array_intersect($ticket, $drawn));
}

function validateTicket(array $ticket): bool {
    return count($ticket) === 7 &&
           count(array_unique($ticket)) === 7 &&
           max($ticket) <= 34 &&
           min($ticket) >= 1;
}

$userTicket = [];
$drawnNumbers = [];
$matched = null;
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userTicket = array_map('intval', $_POST['ticket'] ?? []);
    sort($userTicket);

    if (validateTicket($userTicket)) {
        $drawnNumbers = drawLottoNumbers();
        sort($drawnNumbers);
        $matched = checkTicket($userTicket, $drawnNumbers);

        switch ($matched) {
            case 7:
                $message = "🎉 Jackpot Winner!";
                break;
            case 6:
                $message = "👏 Second prize!";
                break;
            case 5:
                $message = "👍 Third prize!";
                break;
            default:
                $message = "😞 Better luck next time.";
        }
    } else {
        $message = "⚠️ Invalid ticket. Please select 7 unique numbers between 1 and 34.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Lotto Simulator</title>
    <style>
        body {
            font-family: "Segoe UI", sans-serif;
            background: #f5f8fa;
            padding: 2rem;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: auto;
            background: #fff;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 0 20px rgba(0,0,0,0.05);
            animation: fadeIn 0.8s ease-out;
        }
        h1 {
            text-align: center;
            color: #0077cc;
        }
        form {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            justify-content: center;
            margin-top: 1rem;
        }
        input[type="number"] {
            width: 60px;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 8px;
            text-align: center;
            transition: transform 0.2s ease-in-out, border-color 0.2s;
        }
        input[type="number"]:focus {
            border-color: #0077cc;
            transform: scale(1.05);
            outline: none;
        }
        .button-group {
            width: 100%;
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin-top: 1rem;
        }
        button {
            padding: 10px 20px;
            background: #0077cc;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: transform 0.2s ease, background-color 0.2s;
        }
        button:hover {
            background: #005fa3;
            transform: scale(1.05);
        }
        .result {
            margin-top: 1.5rem;
            padding: 1rem;
            border-radius: 10px;
            background: #e9f6ff;
            border: 1px solid #cce6ff;
            animation: fadeInSlide 0.7s ease-out;
        }
        .numbers {
            font-weight: bold;
            color: #0077cc;
            display: inline-block;
        }
        .numbers span {
            display: inline-block;
            background: #d9edff;
            margin: 2px 4px;
            padding: 5px 10px;
            border-radius: 10px;
            animation: bounceIn 0.4s ease-out;
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
        }

        @keyframes fadeInSlide {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes bounceIn {
            0% { transform: scale(0.8); opacity: 0; }
            50% { transform: scale(1.05); opacity: 1; }
            100% { transform: scale(1); }
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Norsk Lotto Simulator</h1>

    <form method="POST" id="lottoForm">
        <?php for ($i = 0; $i < 7; $i++): ?>
            <input type="number" name="ticket[]" min="1" max="34" required value="<?= $userTicket[$i] ?? '' ?>">
        <?php endfor; ?>
        <div class="button-group">
            <button type="button" onclick="autoSelect()">Auto-Select</button>
            <button type="submit">Draw Numbers</button>
        </div>
    </form>

    <?php if ($message): ?>
        <div class="result">
            <p><strong>Result:</strong> <?= htmlspecialchars($message) ?></p>
            <?php if ($matched !== null): ?>
                <p>Your Ticket:
                    <span class="numbers">
                        <?php foreach ($userTicket as $num): ?>
                            <span><?= $num ?></span>
                        <?php endforeach; ?>
                    </span>
                </p>
                <p>Drawn Numbers:
                    <span class="numbers">
                        <?php foreach ($drawnNumbers as $num): ?>
                            <span><?= $num ?></span>
                        <?php endforeach; ?>
                    </span>
                </p>
                <p>You matched <strong><?= $matched ?></strong> number<?= $matched !== 1 ? 's' : '' ?>.</p>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>

<script>
function autoSelect() {
    const inputs = document.querySelectorAll('input[name="ticket[]"]');
    let numbers = [];

    while (numbers.length < 7) {
        const n = Math.floor(Math.random() * 34) + 1;
        if (!numbers.includes(n)) numbers.push(n);
    }

    numbers.sort((a, b) => a - b);

    inputs.forEach((input, i) => {
        input.value = numbers[i];
    });
}
</script>

</body>
</html>
