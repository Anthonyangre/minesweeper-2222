<?php
$sql = new mysqli("localhost", "Minesweeper", "Minesweeper", "Minesweeper");

require_once 'assets/functions.php';
require_once '../../dbhs.php';


session_start(); 

if (!isset($_SESSION['userid'])) {
    echo "Du måste vara inloggad för att komma åt den här sidan.";
    exit;
}

$username = $_SESSION['userid'];

$records = getForumPosts();

?>

<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="utf-8">
    <title>Forum</title>

    <link rel="stylesheet" href="../../style.css">
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
    <?php echo htmlspecialchars($username); ?>
    <div class="konto-dropdown">
        <ul>
            <li class="konto-item"><a href="../../assets/logout.php"onclick="return confirm('Är du säker på att du vill logga ut och gå till förtsa sidan?');" >Logga ut</a></li>
            <li class="konto-item"><a href="../profil.php">Profil</a></li>
            <li class="konto-item"><a href="pre_game_choice.php" onclick="return confirm('Är du säker att du vill gå till spelmenyn?');">Spelmeny</a> </li>
        </ul>
    </div>
</div>


    
    </header>


  <form name="nypost" method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
      <table class="yes">
          <tr >
              <td> <?php echo "<p>Skicka meddelande genom:<span class='username'> $username</span></p>"; ?>
              
                  <textarea class="textFiled" name="msg" rows="8" id="msg"></textarea>
              </td>
          </tr>
          <tr>
              <td colspan="2">
                  <input type="submit" name="skicka" id="skicka" value="Skicka">
              </td>
          </tr>
      </table>
      <input type="hidden" name="MM_insert" value="nypost">
  </form>

  <table id="dbres" class="yes">
      <?php if (!empty($records)): ?>
          <?php foreach ($records as $row_Recordset1): ?>
              <tr>
                  <td>
                    
                      <?php echo "<strong>" . htmlspecialchars($row_Recordset1['username']) . "</strong>"; ?> <?php echo "<small>" . htmlspecialchars($row_Recordset1['tid']) . "</small>"; ?>
                      <!-- Link to search page for user's posts -->
                      
                      <div id="message"> <?php echo nl2br(htmlspecialchars_decode($row_Recordset1['msg'])); ?></div>
                    
                </td>
              </tr>
          <?php endforeach; ?>
      <?php else: ?>
          <tr>
              <td colspan="3">Inga meddelanden att visa.</td>
          </tr>
      <?php endif; ?>

  </table>
  <script src="../../java.js"></script>
</body>
</html>