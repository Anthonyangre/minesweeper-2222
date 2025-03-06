<?php
// Skapar en anslutning till databasen "Minesweeper"
$sql = new mysqli("localhost", "Minesweeper", "Minesweeper", "Minesweeper");

// Inkluderar funktioner och databasanslutning från andra filer
require_once 'assets/functions2.php';
require_once '../../dbhs.php';

// Startar en session för att hantera inloggning
session_start(); 

// Kollar om användaren är inloggad
if (!isset($_SESSION['userid'])) {
    echo "Du måste vara inloggad för att komma åt den här sidan.";
    header('Location: ../../index.php'); // Omdirigerar till startsidan om inte inloggad
} else {
    $username = $_SESSION['userid']; // Sparar användarnamnet från sessionen
}

// Hämtar forumsinlägg från databasen
$records2 = getporumPosts();

?>

<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="utf-8">
    <title>Forum</title>
    <link rel="stylesheet" href="../../style.css"> <!-- Laddar global CSS -->
    <link rel="stylesheet" href="../style.css">    <!-- Laddar lokal CSS -->
</head>
<body>
<header> 
    <!-- Hamburgermeny med dropdown för navigering -->
    <div class="menu-container" onclick="toggleMenu(this)">
        <div class="hamburger-menu">
            <div class="bar1"></div>
            <div class="bar2"></div>
            <div class="bar3"></div>
        </div>
        <div class="menu-title">Navigation</div>
        <div class="dropdown-menu">
            <a href="../pre_game_choice.php" class="dropdown-item" onclick="return confirm('Är du säker att du vill gå till spelmenyn?');">Spelmeny</a>
            <a href="../leaderboard.php" class="dropdown-item">Topplista</a>
            <a href="../../assets/logout.php#regler" onclick="return confirm('Är du säker på att du vill logga ut och gå till första sidan?');" class="dropdown-item">Regler</a>
            <a href="../../assets/logout.php#info" onclick="return confirm('Är du säker på att du vill logga ut och gå till första sidan?');" class="dropdown-item">Info</a>
        </div>
    </div>

    <!-- Sidans titel med regnbågseffekt -->
    <h3 class="rainbow-text">Forum</h3>

    <!-- Kontosektion som visar användarnamn och profilbild -->
    <div class="konto" onclick="togglekonto(this)">
        <?php
        // Sätter sökväg till användarens profilbild
        $profilePicturePath = '../uploads/' . $_SESSION["userid"] . '_picture.jpg';
        // Kollar om profilbilden finns och visar den om den gör det
        if (file_exists($profilePicturePath)) {
            echo "<img class='bild' src='" . $profilePicturePath . "' alt='Profile Picture'>";
        }
        ?>
        <!-- Skriver ut användarnamnet och en pil för dropdown -->
        <?php echo htmlspecialchars($username) . "<p id='arrow'>🢓</p>"; ?>
        <div class="konto-dropdown">
            <ul>
                <li class="konto-item"><a href="../../assets/logout.php" onclick="return confirm('Är du säker på att du vill logga ut och gå till första sidan?');">Logga ut</a></li>
                <li class="konto-item"><a href="../profil.php">Profil</a></li>
            </ul>
        </div>
    </div>
</header>

<!-- Formulär för att skapa ett nytt foruminlägg -->
<form name="nyppost" method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
    <table class="yes">
        <tr>
            <td>
                <?php echo "<p>Skapa ett forum genom:<span class='username'> $username</span></p>"; ?>
                <!-- Textarea för att skriva titel på inlägget -->
                <textarea class="textFiled" name="title" rows="8" id="title1" placeholder="Title:"></textarea>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <input type="submit" name="skicka" id="skicka" value="Skicka"> <!-- Skicka-knapp -->
            </td>
        </tr>
    </table>
    <input type="hidden" name="MM_insert" value="nypost"> <!-- Dold input för att markera ny post -->
</form>

<!-- Tabell som visar befintliga foruminlägg -->
<table id="dbres" class="yes">
    <?php if (!empty($records2)): ?> <!-- Kollar om det finns inlägg -->
        <?php foreach ($records2 as $row_Recordset1): ?> <!-- Loopar igenom alla inlägg -->
            <tr>
                <td>
                    <?php
                    // Sätter sökväg till användarens profilbild för varje inlägg
                    $profilePicturePath = '../uploads/' . htmlspecialchars($row_Recordset1['username']) . '_picture.jpg';
                    // Visar profilbild och användarnamn om bilden finns, annars bara användarnamn
                    if (file_exists($profilePicturePath)) {
                        echo "<div class='textdiv'>" . "<strong>" . "<img class='forum_bild' src='" . $profilePicturePath . "' alt='Profile Picture'>" . htmlspecialchars($row_Recordset1['username']) . "</strong>" . "</div>";
                    } else {
                        echo "<strong>" . htmlspecialchars($row_Recordset1['username']) . "</strong>";
                    }
                    ?>
                    <!-- Länk till enskilt inlägg baserat på ID -->
                    <a href="forum.php?id=<?php echo htmlspecialchars($row_Recordset1['id']); ?>">
                        <div id="title"><?php echo nl2br(htmlspecialchars_decode($row_Recordset1['title'])); ?></div>
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?> <!-- Om det inte finns några inlägg -->
        <tr>
            <td colspan="3">Inga meddelanden att visa.</td>
        </tr>
    <?php endif; ?>
</table>

<script src="../../java.js"></script> <!-- Laddar JavaScript för interaktivitet -->
</body>
</html>