<?php
function getDatabaseConnection() {
    // Create and return a new database connection
    return new mysqli("localhost", "Minesweeper", "Minesweeper", "Minesweeper");
}

function getScore() {
    $conn = getDatabaseConnection(); // Get connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("SELECT * FROM score ORDER BY id DESC");
    if (!$stmt) {
        die("Statement preparation failed: " . $conn->error);
    }

    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close(); // Close the statement
    $conn->close(); // Close the connection
    return $result;
}

function getDatabasePoints() {
    $conn = getDatabaseConnection(); // Get connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    session_start(); // Ensure session is started
    $username = $_SESSION['username'];

    $stmt = $conn->prepare("SELECT points FROM score WHERE username = ?");
    if (!$stmt) {
        die("Statement preparation failed: " . $conn->error);
    }

    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $score = $result->fetch_assoc();
    $stmt->close(); // Close the statement
    $conn->close(); // Close the connection
    return $score['points'];
}
function getUserPoints() {
    $conn = getDatabaseConnection(); // Get connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    session_start(); // Ensure session is started
    $username = $_SESSION['username'];

    $stmt = $conn->prepare("SELECT points, wins, lose FROM score WHERE username = ?");
    if (!$stmt) {
        die("Statement preparation failed: " . $conn->error);
    }

    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $stats = $result->fetch_assoc();
    $stmt->close(); // Close the statement
    $conn->close(); // Close the connection
    return $stats;
}
?>
