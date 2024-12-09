<?php
session_start();
$conn = new mysqli("localhost", "Minesweeper", "Minesweeper", "Minesweeper");


if (!isset($_SESSION['userid'])) {
    echo "Unauthorized access.";
    header('Location: ../index.php');
    exit;
}

$user_id = $_SESSION['userid'];

// Fetch the game data
$game_id = $_POST['game_id'];
$query = "SELECT * FROM game WHERE game_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $game_id);
$stmt->execute();
$result = $stmt->get_result();
$game = $result->fetch_assoc();

if (!$game) {
    echo json_encode(['error' => 'Game not found']);
    exit;
}

// Check if it's the player's turn
if ((int)$game['current_turn'] !== (int)$user_id) {
    echo json_encode(['error' => 'Not your turn']);
    exit;
}

// Decode grid, revealed, and flags
$grid = json_decode($game['grid'], true);
$revealed = json_decode($game['revealed'], true);
$flags = json_decode($game['flags'], true);

// Handle the player's action
$action = $_POST['action'];
$row = (int)$_POST['row'];
$col = (int)$_POST['col'];

if ($action === 'reveal') {
    if ($revealed[$row][$col] || $flags[$row][$col]) {
        echo json_encode(['error' => 'Cell already revealed or flagged']);
        exit;
    }

    $revealed[$row][$col] = true;

    if ($grid[$row][$col] === 'M') {
        // Player hit a mine - lose the game
        $game['state'] = 'lost';
        updateDatabase($game_id, $grid, $revealed, $flags, $game['state']);
        echo json_encode(['state' => 'lost', 'grid' => $grid, 'revealed' => $revealed]);
        exit;
    }

    if ($grid[$row][$col] === 0) {
        revealCluster($row, $col, $grid, $revealed);
    }
} elseif ($action === 'flag') {
    $flags[$row][$col] = !$flags[$row][$col];
}

// Update points and game state
if ($user_id == $game['player_1_id']) {
    $game['player_1_points']++;
} else {
    $game['player_2_points']++;
}

$game['current_turn'] = ($user_id == $game['player_1_id']) ? $game['player_2_id'] : $game['player_1_id'];

// Save updated game state to the database
updateDatabase($game_id, $grid, $revealed, $flags, $game['state'], $game['current_turn'], $game['player_1_points'], $game['player_2_points']);

echo json_encode([
    'grid' => $grid,
    'revealed' => $revealed,
    'flags' => $flags,
    'state' => $game['state'],
    'current_turn' => $game['current_turn'],
    'player_1_points' => $game['player_1_points'],
    'player_2_points' => $game['player_2_points']
]);

function revealCluster($row, $col, &$grid, &$revealed) {
    $rows = count($grid);
    $cols = count($grid[0]);
    $queue = [[$row, $col]];

    while (!empty($queue)) {
        [$r, $c] = array_shift($queue);
        if ($revealed[$r][$c]) continue;
        $revealed[$r][$c] = true;

        if ($grid[$r][$c] === 0) {
            for ($i = max(0, $r - 1); $i <= min($rows - 1, $r + 1); $i++) {
                for ($j = max(0, $c - 1); $j <= min($cols - 1, $c + 1); $j++) {
                    if (!$revealed[$i][$j]) $queue[] = [$i, $j];
                }
            }
        }
    }
}

function updateDatabase($game_id, $grid, $revealed, $flags, $state, $current_turn, $player_1_points, $player_2_points) {
    global $conn;

    $query = "UPDATE game SET grid = ?, revealed = ?, flags = ?, state = ?, current_turn = ?, player_1_points = ?, player_2_points = ? WHERE game_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssiiiii",
        json_encode($grid),
        json_encode($revealed),
        json_encode($flags),
        $state,
        $current_turn,
        $player_1_points,
        $player_2_points,
        $game_id
    );
    $stmt->execute();
}
?>
