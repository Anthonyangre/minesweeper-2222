<?php
session_start();
require_once '../assets/functions.php'; // Inkludera funktionerna

// Kontrollera om användaren är inloggad och har administratörsbehörighet
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// Variabel för sökning och användardata
$searchResult = null;
$searchTerm = '';

// Hantera sökning via POST (när man skickar in formuläret)
if (isset($_POST['search'])) {
    $searchTerm = trim($_POST['search']);
    // Kontrollera om söktermen är tom
    if (!empty($searchTerm)) {
        // Hämta data för en specefik användare
        $searchResult = getUserData($searchTerm);
    }
}

// Hämta alla användare om ingen sökning är gjord
$users = $searchResult ? [$searchResult] : getAllUsers();

?>

<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="utf-8">
    <title>Användarlista</title>
    <link href="../css/forum.css?v=1.0" rel="stylesheet" type="text/css">
</head>
<body>
<li>Vill du <a href="../logout.php">logga ut</a>?</li>
<li><a href="admins.php">Admin lista</a>?</li>


    <h1>Användarlista</h1>

    <!-- Formulär för att söka efter användare -->
    <form method="POST" action="users.php">
        <label for="search">Sök efter användarnamn:</label>
        <input type="text" id="search" name="search" placeholder="Ange användarnamn" value="<?php echo htmlspecialchars($searchTerm); ?>">
        <input type="submit" value="Sök">
    </form>

    <!-- Tabell som visar användarens namn, e-post och "Ta bort"-knapp -->
    <table id="userList">
        <tr>
            <th>Användarnamn</th>
            <th>Namn</th>
            <th>E-post</th>
            <th>Ändra användarinfo</th>
            <th>Ta bort användare</th>
            <th>Ta bort användarens inlägg</th> <!-- Ny kolumn för att ta bort inlägg -->

        </tr>

        <?php
        // Om vi har sökresultat, visa endast den användaren
        if ($searchResult) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($searchResult['username']) . "</td>";
            echo "<td>" . htmlspecialchars($searchResult['name']) . "</td>";
            echo "<td>" . htmlspecialchars($searchResult['email']) . "</td>";
            echo "<td><a href='change_user.php?username=" . urlencode($searchResult['username']) . "'>Ändra</a></td>";
            echo "<td><a href='delete_user.php?username=" . urlencode($searchResult['username']) . "' onclick='return confirm(\"Är du säker på att du vill ta bort denna användare?\");'>Ta bort</a></td>";
            // Lägg till länk för att ta bort användarens foruminlägg
            echo "<td><a href='delete_user_posts.php?username=" . urlencode($searchResult['username']) . "' onclick='return confirm(\"Är du säker på att du vill ta bort alla foruminlägg för denna användare?\");'>Ta bort inlägg</a></td>";
            echo "</tr>";
        } else {
            // Om vi inte får en sökresultat, visa alla användare
            while ($row = $users->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['username']) . "</td>";
                echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                echo "<td><a href='change_user.php?username=" . urlencode($row['username']) . "'>Ändra</a></td>";
                echo "<td><a href='delete_user.php?username=" . urlencode($row['username']) . "' onclick='return confirm(\"Är du säker på att du vill ta bort denna användare?\");'>Ta bort</a></td>";
                // Lägg till länk för att ta bort användarens foruminlägg
                echo "<td><a href='delete_user_posts.php?username=" . urlencode($row['username']) . "' onclick='return confirm(\"Är du säker på att du vill ta bort alla foruminlägg för denna användare?\");'>Ta bort inlägg</a></td>";
                echo "</tr>";
            }
        }
        ?>
    </table>
</body>
</html>
