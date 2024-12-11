<?php
session_start();
require_once '../assets/functions.php'; // Inkludera funktionerna

// Kontrollera om användaren är inloggad och har administratörsbehörighet
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// Kontrollera om användarnamn är skickat via GET
if (isset($_GET['username'])) {
    $username = $_GET['username'];

    // Ta bort alla inlägg för denna användare
    if (deleteUserPosts($username)) {
        echo "Alla inlägg för användaren $username har tagits bort.";
    } else {
        echo "Det gick inte att ta bort inlägg för användaren $username.";
    }
} else {
    echo "Användarnamn saknas.";
}


?>
