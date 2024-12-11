<?php

session_start();

require_once '../assets/functions.php'; // Säkerställ att denna fil finns för databasanslutning

// Om formuläret för att skapa admin skickas
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Hämta och sanera användardata
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $name = trim($_POST['name']);  // Lägg till namn
    $role = 'admin'; // Sätt rollen till admin för den nya användaren

    // Kontrollera om användarnamn, lösenord eller namn är tomt
    if (empty($username) || empty($password) || empty($name)) {
        $error_message = "<p>Fyll i användarnamn, lösenord och namn.</p>";
    } else {
        // Kontrollera om användaren redan finns
        $existingAdmin = getAdminData($username); // Vi kollar om användarnamnet redan finns
        if ($existingAdmin) {
            $error_message = "<p>Användarnamnet finns redan i systemet.</p>";
        } else {
            // Hasha lösenordet innan det sparas
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Spara användaren i databasen
            try {
                require_once '../assets/config/db.php';
                $forum = getDbConnection(); // Hämta databasanslutningen
                $stmt = $forum->prepare("INSERT INTO admin (username, password, name, role) VALUES (?, ?, ?, ?)");
                $stmt->bindParam(1, $username);
                $stmt->bindParam(2, $hashed_password); // Spara det hashade lösenordet
                $stmt->bindParam(3, $name);   // Lägg till namn här
                $stmt->bindParam(4, $role);

                if ($stmt->execute()) {
                    echo "<p>Admin-användare skapad!</p>";
                } else {
                    $error_message = "<p>Kunde inte skapa admin.</p>";
                }
            } catch (Exception $e) {
                $error_message = "<p>Fel: " . $e->getMessage() . "</p>";
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="UTF-8">
    <title>Skapa Admin</title>
</head>
<body>
    <a href="../index.php">Gå tillbaka</a>
    <h2>Skapa Admin</h2>
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
        <label for="username">Användarnamn:</label>
        <input type="text" id="username" name="username" required><br><br>

        <label for="name">Namn:</label>   <!-- Lägg till inputfält för namn -->
        <input type="text" id="name" name="name" required><br><br>

        <label for="password">Lösenord:</label>
        <input type="password" id="password" name="password" required><br><br>

        <input type="submit" value="Skapa Admin">
    </form>

    <?php
    if (isset($error_message)) {
        echo $error_message;
    }
    ?>
</body>
</html>
