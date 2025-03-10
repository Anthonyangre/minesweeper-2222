<?php
// Skapar en anslutning till databasen med server, användarnamn, lösenord och databasnamn
$conn = new mysqli("localhost", "Minesweeper", "Minesweeper", "Minesweeper");

// Kollar om en session redan har startats, och startar en om det inte är gjort
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Kontrollerar om användaren är inloggad genom att kolla om 'userid' finns i sessionen
if (isset($_SESSION['userid'])) {
    // Hämtar användar-ID från sessionen och sparar det i en variabel
    $userid = $_SESSION['userid'];

    // Förbereder en SQL-fråga för att hämta användarens data från databasen
    $stmt = $conn->prepare("SELECT username, name, email FROM users WHERE username = ?");
    // Kopplar användar-ID till frågan för att göra den säker
    $stmt->bind_param("s", $userid);
    // Kör frågan mot databasen
    $stmt->execute();
    // Hämtar resultatet från frågan
    $result = $stmt->get_result();

    // Kollar om det finns några rader i resultatet
    if ($result->num_rows > 0) {
        // Hämtar användarens data som en array och sparar värdena i variabler
        $user = $result->fetch_assoc();
        $username = $user['username'];
        $name = $user['name'];
        $email = $user['email'];
    } else {
        // Lägger till ett felmeddelande om användardata inte kunde hittas
        $errors[] = "Användardata kunde inte hämtas.";
    }
    // Stänger SQL-frågan för att frigöra resurser
    $stmt->close();
} else {
    // Lägger till ett felmeddelande om användaren inte är inloggad
    $errors[] = "Du måste vara inloggad för att ändra dina uppgifter";
}

// Kollar om formuläret har skickats med POST-metoden
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Sätter sökvägen till mappen där uppladdade filer ska sparas
    $uploadDir = 'uploads/';

    // Kontrollerar om mappen finns, och skapar den om den inte gör det
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true); // Skapar mappen med rätt behörigheter
    }

    // Kollar om en fil har laddats upp och om det inte blev något fel
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
        // Sparar filinformationen i en variabel för enkel åtkomst
        $file = $_FILES['profile_picture'];

        // Bestämmer vilka filtyper som är tillåtna (bara JPEG)
        $allowedTypes = ['image/jpeg'];
        // Kollar filens faktiska typ med mime_content_type
        $fileType = mime_content_type($file['tmp_name']);
        // Om filtypen inte är tillåten läggs ett felmeddelande till
        if (!in_array($fileType, $allowedTypes)) {
            $errors[] = "Bara JPG filer är tillåtna.";
        }

        // Sätter en maxstorlek för filen (2 MB)
        $maxFileSize = 2 * 1024 * 1024; // 2 MB i byte
        // Kollar om filen är för stor och lägger till ett felmeddelande om den är det
        if ($file['size'] > $maxFileSize) {
            $errors[] = "Filen är för stor. Maxstorlek är 2 MB.";
        }

        // Om det inte finns några fel fortsätter processen
        if (empty($errors)) {
            // Hämtar filändelsen från det uppladdade filnamnet
            $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);
            // Sätter användarnamnet i sessionen (redundant här, redan satt tidigare)
            $_SESSION["userid"] = $username;
            // Skapar ett unikt filnamn baserat på användarnamnet och filändelsen
            $uniqueFilename = $username . "_picture." . $fileExtension;

            // Sätter hela sökvägen där filen ska sparas
            $profilePicturePath = $uploadDir . $uniqueFilename;

            // Försöker flytta den uppladdade filen till rätt plats på servern
            if (move_uploaded_file($file['tmp_name'], $profilePicturePath)) {
                // Om flytten lyckas görs inget mer här (kan t.ex. lägga till framgångsmeddelande)
            } else {
                // Lägger till ett felmeddelande om filen inte kunde flyttas
                $errors[] = "Kunde inte flytta den uppladdade filen.";
            }
        }
    } else {
        // Tom else-sats, kan användas för att hantera fall där ingen fil laddats upp
    }
}

?>