<?php
//infogar funktionalitet för inloggningen 
require_once '../dbhs.php';
$username = $_SESSION['username'];
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
              <a href="index.php"><div class="dropdown-item">Spelet</div></a> 
            <a href="leaderboard.php" class="dropdown-item">Leaderboard</a>  <!-- Länk till regler-sektionen på index.php -->
</div>
        </div>

        <h3 class="rainbow-text">Välkommen till Minesweeper</h3> <!-- Välkomsttext med regnbågsfärg -->
        <div class="konto"> <?php echo htmlspecialchars($username); ?></div>
<!-- Länkar för inloggning och registrering --></header>
<> 
     

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
    <?php while ($row = $records->fetch(PDO::FETCH_ASSOC)): ?>
        <tr>
            <!-- Visa användarnamnet för varje inlägg -->
            <td><?php echo htmlspecialchars($row['username'], ENT_QUOTES, 'UTF-8'); ?></td>
            
            <!-- Visa meddelandet och återställ HTML-tecken korrekt -->
            <td><?php echo nl2br(html_entity_decode($row['msg'], ENT_QUOTES, 'UTF-8')); ?></td>
            
            <!-- Visa när meddelandet skickades -->
            <td><?php echo htmlspecialchars($row['tid'], ENT_QUOTES, 'UTF-8'); ?></td>
            
            <!-- Ny kolumn: Länk till alla inlägg av användaren -->
            <td>
                <a href="search.php?username=<?php echo urlencode($row['username']); ?>">Visa alla inlägg</a>
            </td>
        </tr>
    <?php endwhile; ?>
    </table>
</body>