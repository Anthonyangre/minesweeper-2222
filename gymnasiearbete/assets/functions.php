<?php
//sql connection till våran sql server genom minesweeper usern

function getDatabaseConnection() {
    // Skapar och returnerar en ny databasanslutning
    return new mysqli("localhost", "Minesweeper", "Minesweeper", "Minesweeper");
}

function getScore() {
    $conn = getDatabaseConnection(); // Hämtar databasanslutningen
    if ($conn->connect_error) {
        die("Anslutning misslyckades: " . $conn->connect_error);
    }

    // Förbereder SQL-frågan för att hämta poäng i fallande ordning
    $stmt = $conn->prepare("SELECT * FROM score ORDER BY points DESC");
    if (!$stmt) {
        die("Misslyckades att förbereda frågan: " . $conn->error);
    }

    $stmt->execute(); // Kör SQL-frågan
    $result = $stmt->get_result(); // Hämtar resultatet
    $stmt->close(); // Stänger SQL-förfrågan
    $conn->close(); // Stänger databasanslutningen
    return $result; // Returnerar resultatet
}


function getUserPoints() {
    $conn = getDatabaseConnection(); // Hämtar databasanslutningen
    if ($conn->connect_error) {
        die("Anslutning misslyckades: " . $conn->connect_error);
    }

    session_start(); // Säkerställer att sessionen är startad
    $username = $_SESSION['userid']; // Hämtar användarnamnet från sessionen

    // Förbereder SQL-frågan för att hämta användarens poäng, vinster och förluster
    $stmt = $conn->prepare("SELECT points, wins, lose FROM score WHERE username = ?");
    if (!$stmt) {
        die("Misslyckades att förbereda frågan: " . $conn->error);
    }

    $stmt->bind_param("s", $username); // Binder parametern till användarnamnet
    $stmt->execute(); // Kör SQL-frågan
    $result = $stmt->get_result(); // Hämtar resultatet
    $stats = $result->fetch_assoc(); // Lagrar resultatet i en associerad array
    $stmt->close(); // Stänger SQL-förfrågan
    $conn->close(); // Stänger databasanslutningen
    return $stats; // Returnerar användarens statistik
}
?>
