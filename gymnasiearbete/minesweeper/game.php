<?php
// Startar session för att hålla koll på användaren
session_start();
require_once '../assets/functions.php'; // Laddar in funktioner
if (!isset($_SESSION['userid'])) { // Kollar om användaren är inloggad
    echo "du är inte välkommen";
    header('Location: ../index.php'); // Skickar till startsidan om ej inloggad
}
// Sätter upp spelet om det inte finns
if (!isset($_SESSION['game_state'])) {
    $_SESSION['game_state'] = 'ongoing'; // Spelet är igång
    $rows = 10; // Antal rader
    $cols = 10; // Antal kolumner
    $mines = 20; // Antal minor
    $_SESSION['grid'] = generateGrid($rows, $cols, $mines); // Skapar spelplan
    $_SESSION['revealed'] = array_fill(0, $rows, array_fill(0, $cols, false)); // Håller koll på visade rutor
    $_SESSION['flags'] = array_fill(0, $rows, array_fill(0, $cols, false)); // Håller koll på flaggor
    $_SESSION['points'] = 0; // Startpoäng
    $stats = getUserPoints(); // Hämtar användarstatistik
    $_SESSION['pre_game_points'] = $stats['points']; // Sparar poäng före spelet
    $_SESSION['wins'] = 0; // Antal vinster
    $_SESSION['lose'] = 0; // Antal förluster
    $_SESSION['currentpoints'] = 0; // Nuvarande poäng
}

// Hanterar POST från frontend
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_SESSION['game_state'] != 'ongoing') { // Om spelet är slut
        $_SESSION['currentpoints'] = 0; // Nollställer poäng
    }
    if ($_SESSION['game_state'] === 'ongoing') { // Om spelet är igång
        $action = $_POST['action']; // Hämtar handling
        $row = (int)$_POST['row']; // Radnummer
        $col = (int)$_POST['col']; // Kolumnnummer

        if ($action === 'reveal') { // Om användaren visar ruta
            if (isFirstClick()) { // Första klicket?
                ensureFirstClickIsZero($row, $col); // Gör första rutan till 0
                revealCluster($row, $col); // Visar kluster
            } else {
                revealCell($row, $col); // Visar vald ruta
            }
        } elseif ($action === 'flag') { // Om användaren flaggar
            $_SESSION['flags'][$row][$col] = !$_SESSION['flags'][$row][$col]; // Växlar flagga
        }

        checkGameState(); // Kollar spelets status
    }

    // Skickar speldata som JSON till frontend
    echo json_encode([
        'grid' => $_SESSION['grid'],
        'revealed' => $_SESSION['revealed'],
        'flags' => $_SESSION['flags'],
        'game_state' => $_SESSION['game_state'],
        'points' => $_SESSION['points']
    ]);
}

// Kollar om det är första klicket
function isFirstClick() {
    foreach ($_SESSION['revealed'] as $row) {
        if (in_array(true, $row)) {
            return false; // Inte första om något är visat
        }
    }
    return true; // Första klicket
}

// Gör så första klicket blir en 0:a
function ensureFirstClickIsZero($row, $col) {
    if ($_SESSION['grid'][$row][$col] != 0) { // Om inte 0
        regenerateGridWithZeroAt($row, $col); // Skapar ny spelplan
    }
}

// Skapar ny spelplan med 0 på vald plats
function regenerateGridWithZeroAt($safeRow, $safeCol) {
    $rows = count($_SESSION['grid']); // Antal rader
    $cols = count($_SESSION['grid'][0]); // Antal kolumner
    $mines = 20; // Antal minor
    do {
        $_SESSION['grid'] = generateGrid($rows, $cols, $mines); // Ny spelplan
    } while ($_SESSION['grid'][$safeRow][$safeCol] != 0); // Tills vald ruta är 0
}

// Skapar spelplan med minor och siffror
function generateGrid($rows, $cols, $mines) {
    $totalCells = $rows * $cols; // Totala rutor
    if ($mines > $totalCells) {
        $mines = $totalCells; // Begränsar minor
    }
    $allCells = []; // Lista med rutor
    for ($r = 0; $r < $rows; $r++) {
        for ($c = 0; $c < $cols; $c++) {
            $allCells[] = [$r, $c]; // Lägger till ruta
        }
    }
    shuffle($allCells); // Blandar rutor
    $minePositions = array_slice($allCells, 0, $mines); // Väljer minor
    $grid = array_fill(0, $rows, array_fill(0, $cols, 0)); // Tom spelplan
    foreach ($minePositions as [$r, $c]) { // Placerar minor
        $grid[$r][$c] = 'M'; // Markerar mina
        for ($i = max(0, $r - 1); $i <= min($rows - 1, $r + 1); $i++) { // Uppdaterar runt mina
            for ($j = max(0, $c - 1); $j <= min($cols - 1, $c + 1); $j++) {
                if ($grid[$i][$j] !== 'M') {
                    $grid[$i][$j]++; // Ökar siffra
                }
            }
        }
    }
    return $grid; // Returnerar spelplan
}

// Visar en ruta
function revealCell($row, $col) {
    if ($_SESSION['revealed'][$row][$col] || $_SESSION['flags'][$row][$col]) { // Om redan visad eller flaggad
        return;
    }
    $_SESSION['revealed'][$row][$col] = true; // Visar ruta
    if ($_SESSION['grid'][$row][$col] != 'M') { // Om inte mina
        $_SESSION['currentpoints']++; // Ökar poäng
    }
    if ($_SESSION['grid'][$row][$col] == 'M') { // Om mina
        $_SESSION['game_state'] = 'lost'; // Spelet förlorat
        $_SESSION['lose']++; // Ökar förluster
        $totalPointsLost = 5; // Straffpoäng
        $cPoints = $_SESSION['currentpoints'] - $totalPointsLost; // Räknar poäng
        if ($cPoints > 0) { // Om poäng kvar
            $_SESSION['points'] = $cPoints;
            $cPoints = 0;
        } elseif ($cPoints < 0) { // Om negativt
            $_SESSION['points'] = 0;
            $cPoints = 0;
        } else { // Om noll
            $_SESSION['points'] = 0;
        }
        updateDatabaseLoss(); // Uppdaterar databas med förlust
        revealAllMines(); // Visar alla minor
        return;
    }
    if ($_SESSION['grid'][$row][$col] == 0) { // Om 0
        revealCluster($row, $col); // Visar kluster
    }
}

// Visar kluster runt en 0:a
function revealCluster($row, $col) {
    $rows = count($_SESSION['grid']); // Antal rader
    $cols = count($_SESSION['grid'][0]); // Antal kolumner
    $queue = [[$row, $col]]; // Startar kö
    while (!empty($queue)) {
        list($r, $c) = array_shift($queue); // Tar nästa ruta
        if ($_SESSION['revealed'][$r][$c]) { // Om redan visad
            continue;
        }
        $_SESSION['revealed'][$r][$c] = true; // Visar ruta
        if ($_SESSION['grid'][$r][$c] == 0) { // Om 0
            for ($i = max(0, $r - 1); $i <= min($r + 1, $rows - 1); $i++) { // Lägger till närliggande rutor
                for ($j = max(0, $c - 1); $j <= min($c + 1, $cols - 1); $j++) {
                    if (!$_SESSION['revealed'][$i][$j]) {
                        $queue[] = [$i, $j];
                    }
                }
            }
        }
    }
}

// Kollar om spelet är vunnet eller förlorat
function checkGameState() {
    if ($_SESSION['game_state'] === 'lost') { // Om redan förlorat
        return;
    }
    $rows = count($_SESSION['grid']); // Antal rader
    $cols = count($_SESSION['grid'][0]); // Antal kolumner
    $totalCells = $rows * $cols; // Totala rutor
    $mines = 0; // Räknar minor
    foreach ($_SESSION['grid'] as $row) {
        foreach ($row as $cell) {
            if ($cell == 'M') {
                $mines++;
            }
        }
    }
    $revealedCount = 0; // Räknar visade rutor
    foreach ($_SESSION['revealed'] as $row) {
        foreach ($row as $revealed) {
            if ($revealed) {
                $revealedCount++;
            }
        }
    }
    if ($revealedCount === ($totalCells - $mines)) { // Om alla säkra rutor visade
        $_SESSION['game_state'] = 'won'; // Spelet vunnet
        $_SESSION['wins']++; // Ökar vinster
        updateDatabaseWin(); // Uppdaterar databas med vinst
        revealAllMines(); // Visar alla minor
    }
}

// Visar alla minor
function revealAllMines() {
    for ($r = 0; $r < count($_SESSION['grid']); $r++) {
        for ($c = 0; $c < count($_SESSION['grid'][0]); $c++) {
            if ($_SESSION['grid'][$r][$c] == 'M') {
                $_SESSION['revealed'][$r][$c] = true; // Visar mina
            }
        }
    }
}

// Uppdaterar databasen vid vinst
function updateDatabaseWin() {
    $conn = new mysqli("localhost", "Minesweeper", "Minesweeper", "Minesweeper"); // Kopplar till databas
    if ($conn->connect_error) {
        error_log("Database connection failed: " . $conn->connect_error); // Loggar fel
        return;
    }
    $points = ($_SESSION['pre_game_points'] + $_SESSION['points']); // Totala poäng
    $wins = $_SESSION['wins']; // Antal vinster
    $username = $_SESSION['userid'] ?? ''; // Användarnamn
    if (!empty($username)) {
        $stmt = $conn->prepare("UPDATE `score` SET `points` = ?, `wins` = ? WHERE `username` = ?"); // Förbereder uppdatering
        if ($stmt) {
            $stmt->bind_param("iis", $points, $wins, $username); // Binder värden
            if (!$stmt->execute()) {
                error_log("Failed to execute statement in updateDatabaseWin: " . $stmt->error); // Loggar fel
            } else {
                error_log("Successfully updated points and wins for user: $username"); // Loggar lyckat
            }
            $stmt->close(); // Stänger fråga
        } else {
            error_log("Failed to prepare statement in updateDatabaseWin: " . $conn->error); // Loggar fel
        }
    } else {
        error_log("Username is empty in updateDatabaseWin."); // Loggar om användarnamn saknas
    }
    $conn->close(); // Stänger databas
}

// Uppdaterar databasen vid förlust
function updateDatabaseLoss() {
    $conn = new mysqli("localhost", "Minesweeper", "Minesweeper", "Minesweeper"); // Kopplar till databas
    if ($conn->connect_error) {
        error_log("Database connection failed: " . $conn->connect_error); // Loggar fel
        return;
    }
    $stats = getUserPoints(); // Hämtar statistik
    $_SESSION['pre_game_points'] = $stats['points']; // Sparar poäng före spelet
    $points = ($_SESSION['pre_game_points'] + $_SESSION['points']); // Totala poäng
    error_log($_SESSION['points']); // Loggar poäng
    $_SESSION['points'] = 0; // Nollställer poäng
    $_SESSION['currentpoints'] = 0; // Nollställer nuvarande poäng
    $lose = $_SESSION['lose']; // Antal förluster
    $username = $_SESSION['userid'] ?? ''; // Användarnamn
    if (!empty($username)) {
        $stmt = $conn->prepare("UPDATE `score` SET `points` = ?, `lose` = ? WHERE `username` = ?"); // Förbereder uppdatering
        if ($stmt) {
            $stmt->bind_param("iis", $points, $lose, $username); // Binder värden
            if (!$stmt->execute()) {
                error_log("Failed to execute statement in updateDatabaseLoss: " . $stmt->error); // Loggar fel
            } else {
                error_log("Successfully updated points and losses for user: $username"); // Loggar lyckat
            }
            $stmt->close(); // Stänger fråga
        } else {
            error_log("Failed to prepare statement in updateDatabaseLoss: " . $conn->error); // Loggar fel
        }
    } else {
        error_log("Username is empty in updateDatabaseLoss."); // Loggar om användarnamn saknas
    }
    $conn->close(); // Stänger databas
}