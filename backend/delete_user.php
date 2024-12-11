<?php
session_start();
require_once '../assets/config/db.php'; // Inkludera db.php för databasanslutningen


// Kontrollera att 'username' finns i GET-parameter
if (isset($_GET['username'])) {
    $username = $_GET['username'];

    // Anslut till databasen
    $pdo = getDbConnection();

    // Förbered SQL-frågan för att ta bort användaren baserat på username
    // Ändra här tabellnamnet till den tabell som innehåller användardata,
    $stmt = $pdo->prepare("DELETE FROM user WHERE username = :username");
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);

    // Utför frågan och kontrollera om den lyckades
    if ($stmt->execute()) {
        header("Location: users.php"); // Omdirigera till users.php efter borttagning
        exit();
    } else {
        echo "Fel: Användaren kunde inte tas bort.";
        print_r($stmt->errorInfo()); // Utskrift för felsökning
    }
} else {
    echo "Inget användarnamn angivet.";
}



?>
