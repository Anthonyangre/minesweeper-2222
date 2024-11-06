<?php
session_start();
require_once '../dbhs.php';
if (!isset($_SESSION['grid'])) {
    resetGame();
}

function resetGame() {
    $rows = 10;
    $cols = 10;
    $mines = 10;

    $_SESSION['grid'] = generateGrid($rows, $cols, $mines);
    $_SESSION['revealed'] = array_fill(0, $rows, array_fill(0, $cols, false));
    $_SESSION['flags'] = array_fill(0, $rows, array_fill(0, $cols, false));
    $_SESSION['game_state'] = 'ongoing'; // 'ongoing', 'won', 'lost'
}

function generateGrid($rows, $cols, $mines) {
    $grid = array_fill(0, $rows, array_fill(0, $cols, 0));

    // Place mines
    $minesPlaced = 0;
    while ($minesPlaced < $mines) {
        $r = rand(0, $rows - 1);
        $c = rand(0, $cols - 1);
        if ($grid[$r][$c] == 0) {
            $grid[$r][$c] = 'M'; // Mine
            $minesPlaced++;

            // Update surrounding cells
            for ($i = max(0, $r - 1); $i <= min($r + 1, $rows - 1); $i++) {
                for ($j = max(0, $c - 1); $j <= min($c + 1, $cols - 1); $j++) {
                    if ($grid[$i][$j] != 'M') {
                        $grid[$i][$j]++;
                    }
                }
            }
        }
    }
    return $grid;
}

if (isset($_GET['reset'])) {
    resetGame();
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minesweeper</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="../style.css">
</head>
<body>
<header> 
        <div class="menu-container" onclick="toggleMenu(this)">
            <div class="hamburger-menu">
                <div class="bar1"></div>
                <div class="bar2"></div>
                <div class="bar3"></div>
            </div>

            <!-- Rubrik som glider in från vänster när knappen klickas -->
            <div class="menu-title">Navigation</div>

            <!-- Dropdown-menyn som visas vid klick -->
            <div class="dropdown-menu">
            <a href="#regler" class="dropdown-item">Regler</a>
            <a href="#info" class="dropdown-item">Info</a>
            <a href= "leaderboard.php"class="dropdown-item">Leaderboard </a>
               

            </div>
        </div>

        <h3 class="rainbow-text">Välkommen till Minesweeper</h3> <!-- Välkomsttext med regnbågsfärg -->

<!-- Länkar för inloggning och registrering -->
<div class="header-links">
    <a href="../profil.php"> <div class="konto">Konto</div></a> 

   
    </div>

        
    </header>

    <div id="status">Game in progress...</div>
    <div id="game-board"></div>
    <button id="reset-button">Reset</button>
    
    <script src="script.js"></script>
    <script src="../java.js"></script>
    <script>
        const grid = <?= json_encode($_SESSION['grid']) ?>;
        const revealed = <?= json_encode($_SESSION['revealed']) ?>;
        const flags = <?= json_encode($_SESSION['flags']) ?>;
        const gameState = '<?= $_SESSION['game_state'] ?>';
    </script>
</body>
</html>
