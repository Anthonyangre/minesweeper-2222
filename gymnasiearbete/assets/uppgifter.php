<?php
$conn = new mysqli("localhost", "Minesweeper", "Minesweeper", "Minesweeper");

// Start the session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Ensure the user is logged in
if (isset($_SESSION['userid'])) {
    $userid = $_SESSION['userid'];

    // Query to fetch user data
    $stmt = $conn->prepare("SELECT username, name, email FROM users WHERE id = ?");
    $stmt->bind_param("s", $userid);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $username = $user['username'];
        $name = $user['name'];
        $email = $user['email'];
    } else {
        $errors[] = "Användardata kunde inte hämtas.";
    }
    $stmt->close();
} else {
    $errors[] = "Du måste vara inloggad för att ändra dina uppgifter";
}

?>