<?php
session_start();
$conn = new mysqli("localhost", "Minesweeper", "Minesweeper", "Minesweeper");

// Debugging connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if player is logged in
if (!isset($_SESSION['userid'])) {
    echo "Player is not logged in. Redirecting to login.<br>";
    header("Location: ../../login.php");
    exit();
}

$player_id = $_SESSION['userid'];

// Ensure a valid game ID is passed
if (!isset($_GET['game_id'])) {
    echo "No game ID provided. Redirecting to lobby.<br>";
    header("Location: game_lobby.php");
    exit();
}

$game_id = $_GET['game_id'];

// Fetch game details
$query = "SELECT player_1_id, player_2_id, is_ongoing, turn_player_id FROM games WHERE game_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $game_id);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($player_1_id, $player_2_id, $is_ongoing, $turn_player_id);
$stmt->fetch();

// Check if the game is valid
if (!$player_2_id) {
    echo "<h2>Waiting for another player to join...</h2>";
    echo "<p>Share this Game ID with another player: <strong>$game_id</strong></p>";
    exit(); // Stop further processing until another player joins
}

if (!in_array($player_id, [$player_1_id, $player_2_id])) {
    echo "Invalid game status or player not part of this game.<br>";
    die();
}

// Switch the turn if it's this player's turn
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'end_turn') {
    if ($turn_player_id === $player_id) {
        $next_player_id = ($turn_player_id == $player_1_id) ? $player_2_id : $player_1_id;
        $query = "UPDATE games SET turn_player_id = ? WHERE game_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ii", $next_player_id, $game_id);
        $stmt->execute();
        header("Location: multi.php?game_id=$game_id");
        exit();
    } else {
        echo "<p style='color:red;'>It's not your turn!</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Multiplayer Game</title>
</head>
<body>
    <h1>Game ID: <?php echo $game_id; ?></h1>
    <p>Current Turn: <?php echo ($turn_player_id === $player_id) ? "Your Turn" : "Opponent's Turn"; ?></p>

    <?php if ($turn_player_id === $player_id): ?>
        <form method="POST">
            <button type="submit" name="action" value="end_turn">End Turn</button>
        </form>
    <?php else: ?>
        <p>Waiting for your opponent...</p>
    <?php endif; ?>
</body>
</html>
