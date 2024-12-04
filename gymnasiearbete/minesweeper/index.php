<?php

session_start();
require_once '../dbhs.php';
if (!isset($_SESSION['grid'])) {
    resetGame();
}

function resetGame() {
    $rows = 10;
    $cols = 10;
    $mines = 20;

    $_SESSION['grid'] = generateGrid($rows, $cols, $mines);
    $_SESSION['revealed'] = array_fill(0, $rows, array_fill(0, $cols, false));
    $_SESSION['flags'] = array_fill(0, $rows, array_fill(0, $cols, false));
    $_SESSION['game_state'] = 'ongoing'; // 'ongoing', 'won', 'lost'
}

function generateGrid($rows, $cols, $mines) {
    // Ensure the number of mines is less than or equal to the total cells
    $totalCells = $rows * $cols;
    if ($mines > $totalCells) {
        $mines = $totalCells; // Cap the number of mines to the number of available cells
    }

    // Create a flat list of all cell positions
    $allCells = [];
    for ($r = 0; $r < $rows; $r++) {
        for ($c = 0; $c < $cols; $c++) {
            $allCells[] = [$r, $c];
        }
    }

    // Shuffle the cell positions and pick the first $mines as mine locations
    shuffle($allCells);
    $minePositions = array_slice($allCells, 0, $mines);

    // Initialize grid with zeros
    $grid = array_fill(0, $rows, array_fill(0, $cols, 0));

    // Place mines and update surrounding counts
    foreach ($minePositions as [$r, $c]) {
        $grid[$r][$c] = 'M';

        // Update neighboring cells
        for ($i = max(0, $r - 1); $i <= min($rows - 1, $r + 1); $i++) {
            for ($j = max(0, $c - 1); $j <= min($cols - 1, $c + 1); $j++) {
                if ($grid[$i][$j] !== 'M') {
                    $grid[$i][$j]++;
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
$username = $_SESSION['username'];
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
            <a href="../assets/logout.php" class="dropdown-item" onclick="return confirm('Är du säker på att du vill logga ut och gå till förtsa sidan?');">Hem</a> 
            <a href= "leaderboard.php"class="dropdown-item">Leaderboard </a>
               

            </div>
        </div>

        <h3 class="rainbow-text">Minesweeper</h3> <!-- Välkomsttext med regnbågsfärg -->
        <div class="konto" onclick="togglekonto(this)">
    <?php echo htmlspecialchars($username); ?>
    <div class="konto-dropdown">
        <ul>
            <li class="konto-item"><a href="../assets/logout.php"onclick="return confirm('Är du säker på att du vill logga ut och gå till förtsa sidan?');" >Logga ut</a></li>
            <li class="konto-item"><a href="profil.php">Profil</a></li>
        </ul>
    </div>
</div>


        
    </header>
    <?php echo $_SESSION['points'];?>
<div class="background">
<div id="status">Game in progress...</div>
    <div id="game-board"></div>
    <button id="reset-button">Reset</button>
</div>

    
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
