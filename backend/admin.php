<?php
session_start(); // Startar en ny session eller återanvänder en befintlig session

require_once '../assets/functions.php'; // Inkludera functions.php från assets-mappen

// Om formuläret skickas via POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username']; // Hämtar användarnamn från formuläret
    $password = $_POST['password']; // Hämtar lösenord från formuläret

    // Hämta admin-data från databasen
    $admin = getAdminData($username); // Hämtar administratörens data baserat på användarnamn

    // Om admin finns i databasen och lösenordet är korrekt
    if ($admin && password_verify($password, $admin['password'])) {
        echo "Inloggning lyckades!"; // Om inloggning lyckades
        // Sätt sessionens variabler för användarnamn och roll
        $_SESSION['username'] = $admin['username'];
        $_SESSION['role'] = $admin['role'];
        // Omdirigera till användarpanelen (users.php)
        header("Location: users.php");
        exit();
    } else {
        echo "Inloggning misslyckades!"; // Om inloggningen misslyckades
        $error_message = "<p>Fel användarnamn eller lösenord</p>"; // Sätt felmeddelande
    }
}

?>

<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="UTF-8">
    <a href="../index.php">Gå tillbaka</a> <!-- Länk för att gå tillbaka till startsidan -->
    <title>Admin Login</title>
</head>
<body>
    <h2>Admin Login</h2> <!-- Rubrik för login-sidan -->
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>"> <!-- Formulär för inloggning -->
        <label for="username">Användarnamn:</label> <!-- Etikett för användarnamn -->
        <input type="text" id="username" name="username" required><br><br> <!-- Fält för användarnamn -->

        <label for="password">Lösenord:</label> <!-- Etikett för lösenord -->
        <input type="password" id="password" name="password" required><br><br> <!-- Fält för lösenord -->

        <input type="submit" value="Logga in"> <!-- Skicka-knapp -->
    </form>

    <?php
    if (isset($error_message)) { // Om det finns ett felmeddelande, visa det
        echo $error_message;
    }
    ?>
</body>
</html>
