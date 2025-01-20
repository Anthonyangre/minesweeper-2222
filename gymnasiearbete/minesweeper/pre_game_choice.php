<?php
session_start();
require_once '../assets/functions.php';
$username = $_SESSION['userid'];
if (!isset($_SESSION['userid'])) {
    echo "du är inte välkommen";
    header('Location: ../index.php');
}

$stats = getUserPoints();
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../style.css">
    <script type="text/javascript" src="../java.js"></script>
</head>
<body>

<header> 
    
        <div class="menu-container" onclick="toggleMenu(this)">
            <div class="hamburger-menu">
                <div class="bar1"></div>
                <div class="bar2"></div>
                <div class="bar3"></div>
            </div>

            <!-- Rubrik som glider in från vänster när knappen klickas -->
            <div class="menu-title">Navigation</div>

            <!-- Dropdown-menyn som visas vid klick -->
            <div class="dropdown-menu">
            <a href="../assets/logout.php" class="dropdown-item" onclick="return confirm('Är du säker på att du vill logga ut och gå till första sidan?');">Hem</a> 
            <a href="pre_game_choice.php" class="dropdown-item" onclick="return confirm('Är du säker att du vill gå till spelmenyn?');">Meny</a> 
            <a href= "leaderboard.php"class="dropdown-item">Leaderboard </a>
               

            </div>
        </div>

        <h3 class="rainbow-text">Minesweeper</h3> <!-- Välkomsttext med regnbågsfärg -->
        <div class="konto" onclick="togglekonto(this)">
        <?php echo htmlspecialchars($username) . "<p id='arrow'>🢓</p>"; ?>
    <div class="konto-dropdown">
        <ul>
            <li class="konto-item"><a href="../assets/logout.php"onclick="return confirm('Är du säker på att du vill logga ut och gå till första sidan?');" >Logga ut</a></li>
            <li class="konto-item"><a href="profil.php">Profil</a></li>
        </ul>
    </div>
</div>
</header>
<div class="back">
        <div class="choicelinks">

             <a href="index.php"><p>🎮 </p> Singleplayer</a>
             <a href="forum/index.php"><p>🗣️ </p>  Forum</a>
             
      </div>
<table id="stats">

    <tr>
        <th>Poäng</th>
        <th>Vinster</th>
        <th>Förluster</th>
    </tr>
    <tr>
        <td><?php echo htmlspecialchars($stats['points'] * 100); ?></td>
        <td><?php echo htmlspecialchars($stats['wins']); ?></td>
        <td><?php echo htmlspecialchars($stats['lose']); ?></td>
    </tr>
</table>
</div>
</body>
</html>