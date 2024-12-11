<?php
// Aktivera felrapportering för debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Starta sessionen
session_start();
require_once '../assets/functions.php';

// Kontrollera om användaren är inloggad och om de är administratör
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

// Om administratören söker efter en användare
if (isset($_GET['username'])) {
    $username = $_GET['username']; // Hämta användarnamnet från GET-parametern
} else {
    header("Location: users.php"); // Om inget användarnamn är angivet, gå tillbaka till användarlistan
    exit();
}

// Hämta användardata
$userData = getUserData($username);

// Kontrollera om användardata finns
if (!$userData) {
    echo "<p>Användardata hittades inte.</p>";
    exit();
}

// Hantera formuläret för att uppdatera användardata
$errors = [];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'] ?? $userData['name'];
    $email = $_POST['email'] ?? $userData['email'];
    $password = $_POST['password'] ?? '';

    // Validera fälten
    if (empty($name) || empty($email)) {
        $errors[] = 'Namn och e-post är obligatoriska.';
    }

    // Uppdatera användardata
    if (!empty($password)) {
        // Hasha lösenordet om det har angetts
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        updateUserByAdmin($name, $email, $passwordHash, $username); // Använd den nya admin-funktionen
    } else {
        // Uppdatera utan att ändra lösenordet
        updateUserByAdmin($name, $email, null, $username); // Använd den nya admin-funktionen
    }

    // Om uppdateringen lyckas, omdirigera till användarlistan
    header('Location: users.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="UTF-8">
    <title>Ändra användardata</title>
    <link href="../css/forum.css?v=1.0" rel="stylesheet" type="text/css">
</head>
<body>

<nav>
    <ul>
        <li><a href="users.php">Tillbaka till användarlistan</a></li>
    </ul>
</nav>

<h1>Ändra användardata för <?php echo htmlspecialchars($userData['username']); ?></h1>
<div class="center-container">
    <form method="post" action="">
        <table>
            <tr>
                <th>Namn</th>
                <td><input type="text" name="name" value="<?php echo htmlspecialchars($userData['name']); ?>"></td>
            </tr>
            <tr>
                <th>E-post</th>
                <td><input type="email" name="email" value="<?php echo htmlspecialchars($userData['email']); ?>"></td>
            </tr>
            <tr>
                <th>Lösenord</th>
                <td><input type="password" name="password" placeholder="Fyll i om du vill ändra lösenord"></td>
            </tr>
        </table>

        <input type="submit" value="Uppdatera">
    </form>

    <!-- Visa eventuella felmeddelanden -->
    <?php if (!empty($errors)): ?>
        <div style="color: red;">
            <?php foreach ($errors as $error): ?>
                <p><?php echo htmlspecialchars($error); ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

</body>
</html>
