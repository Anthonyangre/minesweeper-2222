<?php
session_start();
require_once '../assets/functions.php'; // Inkludera funktionerna

// Kontrollera om användaren är inloggad och har administratörsbehörighet
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// Hämta användarnamnet från URL-parametern
if (isset($_GET['username'])) {
    $username = $_GET['username'];

    // Kontrollera att vi inte försöker ta bort den inloggade administratören
    if ($username !== $_SESSION['username']) {
        // Ta bort administratören från admin-tabellen
        deleteAdmin($username); // Funktion för att ta bort administratören från admin-tabellen
        header("Location: admins.php"); // Om borttagning lyckades, omdirigera till adminlistan
        exit();
    } else {
        // Om försöker ta bort sig själv, ge ett felmeddelande
        echo "Du kan inte ta bort den inloggade administratören.";
    }
} else {
    echo "Användarnamn saknas.";
}

?>
