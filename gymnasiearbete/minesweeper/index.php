<?php
// Startar session för att hålla koll på användaren
session_start();
require_once '../dbhs.php'; // Laddar databasanslutning
require_once '../assets/functions.php'; // Laddar funktioner
if (!isset($_SESSION['grid'])) { // Om spelplan inte finns
    resetGame(); // Återställer spelet
}
if (!isset($_SESSION['userid'])) { // Kollar om användaren är inloggad
    echo "du är inte välkommen";
    header('Location: ../index.php'); // Skickar till startsidan om ej inloggad
}

// Funktion för att återställa spelet
function resetGame() {
    $rows = 10; // Antal rader
    $cols = 10; // Antal kolumner
    $mines = 20; // Antal minor
    $_SESSION['currentpoints'] = 0; // Nollställer poäng
    $_SESSION['grid'] = generateGrid($rows, $cols, $mines); // Skapar ny spelplan
    $_SESSION['revealed'] = array_fill(0, $rows, array_fill(0, $cols, false)); // Håller koll på visade rutor
    $_SESSION['flags'] = array_fill(0, $rows, array_fill(0, $cols, false)); // Håller koll på flaggor
    $_SESSION['game_state'] = 'ongoing'; // Sätter spelet som igång
}

// Funktion för att skapa spelplanen
function generateGrid($rows, $cols, $mines) {
    $totalCells = $rows * $cols; // Räknar totala rutor
    if ($mines > $totalCells) { // Om för många minor
        $mines = $totalCells; // Begränsar minor
    }
    $allCells = []; // Lista med alla rutor
    for ($r = 0; $r < $rows; $r++) {
        for ($c = 0; $c < $cols; $c++) {
            $allCells[] = [$r, $c]; // Lägger till varje ruta
        }
    }
    shuffle($allCells); // Blandar rutorna
    $minePositions = array_slice($allCells, 0, $mines); // Väljer minor
    $grid = array_fill(0, $rows, array_fill(0, $cols, 0)); // Skapar tom spelplan
    foreach ($minePositions as [$r, $c]) { // Placerar minor
        $grid[$r][$c] = 'M'; // Markerar mina
        for ($i = max(0, $r - 1); $i <= min($rows - 1, $r + 1); $i++) { // Uppdaterar runt minan
            for ($j = max(0, $c - 1); $j <= min($cols - 1, $c + 1); $j++) {
                if ($grid[$i][$j] !== 'M') {
                    $grid[$i][$j]++; // Ökar siffra runt minan
                }
            }
        }
    }
    return $grid; // Returnerar spelplanen
}

// Kollar om användaren vill återställa spelet via URL
if (isset($_GET['reset'])) {
    resetGame(); // Återställer spelet
    header("Location: index.php"); // Skickar tillbaka till sidan
    exit(); // Avslutar skriptet
}
$username = $_SESSION['userid']; // Hämtar användarnamn
$stats = getUserPoints(); // Hämtar användarstatistik
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"> <!-- Sätter teckenkodning -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Gör sidan responsiv -->
    <title>Minesweeper</title> <!-- Sidans titel -->
    <link rel="stylesheet" href="style.css"> <!-- Laddar lokal CSS -->
    <link rel="stylesheet" href="../style.css"> <!-- Laddar global CSS -->
</head>
<body>
<header> 
    <!-- Hamburgermeny med navigering -->
    <div class="menu-container" onclick="toggleMenu(this)">
        <div class="hamburger-menu">
            <div class="bar1"></div>
            <div class="bar2"></div>
            <div class="bar3"></div>
        </div>
        <div class="menu-title">Navigation</div> <!-- Menytitel -->
        <div class="dropdown-menu"> <!-- Dropdown-länkar -->
            <a href="pre_game_choice.php" class="dropdown-item" onclick="return confirm('Är du säker att du vill gå till spelmenyn?');">Spelmeny</a>
            <a href="leaderboard.php" class="dropdown-item">Topplista</a>
            <a href="../assets/logout.php#regler" onclick="return confirm('Är du säker på att du vill logga ut och gå till första sidan?');" class="dropdown-item">Regler</a>
            <a href="../assets/logout.php#info" onclick="return confirm('Är du säker på att du vill logga ut och gå till första sidan?');" class="dropdown-item">Info</a>
        </div>
    </div>

    <h3 class="rainbow-text">Minesweeper</h3> <!-- Sidtitel med regnbågseffekt -->
    <!-- Kontosektion med användarnamn och profilbild -->
    <div class="konto" onclick="togglekonto(this)">
        <?php
        $profilePicturePath = 'uploads/' . $_SESSION["userid"] . '_picture.jpg'; // Sökväg till profilbild
        if (file_exists($profilePicturePath)) { // Kollar om bilden finns
            echo "<img class='bild' src='" . $profilePicturePath . "' alt='Profile Picture'>";
        }
        ?>
        <?php echo htmlspecialchars($username) . "<p id='arrow'>🢓</p>"; ?> <!-- Visar användarnamn och pil -->
        <div class="konto-dropdown"> <!-- Dropdown för konto -->
            <ul>
                <li class="konto-item"><a href="../assets/logout.php" onclick="return confirm('Är du säker på att du vill logga ut och gå till första sidan?');">Logga ut</a></li>
                <li class="konto-item"><a href="profil.php">Profil</a></li>
            </ul>
        </div>
    </div>
</header>
<div class="back">
    <div class="background">
        <div id="status">Spel pågår...</div> <!-- Visar spelstatus -->
        <div id="game-board"></div> <!-- Plats för spelplanen -->
        <button id="reset-button">Återställ</button> <!-- Knapp för att återställa spelet -->
    </div>

    <script src="script.js"></script> <!-- Laddar spel-logik -->
    <script src="../java.js"></script> <!-- Laddar meny-logik -->
    <!-- Skickar PHP-data till JavaScript -->
    <script>
        const grid = <?= json_encode($_SESSION['grid']) ?>; // Spelplan
        const revealed = <?= json_encode($_SESSION['revealed']) ?>; // Visade rutor
        const flags = <?= json_encode($_SESSION['flags']) ?>; // Flaggor
        const gameState = '<?= $_SESSION['game_state'] ?>'; // Spelstatus
    </script>

    <!-- Tabell med användarstatistik -->
    <table id="stats">
        <tr>
            <th>Poäng</th>
            <th>Vinster</th>
            <th>Förluster</th>
        </tr>
        <tr>
            <td><?php echo htmlspecialchars($stats['points'] * 100); ?></td> <!-- Visar poäng -->
            <td><?php echo htmlspecialchars($stats['wins']); ?></td> <!-- Visar vinster -->
            <td><?php echo htmlspecialchars($stats['lose']); ?></td> <!-- Visar förluster -->
        </tr>
    </table>
</div>
</body>
</html>