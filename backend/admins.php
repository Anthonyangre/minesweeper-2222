<?php
session_start();
require_once '../assets/functions.php'; // Inkludera funktionerna

// Kontrollera om användaren är inloggad och har administratörsbehörighet
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php"); // Om inte inloggad som admin, omdirigera till login-sidan
    exit();
}

// Hämta alla administratörer från admin-tabellen
$admins = getAllAdmins(); // Funktion för att hämta alla administratörer från admin-tabellen

?>

<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="utf-8">
    <title>Adminlista</title>
    <link href="../css/forum.css?v=1.0" rel="stylesheet" type="text/css">
</head>
<body>
<li><a href="users.php">Användarlistan</a>?</li> <!-- Länk till användarlistan -->

<li>Vill du <a href="../logout.php">logga ut</a>?</li> <!-- Länk för att logga ut -->

    <h1>Adminlista</h1> <!-- Rubrik för adminlistan -->

    <!-- Tabell som visar administratörens användarnamn, namn och en "Ta bort"-knapp -->
    <table id="adminList">
        <tr>
            <th>Användarnamn</th> <!-- Kolumn för användarnamn -->
            <th>Namn</th> <!-- Kolumn för namn -->
            <th>Ta bort administratör</th> <!-- Kolumn för att ta bort administratör -->
        </tr>

        <?php
        // Loopar igenom varje admin i listan och skapar en rad i tabellen för varje admin
        while ($admin = $admins->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($admin['username']) . "</td>"; // Visar administratörens användarnamn
            echo "<td>" . htmlspecialchars($admin['name']) . "</td>"; // Visar administratörens namn
            // Länk för att ta bort administratören, med en bekräftelse innan
            echo "<td><a href='delete_admin.php?username=" . urlencode($admin['username']) . "' onclick='return confirm(\"Är du säker på att du vill ta bort denna administratör?\");'>Ta bort</a></td>";
            echo "</tr>";
        }
        ?>
    </table>
</body>
</html>
