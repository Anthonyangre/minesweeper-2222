<?php
//infogar funktionalitet för inloggningen 
session_start();
if (!isset($_SESSION['userid'])) {
    echo "du är inte välkommen";
    header('Location: ../index.php');
}
require_once '../dbhs.php';
require_once '../assets/functions.php';
$username = $_SESSION['userid'];
$records = getScore();

?> 
<html lang="sv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minesweeper login</title>
    <link rel="stylesheet" href="../style.css">
    <script type="text/javascript" src="../java.js"></script>
   
</head>
<body>
<header>   <div class="menu-container" onclick="toggleMenu(this)">
            <div class="hamburger-menu">
                <div class="bar1"></div>
                <div class="bar2"></div>
                <div class="bar3"></div>
            </div>

            <!-- Rubrik som glider in från vänster när knappen klickas -->
            <div class="menu-title">Navigation</div>

            <!-- Dropdown-menyn som visas vid klick -->
            <div class="dropdown-menu">
            <a href="../assets/logout.php" class="dropdown-item" onclick="return confirm('Är du säker på att du vill logga ut och gå till förtsa sidan?');">Hem</a>
            <a href="leaderboard.php" class="dropdown-item">Leaderboard</a>  <!-- Länk till regler-sektionen på index.php -->
            <a href="pre_game_choice.php" class="dropdown-item" onclick="return confirm('Är du säker att du vill gå till spelmenyn?');">Spelmeny</a>
</div>
        </div>

        <h3 class="rainbow-text">Leaderboard</h3> <!-- Välkomsttext med regnbågsfärg -->
        <div class="konto" onclick="togglekonto(this)">
    <?php echo htmlspecialchars($username); ?>
    <div class="konto-dropdown">
        <ul>
            <li class="konto-item"><a href="../assets/logout.php" onclick="return confirm('Är du säker på att du vill logga ut och gå till förtsa sidan?');">Logga ut</a></li>
            <li class="konto-item"><a href="profil.php">Profil</a></li>
        </ul>
    </div>
</div>



<!-- Länkar för inloggning och registrering --></header>

     

   </header>

   <h2>Leaderboard</h2>

    <!-- Tabell som visar forumets inlägg -->
    <table id="dbres">
    <tr>
        <th>Namn</th>
        <th>Poäng</th>
        <th>Vintster</th>
        <th>Förluster</th>
    </tr>
    <!-- Loopa igenom alla inlägg och visa dem i tabellen -->
    <?php while ($row = $records->fetch_assoc()): ?>
        <tr>
            <!-- Visa användarnamnet för varje inlägg -->
            <td><?php echo htmlspecialchars($row['username'], ENT_QUOTES, 'UTF-8'); ?></td>
            
            <!-- Visa meddelandet och återställ HTML-tecken korrekt -->
            <td><?php echo htmlspecialchars($row['points']*100, ENT_QUOTES, 'UTF-8'); ?></td>
            
            <!-- Visa när meddelandet skickades -->
            <td><?php echo htmlspecialchars($row['wins'], ENT_QUOTES, 'UTF-8'); ?></td>
            <td><?php echo htmlspecialchars($row['lose'], ENT_QUOTES, 'UTF-8'); ?></td>
           
        </tr>
    <?php endwhile; ?>
    </table>
</body>