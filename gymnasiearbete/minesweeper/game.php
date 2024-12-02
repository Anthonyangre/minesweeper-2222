<?php
session_start();

// Initialize the game state if it doesn't exist
if (!isset($_SESSION['game_state'])) {
    $_SESSION['game_state'] = 'ongoing';
    $_SESSION['grid'] = generateGrid(10, 10, 10); // 10x10 grid with 10 mines
    $_SESSION['revealed'] = array_fill(0, 10, array_fill(0, 10, false));
    $_SESSION['flags'] = array_fill(0, 10, array_fill(0, 10, false));
    $_SESSION['points'] = 0;  // Initialize points for the session
    $_SESSION['pre_game_points'] = 0;  // Store points before the game starts
    $_SESSION['wins'] = 0;  // Initialize wins
    $_SESSION['lose'] = 0;  // Initialize losses
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
    $mines = 10;  // Adjust the number of mines as necessary

    do {
        $_SESSION['grid'] = generateGrid($rows, $cols, $mines);
    } while ($_SESSION['grid'][$safeRow][$safeCol] != 0);  // Ensure the clicked cell is a '0'
}

function generateGrid($rows, $cols, $mines) {
    $grid = array_fill(0, $rows, array_fill(0, $cols, 0));

    // Place mines randomly on the grid
    $minesPlaced = 0;
    while ($minesPlaced < $mines) {
        $r = rand(0, $rows - 1);
        $c = rand(0, $cols - 1);
        if ($grid[$r][$c] == 0) {
            $grid[$r][$c] = 'M';  // Mark as mine
            $minesPlaced++;

            // Update surrounding cells' mine count
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

function revealCell($row, $col) {
    // If already revealed or flagged, do nothing
    if ($_SESSION['revealed'][$row][$col] || $_SESSION['flags'][$row][$col]) {
        return;
    }

    // Reveal the clicked cell
    $_SESSION['revealed'][$row][$col] = true;
    $_SESSION['currentpoints']++;  // Increment points when a cell is revealed

    // If it's a mine, the game is lost
    if ($_SESSION['grid'][$row][$col] == 'M') {
        $_SESSION['game_state'] = 'lost';
        $_SESSION['lose']++;  // Increment the loss count
    
        // Apply a penalty of -10 points for the loss
        $totalPointsLost = 5;  // Define the penalty explicitly
        $currentPoints = $_SESSION['currentpoints'];
        $cPoints = $currentPoints - $totalPointsLost;
        $pointsloss = max(0, $cPoints);

        
        $_SESSION['points'] = ($pointsloss);  // Apply penalty but ensure points don't go negative
    
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

    $points = ($_SESSION['pre_game_points'] + $_SESSION['points']);  // Multiply points by 100 for scoring system
    $wins = $_SESSION['wins'];
    $username = $_SESSION['username'] ?? '';

    if (!empty($username)) {
        $stmt = $conn->prepare("UPDATE `score` SET `points` = ?, `wins` = ? WHERE `username` = ?");
        if ($stmt) {
            $stmt->bind_param("iis", $points, $wins, $username);
            if (!$stmt->execute()) {
                error_log("Failed to update database: " . $stmt->error);
            }
            $stmt->close();
        } else {
            error_log("Failed to prepare statement: " . $conn->error);
        }
    }

    $conn->close();
}

function updateDatabaseLoss() {
    $conn = new mysqli("localhost", "Minesweeper", "Minesweeper", "Minesweeper");

    $points = $_SESSION['points'];  // Multiply points by 100 for scoring system
    $lose = $_SESSION['lose'];
    $username = $_SESSION['username'] ?? '';

    if (!empty($username)) {
        $stmt = $conn->prepare("UPDATE `score` SET `points` = ?, `lose` = ? WHERE `username` = ?");
        if ($stmt) {
            $stmt->bind_param("iis", $points, $lose, $username);
            if (!$stmt->execute()) {
                error_log("Failed to update database: " . $stmt->error);
            }
            $stmt->close();
        } else {
            error_log("Failed to prepare statement: " . $conn->error);
        }
    }

    $conn->close();
}
?>
