<?php
session_start(); // Startar sessionen om den inte redan är startad

session_unset(); // Tar bort alla sessionvariabler

session_destroy(); // Förstör sessionen helt

header('Location: ../index.php'); // Omdirigerar användaren till startsidan
exit; // Säkerställer att inga ytterligare skript körs efter omdirigeringen
?>