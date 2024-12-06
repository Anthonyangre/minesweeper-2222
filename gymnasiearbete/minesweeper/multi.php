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
    header("Location: ../login.php");
    exit();
}

$player_id = $_SESSION['userid'];

// Handle game creation or joining
if (!isset($_GET['game_id'])) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
        if ($_POST['action'] === 'create_game') {
            // Create a new game for this player
            $query = "INSERT INTO games (player_1_id, is_ongoing, turn_player_id) VALUES (?, 0, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ii", $player_id, $player_id);  // Player 1 starts first

            if ($stmt->execute()) {
                $game_id = $stmt->insert_id;
                $_SESSION['game_id'] = $game_id;
                echo "Game created with ID: $game_id<br>";
                header("Location: multi.php?game_id=$game_id");
                exit();
            } else {
                echo "Error executing query: " . $stmt->error . "<br>";
            }
        } elseif ($_POST['action'] === 'join_game' && isset($_POST['game_id'])) {
            // Join an existing game
            $game_id = $_POST['game_id'];

            // Check if the game exists and is waiting for a second player
            $query = "SELECT player_1_id, is_ongoing, player_2_id FROM games WHERE game_id = ? AND player_2_id IS NULL";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $game_id);
            $stmt->execute();
            $stmt->store_result();
            
            if ($stmt->num_rows > 0) {
                $stmt->bind_result($player_1_id, $is_ongoing, $player_2_id);
                $stmt->fetch();
                
                // Assign the player to this game
                $query = "UPDATE games SET player_2_id = ?, is_ongoing = 1, turn_player_id = ? WHERE game_id = ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("iii", $player_id, $player_1_id, $game_id); // Player 1 starts the game
                $stmt->execute();

                $_SESSION['game_id'] = $game_id;
                echo "Joined game with ID: $game_id<br>";
                header("Location: multi.php?game_id=$game_id");
                exit();
            } else {
                $error_message = "Invalid game ID or the game is not available to join.";
                echo "<p style='color:red;'>$error_message</p>";
            }
        }
    }

    // Show the form to either create or join a game
    echo '<h1>Welcome to Minesweeper</h1>';
    echo '<form method="POST">
            <button type="submit" name="action" value="create_game">Create a New Game</button>
          </form>';
    echo '<h2>Or Join an Existing Game</h2>';
    echo '<form method="POST">
            <input type="text" name="game_id" placeholder="Enter Game ID" required>
            <button type="submit" name="action" value="join_game">Join Game</button>
          </form>';
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

// Debugging game data
echo "<br><strong>Game Details:</strong><br>";
echo "player_1_id = $player_1_id, player_2_id = $player_2_id, is_ongoing = $is_ongoing, turn_player_id = $turn_player_id<br>";

// Check if the game is valid and ongoing
if ($is_ongoing != 1 || !in_array($player_id, [$player_1_id, $player_2_id])) {
    echo "Invalid game status or player not part of this game.<br>";
    die();
}

// Fetch the board state for this player
$query = "SELECT * FROM board WHERE game_id = ? AND player_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $game_id, $player_id);
$stmt->execute();
$result = $stmt->get_result();

$board = [];
while ($row = $result->fetch_assoc()) {
    $board[$row['row']][$row['col']] = $row['cell_value'];
}

// Handle player actions (reveal/flag)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['row'], $_POST['col'], $_POST['action'])) {
    $row = $_POST['row'];
    $col = $_POST['col'];
    $action = $_POST['action'];

    // Debugging player action
    echo "<br><strong>Player Action:</strong><br>";
    echo "row = $row, col = $col, action = $action, current_turn_player = $turn_player_id, player_id = $player_id<br>";

    // Only allow actions if it's the player's turn
    if ($turn_player_id === $player_id) {
        if ($action === 'reveal') {
            $query = "UPDATE board SET cell_value = 'revealed' WHERE game_id = ? AND player_id = ? AND row = ? AND col = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("iiii", $game_id, $player_id, $row, $col);
            $stmt->execute();
        } elseif ($action === 'flag') {
            $query = "UPDATE board SET cell_value = 'flagged' WHERE game_id = ? AND player_id = ? AND row = ? AND col = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("iiii", $game_id, $player_id, $row, $col);
            $stmt->execute();
        }

        // Switch the turn to the next player
        $next_player_id = ($turn_player_id == $player_1_id) ? $player_2_id : $player_1_id;
        $query = "UPDATE games SET turn_player_id = ? WHERE game_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ii", $next_player_id, $game_id);
        $stmt->execute();
        
        echo "<br><strong>Turn updated:</strong> next_player_id = $next_player_id<br>";
    } else {
        echo "<br><strong>Not your turn!</strong><br>";
    }
}

// Display game board and actions
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minesweeper Multiplayer</title>
    <style>
        table { border-collapse: collapse; }
        td { width: 30px; height: 30px; text-align: center; border: 1px solid #000; }
        .revealed { background-color: lightgrey; }
        .flagged { background-color: yellow; }
    </style>
</head>
<body>
    <h1>Minesweeper Multiplayer Game</h1>
    <p>Game ID: <?php echo $game_id; ?> | 
       <?php echo ($turn_player_id === $player_id) ? "Your Turn" : "Opponent's Turn"; ?>
    </p>

    <form action="multi.php?game_id=<?php echo $game_id; ?>" method="post">
        <table>
            <?php for ($i = 0; $i < 10; $i++): ?>
                <tr>
                    <?php for ($j = 0; $j < 10; $j++): ?>
                        <td class="<?php echo $board[$i][$j] === 'revealed' ? 'revealed' : ($board[$i][$j] === 'flagged' ? 'flagged' : ''); ?>">
                            <?php
                                if ($board[$i][$j] === 'revealed') {
                                    echo "&nbsp;"; // Empty or value of the cell
                                } else {
                                    // Show action buttons only for current player
                                    if ($turn_player_id === $player_id) {
                                        echo "<button type='submit' name='row' value='$i' name='col' value='$j' name='action' value='reveal'>Reveal</button><br>";
                                        echo "<button type='submit' name='row' value='$i' name='col' value='$j' name='action' value='flag'>Flag</button>";
                                    }
                                }
                            ?>
                        </td>
                    <?php endfor; ?>
                </tr>
            <?php endfor; ?>
        </table>
    </form>
</body>
</html>
