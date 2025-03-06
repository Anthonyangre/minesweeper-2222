<?php
// Startar en PHP-session för att hantera användarens inloggningsstatus och lagra sessionsdata
session_start();

// Kontrollerar om användaren är inloggad genom att verifiera att 'userid' finns i sessionen
if (!isset($_SESSION['userid'])) {
    // Om användaren inte är inloggad visas medelendet
    echo "du är inte välkommen";
    // skcikar användaren till startsidan (index.php) 
    header('Location: ../index.php');
}

// Inkluderar databasanslutningsfilen för att få tillgång till databasen
require_once '../dbhs.php';
// Inkluderar en fil med användardefinierade funktioner, t.ex. för att hämta poäng
require_once '../assets/functions.php';

// Lagrar det inloggade användarnamnet från sessionen i en variabel för senare användning
$username = $_SESSION['userid'];

// Anropar funktionen getScore() för att hämta toppliste-data från databasen och lagrar resultatet
$records = getScore();

?> 
<html lang="sv">
<head>
    <!-- stödjer svenska tecken och andra specialtecken -->
    <meta charset="UTF-8">
    <!-- Gör hemsidan responsiv genom att anpassa bredden till enhetens skärm och sätta initial zoom till 1 -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Sätter sidans titel som visas i webbläsarfliken -->
    <title>Leaderboard</title>
    <!-- Länkar till en global stilmall (style.css) för generell design -->
    <link rel="stylesheet" href="../style.css">
    <!-- Länkar till en lokal stilmall (style.css) för specifik design av denna sida -->
    <link rel="stylesheet" href="style.css">
    <!-- Inkluderar ett JavaScript-skript (java.js) för interaktivitet, t.ex. menyfunktioner -->
    <script type="text/javascript" src="../java.js"></script>
   
</head>
<body>
<header>   
    <!-- Skapar en behållare för hamburgermenyn med ett onclick-event som triggar toggleMenu-funktionen -->
    <div class="menu-container" onclick="toggleMenu(this)">
        <!-- Definierar hamburgermenyns visuella element med tre horisontella streck -->
        <div class="hamburger-menu">
            <div class="bar1"></div>
            <div class="bar2"></div>
            <div class="bar3"></div>
        </div>

        <!-- En rubrik som glider in från vänster när hamburgermenyn klickas, används som menyindikator -->
        <div class="menu-title">Navigation</div>

        <!-- Dropdown-meny som visas när hamburgermenyn klickas, innehåller navigeringslänkar -->
        <div class="dropdown-menu">
            <!-- Länk till spelmenyn med en bekräftelsedialog för att säkerställa användarens val -->
            <a href="pre_game_choice.php" class="dropdown-item" onclick="return confirm('Är du säker att du vill gå till spelmenyn?');">Spelmeny</a>
            <!-- Länk till regler-sektionen på index.php efter utloggning, med bekräftelsedialog -->
            <a href="../assets/logout.php#regler" onclick="return confirm('Är du säker på att du vill logga ut och gå till första sidan?');" class="dropdown-item">Regler</a>
            <!-- Länk till info-sektionen på index.php efter utloggning, med bekräftelsedialog -->
            <a href="../assets/logout.php#info" onclick="return confirm('Är du säker på att du vill logga ut och gå till första sidan?');" class="dropdown-item">Info</a>
        </div>
    </div>

    <!-- En rubrik med regnbågseffekt som fungerar som sidans titel -->
    <h3 class="rainbow-text">Topplista</h3>

    <!-- Skapar en konto-sektion med onclick-event för att visa/dölja en dropdown-meny -->
    <div class="konto" onclick="togglekonto(this)">
        <?php
        // Definierar sökvägen till användarens profilbild baserat på användarnamnet
        $profilePicturePath = 'uploads/' . $_SESSION["userid"] . '_picture.jpg';

        // Kontrollerar om profilbilden finns på servern med file_exists
        if (file_exists($profilePicturePath)) {
            // Om bilden finns, visas den med klassen 'bild' för styling
            echo "<img class='bild' src='" . $profilePicturePath . "' alt='Profile Picture'>";
        }
        ?>
        <!-- Visar användarnamnet från sessionen med htmlspecialchars för att skydda mot XSS-attacker, samt en pil för dropdown-indikering -->
        <?php echo htmlspecialchars($username) . "<p id='arrow'>🢓</p>"; ?>
        
        <!-- Dropdown-meny för kontoalternativ, initialt dold -->
        <div class="konto-dropdown">
            <ul>
                <!-- Länk för att logga ut med bekräftelsedialog -->
                <li class="konto-item"><a href="../assets/logout.php" onclick="return confirm('Är du säker på att du vill logga ut och gå till första sidan?');">Logga ut</a></li>
                <!-- Länk till användarens profilsida -->
                <li class="konto-item"><a href="profil.php">Profil</a></li>
            </ul>
        </div>
    </div>
</header>

     
    <!-- Tabell som visar topplistan med användardata -->
    <table id="dbres">
        <!-- Tabellhuvud med kolumnrubriker -->
        <tr>
            <th>Användarnamn</th>
            <th>Poäng</th>
            <th>Vinster</th>
            <th>Förluster</th>
        </tr>
        <!-- Loopar igenom varje rad i resultatuppsättningen från getScore() -->
        <?php while ($row = $records->fetch_assoc()): ?>
            <tr>
                <!-- Visar användarnamnet från databasen, skyddat mot XSS med htmlspecialchars -->
                <td><?php echo htmlspecialchars($row['username'], ENT_QUOTES, 'UTF-8'); ?></td>
                
                <!-- Visar poängen multiplicerat med 100 för att skala upp värdet, skyddat mot XSS -->
                <td><?php echo htmlspecialchars($row['points']*100, ENT_QUOTES, 'UTF-8'); ?></td>
                
                <!-- Visar antalet vinster från databasen, skyddat mot XSS -->
                <td><?php echo htmlspecialchars($row['wins'], ENT_QUOTES, 'UTF-8'); ?></td>
                
                <!-- Visar antalet förluster från databasen, skyddat mot XSS -->
                <td><?php echo htmlspecialchars($row['lose'], ENT_QUOTES, 'UTF-8'); ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>