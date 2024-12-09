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

// Handle game creation or joining
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'create_game') {
        // Create a new game for this player
        $query = "INSERT INTO games (player_1_id, is_ongoing, turn_player_id) VALUES (?, 0, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ii", $player_id, $player_id); // Player 1 starts first

        if ($stmt->execute()) {
            $game_id = $stmt->insert_id;
            $_SESSION['game_id'] = $game_id;
            header("Location: multi.php?game_id=$game_id");
            exit();
        } else {
            echo "Error creating game: " . $stmt->error . "<br>";
        }
    } elseif ($_POST['action'] === 'join_game' && isset($_POST['game_id'])) {
        $game_id = $_POST['game_id'];

        // Check if the game is available to join
        $query = "SELECT player_1_id, is_ongoing FROM games WHERE game_id = ? AND player_2_id IS NULL";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $game_id);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $query = "UPDATE games SET player_2_id = ?, is_ongoing = 1 WHERE game_id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ii", $player_id, $game_id);
            $stmt->execute();

            $_SESSION['game_id'] = $game_id;
            header("Location: multi.php?game_id=$game_id");
            exit();
        } else {
            echo "<p style='color:red;'>Invalid game ID or the game is not available to join.</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Multiplayer Game Lobby</title>
</head>
<body>
    <h1>Welcome to the Multiplayer Game Lobby</h1>
    <form method="POST">
        <button type="submit" name="action" value="create_game">Create a New Game</button>
    </form>

    <h2>Or Join an Existing Game</h2>
    <form method="POST">
        <input type="text" name="game_id" placeholder="Enter Game ID" required>
        <button type="submit" name="action" value="join_game">Join Game</button>
    </form>
</body>
</html>
