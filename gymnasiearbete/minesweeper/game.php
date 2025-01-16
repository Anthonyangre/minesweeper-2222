<?php
session_start();
require_once '../assets/functions.php';
if (!isset($_SESSION['userid'])) {
    echo "du är inte välkommen";
    header('Location: ../index.php');
}
// Initialize the game state if it doesn't exist
if (!isset($_SESSION['game_state'])) {
    $_SESSION['game_state'] = 'ongoing';
    $rows = 10;
    $cols = 10;
    $mines = 20;  // Change this to your desired mine count
    $_SESSION['grid'] = generateGrid($rows, $cols, $mines);
    $_SESSION['revealed'] = array_fill(0, $rows, array_fill(0, $cols, false));
    $_SESSION['flags'] = array_fill(0, $rows, array_fill(0, $cols, false));
    $_SESSION['points'] = 0;
    $stats = getUserPoints();
    $_SESSION['pre_game_points'] = $stats['points']; 
    $_SESSION['wins'] = 0;
    $_SESSION['lose'] = 0;
    $_SESSION['currentpoints'] = 0;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
 
    if ($_SESSION['game_state'] != 'ongoing') {
        $_SESSION['currentpoints'] = 0;
        
    }
    if ($_SESSION['game_state'] === 'ongoing') {
        $action = $_POST['action'];
        $row = (int)$_POST['row'];
        $col = (int)$_POST['col'];
       
       

        if ($action === 'reveal') {
            // Check if it's the first click
            if (isFirstClick()) {
                ensureFirstClickIsZero($row, $col);  // Ensure first click is on a '0' cell
                revealCluster($row, $col);           // Reveal a cluster of safe cells
            } else {
                revealCell($row, $col);              // Reveal the clicked cell (and potentially its surroundings if it's 0)
            }
        } elseif ($action === 'flag') {
            $_SESSION['flags'][$row][$col] = !$_SESSION['flags'][$row][$col];
        }

        // After action, check for win or lose
        checkGameState();
    }

    // Return the updated game state
    echo json_encode([
        'grid' => $_SESSION['grid'],
        'revealed' => $_SESSION['revealed'],
        'flags' => $_SESSION['flags'],
        'game_state' => $_SESSION['game_state'],
        'points' => $_SESSION['points']  // Include points in the response for the frontend
    ]);
}

function isFirstClick() {
    foreach ($_SESSION['revealed'] as $row) {
        if (in_array(true, $row)) {
            return false;
        }
    }
    return true;
}

function ensureFirstClickIsZero($row, $col) {
    // If the first clicked cell is not a '0', regenerate the grid to ensure the clicked cell is a '0'
    if ($_SESSION['grid'][$row][$col] != 0) {
        regenerateGridWithZeroAt($row, $col);
    }
}
function regenerateGridWithZeroAt($safeRow, $safeCol) {
    $rows = count($_SESSION['grid']);
    $cols = count($_SESSION['grid'][0]);
    $mines = 20;  // Adjust this to match your desired mine count

    do {
        $_SESSION['grid'] = generateGrid($rows, $cols, $mines);
    } while ($_SESSION['grid'][$safeRow][$safeCol] != 0);
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


function revealCell($row, $col) {
    // If already revealed or flagged, do nothing
    if ($_SESSION['revealed'][$row][$col] || $_SESSION['flags'][$row][$col]) {
        return;
    }

    // Reveal the clicked cell
    $_SESSION['revealed'][$row][$col] = true;
 // Increment points when a cell is revealed
    if ($_SESSION['grid'][$row][$col] != 'M') {
        $_SESSION['currentpoints']++;
    }
    // If it's a mine, the game is lost
    if ($_SESSION['grid'][$row][$col] == 'M') {
        $_SESSION['game_state'] = 'lost';
        $_SESSION['lose']++;  // Increment the loss count
    
        // Apply a penalty of -10 points for the loss
        $totalPointsLost = 5;  // Define the penalty explicitly
        $cPoints =  $_SESSION['currentpoints'] - $totalPointsLost;
        
        if ($cPoints > 0) {
            $pointsloss = $cPoints;
            $_SESSION['points'] = ($pointsloss); 
            $cPoints = 0;
        } elseif ($cPoints < 0 ) {
            $_SESSION['points'] = 0;
            $cPoints = 0;
         } else {
            $_SESSION['points'] = 0; 

         }
        
        
          // Apply penalty but ensure points don't go negative
    
        // Update the database with the loss
        updateDatabaseLoss();
    
        revealAllMines();

        return;
    }
    

    // If it's a zero, reveal surrounding cells
    if ($_SESSION['grid'][$row][$col] == 0) {
        revealCluster($row, $col);  // Only reveal surrounding cells if it's 0
        
    }
}

function revealCluster($row, $col) {
    $rows = count($_SESSION['grid']);
    $cols = count($_SESSION['grid'][0]);

    // Use a queue for breadth-first search (BFS) to reveal all connected zero cells and their neighbors
    $queue = [[$row, $col]];
    while (!empty($queue)) {
        list($r, $c) = array_shift($queue);

        // If the cell is already revealed, continue
        if ($_SESSION['revealed'][$r][$c]) {
            continue;
        }

        // Reveal this cell
        $_SESSION['revealed'][$r][$c] = true;

        // If the cell is a zero, add its neighbors to the queue
        if ($_SESSION['grid'][$r][$c] == 0) {
            for ($i = max(0, $r - 1); $i <= min($r + 1, $rows - 1); $i++) {
                for ($j = max(0, $c - 1); $j <= min($c + 1, $cols - 1); $j++) {
                    if (!$_SESSION['revealed'][$i][$j]) {
                        $queue[] = [$i, $j];
                    }
                }
            }
        }
    }
}

function checkGameState() {
    if ($_SESSION['game_state'] === 'lost') {
        return;
    }

    $rows = count($_SESSION['grid']);
    $cols = count($_SESSION['grid'][0]);
    $totalCells = $rows * $cols;
    $mines = 0;

    // Count mines
    foreach ($_SESSION['grid'] as $row) {
        foreach ($row as $cell) {
            if ($cell == 'M') {
                $mines++;
            }
        }
    }

    // Count revealed cells
    $revealedCount = 0;
    foreach ($_SESSION['revealed'] as $row) {
        foreach ($row as $revealed) {
            if ($revealed) {
                $revealedCount++;
            }
        }
    }

    // Check if all non-mine cells have been revealed (player wins)
    if ($revealedCount === ($totalCells - $mines)) {
        $_SESSION['game_state'] = 'won';
        $_SESSION['wins']++;  // Increment the win count

        // Update database with win and points
        updateDatabaseWin();

        revealAllMines(); // Optionally reveal all mines on win
    }
}

function revealAllMines() {
    for ($r = 0; $r < count($_SESSION['grid']); $r++) {
        for ($c = 0; $c < count($_SESSION['grid'][0]); $c++) {
            if ($_SESSION['grid'][$r][$c] == 'M') {
                $_SESSION['revealed'][$r][$c] = true;
            }
        }
    }
}

function updateDatabaseWin() {
    $conn = new mysqli("localhost", "Minesweeper", "Minesweeper", "Minesweeper");

    if ($conn->connect_error) {
        error_log("Database connection failed: " . $conn->connect_error);
        return;
    }

    $points = ($_SESSION['pre_game_points'] + $_SESSION['points']);  
    $wins = $_SESSION['wins'];
    $username = $_SESSION['userid'] ?? '';

    if (!empty($username)) {
        $stmt = $conn->prepare("UPDATE `score` SET `points` = ?, `wins` = ? WHERE `username` = ?");
        if ($stmt) {
            $stmt->bind_param("iis", $points, $wins, $username);
            if (!$stmt->execute()) {
                error_log("Failed to execute statement in updateDatabaseWin: " . $stmt->error);
            } else {
                error_log("Successfully updated points and wins for user: $username");
            }
            $stmt->close();
        } else {
            error_log("Failed to prepare statement in updateDatabaseWin: " . $conn->error);
        }
    } else {
        error_log("Username is empty in updateDatabaseWin.");
    }

    $conn->close();
}

function updateDatabaseLoss() {
    $conn = new mysqli("localhost", "Minesweeper", "Minesweeper", "Minesweeper");

    if ($conn->connect_error) {
        error_log("Database connection failed: " . $conn->connect_error);
        return;
    }
    $stats = getUserPoints();
    $_SESSION['pre_game_points'] = $stats['points'];

    $points = ($_SESSION['pre_game_points'] + $_SESSION['points']);  
    error_log($_SESSION['points']);
    $_SESSION['points'] = 0;
    $_SESSION['currentpoints'] = 0;
 
    $lose = $_SESSION['lose'];
    $username = $_SESSION['userid'] ?? '';

    if (!empty($username)) {
        $stmt = $conn->prepare("UPDATE `score` SET `points` = ?, `lose` = ? WHERE `username` = ?");
        if ($stmt) {
            $stmt->bind_param("iis", $points, $lose, $username);
            if (!$stmt->execute()) {
                error_log("Failed to execute statement in updateDatabaseLoss: " . $stmt->error);
            } else {
                error_log("Successfully updated points and losses for user: $username");
            }
            $stmt->close();
        } else {
            error_log("Failed to prepare statement in updateDatabaseLoss: " . $conn->error);
        }
    } else {
        error_log("Username is empty in updateDatabaseLoss.");
    }

    $conn->close();
}
