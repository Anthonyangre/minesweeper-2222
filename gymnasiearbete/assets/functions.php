<?php

function getScore(){
    $conn = new mysqli("localhost", "Minesweeper", "Minesweeper", "Minesweeper");
    $stmt = $conn->prepare("SELECT * FROM score ORDER BY id DESC"); // Query to fetch user data
    $stmt->execute();
    $result = $stmt->get_result();
    return $result;
}
function getDatabasePoints() {
    $conn = new mysqli("localhost", "Minesweeper", "Minesweeper", "Minesweeper");

    $username = $_SESSION['username'];

    $stmt = $conn->prepare("SELECT points FROM score WHERE username = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();
            $score = $result->fetch_assoc();

    return $score['points'];
    
}
$conn->close();
?>