<?php 
// Startar session för att hålla koll på användaren
session_start();
if (!isset($_SESSION['userid'])) { // Kollar om användaren är inloggad
    echo "du är inte välkommen";
    header('Location: ../index.php'); // Skickar till startsidan om ej inloggad
}
require_once '../dbhs.php'; // Laddar databasanslutning
require_once '../assets/uppgifter.php'; // Laddar fil för användaruppgifter
?>

<html lang="en">
<head>
    <meta charset="UTF-8"> <!-- Sätter teckenkodning -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Gör sidan responsiv -->
    <link rel="stylesheet" href="../style.css"> <!-- Laddar global CSS -->
    <link rel="stylesheet" href="style.css"> <!-- Laddar lokal CSS -->
    <script type="text/javascript" src="../java.js"></script> <!-- Laddar JavaScript -->
    <title>Profil</title> <!-- Sidans titel -->
</head>
<body>
<header> 
    <!-- Hamburgermeny med navigering -->
    <div class="menu-container" onclick="toggleMenu(this)">
        <div class="hamburger-menu">
            <div class="bar1"></div>
            <div class="bar2"></div>
            <div class="bar3"></div>
        </div>
        <div class="menu-title">Navigation</div> <!-- Menytitel -->
        <div class="dropdown-menu"> <!-- Dropdown-länkar -->
            <a href="pre_game_choice.php" class="dropdown-item" onclick="return confirm('Är du säker att du vill gå till spelmenyn?');">Spelmeny</a>
            <a href="leaderboard.php" class="dropdown-item">Topplista</a>
            <a href="../assets/logout.php#regler" onclick="return confirm('Är du säker på att du vill logga ut och gå till första sidan?');" class="dropdown-item">Regler</a>
            <a href="../assets/logout.php#info" onclick="return confirm('Är du säker på att du vill logga ut och gå till första sidan?');" class="dropdown-item">Info</a>
        </div>
    </div>

    <h3 class="rainbow-text">Minesweeper</h3> <!-- Sidtitel med regnbågseffekt -->
    <!-- Kontosektion med användarnamn och profilbild -->
    <div class="konto" onclick="togglekonto(this)">
        <?php
        $profilePicturePath = 'uploads/' . $_SESSION["userid"] . '_picture.jpg'; // Sökväg till profilbild
        if (file_exists($profilePicturePath)) { // Kollar om bilden finns
            echo "<img class='bild' src='" . $profilePicturePath . "' alt='Profile Picture'>";
        }
        ?>
        <?php echo htmlspecialchars($username) . "<p id='arrow'>🢓</p>"; ?> <!-- Visar användarnamn och pil -->
        <div class="konto-dropdown"> <!-- Dropdown för konto -->
            <ul>
                <li class="konto-item"><a href="../assets/logout.php" onclick="return confirm('Är du säker på att du vill logga ut och gå till första sidan?');">Logga ut</a></li>
            </ul>
        </div>
    </div>
</header>
    <div id="wrapper">
    <!-- Formulär för att ändra användaruppgifter -->
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" class="profil-form" enctype="multipart/form-data">
        <fieldset>
            <h3>Redigera dina användaruppgifter</h3> <!-- Titel för formuläret -->
            <h3>Användarnamn: <?php echo htmlspecialchars($username); ?></h3> <!-- Visar användarnamn -->
            <div class="form-group3">
                <label for="profile_picture">Profilbild</label> <!-- Etikett för profilbild -->
                <?php
                $profilePicturePath = 'uploads/' . $_SESSION["userid"] . '_picture.jpg'; // Sökväg till profilbild
                if (file_exists($profilePicturePath)) { // Kollar om bilden finns
                    echo "<img class='profil_bild' src='" . $profilePicturePath . "' alt='Profile Picture'>";
                }
                ?>
                <input type="file" id="profile_picture" name="profile_picture" accept="image"> <!-- Fält för att ladda upp bild -->
            </div>

            <div class="form-group2">
                <label for="email3">Email</label> <!-- Etikett för e-post -->
                <input type="mail" id="email3" name="email3" value="<?php echo htmlspecialchars($email); ?>"> <!-- Fält för e-post -->
            </div>

            <div class="form-group2">
                <label for="name3">Namn</label> <!-- Etikett för namn -->
                <input type="text" id="name3" name="name3" value="<?php echo htmlspecialchars($name); ?>"> <!-- Fält för namn -->
            </div>

            <div class="form-group2">
                <label for="password3">Lösenord</label> <!-- Etikett för lösenord -->
                <input type="password" id="password3" name="password3" placeholder="•••••••••"> <!-- platshållare för lösenord -->
            </div>
            <?php
            // Visar felmeddelanden om det finns några
            if (!empty($errors)) {
                echo '<ul>';
                foreach ($errors as $error) {
                    echo '<li>' . htmlspecialchars($error) . '</li>';
                }
                echo '</ul>';
            }
            ?>
        </fieldset>

        <div class="form-actions">
            <input type="submit" name="Ändra" value="Ändra" class="button1"> <!-- Skicka-knapp -->
        </div>
    </form>
    </div>


</body>
</html>