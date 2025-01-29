<?php
$sql = new mysqli("localhost", "Minesweeper", "Minesweeper", "Minesweeper");

require_once 'assets/functions2.php';
require_once '../../dbhs.php';


session_start(); 

if (!isset($_SESSION['userid'])) {
    echo "Du måste vara inloggad för att komma åt den här sidan.";
    header('Location: ../../index.php');
} else {
    $username = $_SESSION['userid'];
}



$records2 = getporumPosts();

?>

<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="utf-8">
    <title>Forum</title>

    <link rel="stylesheet" href="../../style.css">
    <link rel="stylesheet" href="../style.css">
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
            <a href= "leaderboard.php"class="dropdown-item">Leaderboard </a>
            <a href="pre_game_choice.php" class="dropdown-item" onclick="return confirm('Är du säker att du vill gå till spelmenyn?');">Spelmeny</a>
            <a href="../assets/logout.php#regler"onclick="return confirm('Är du säker på att du vill logga ut och gå till första sidan?');" class="dropdown-item">Regler</a>  <!-- Länk till regler-sektionen på index.php -->
            <a href="../assets/logout.php#info"onclick="return confirm('Är du säker på att du vill logga ut och gå till första sidan?');"  #regler" class="dropdown-item">Info</a>  <!-- Länk till regler-sektionen på index.php -->


            </div>
        </div>

        <h3 class="rainbow-text">Minesweeper</h3> <!-- Välkomsttext med regnbågsfärg -->
        <div class="konto" onclick="togglekonto(this)"><?php
// Define the path to the profile picture
$profilePicturePath = '../uploads/' . $_SESSION["userid"] . '_picture.jpg';

// Check if the profile picture exists
if (file_exists($profilePicturePath)) {
    echo "<img class='bild' src='" . $profilePicturePath . "' alt='Profile Picture'>";
}
?>
        <?php echo htmlspecialchars($username) . "<p id='arrow'>🢓</p>"; ?>   <!-- skriver ut användarnamnet i konto delen samt pilen som kan ändra riktning när man trycker på kanppen -->
    <div class="konto-dropdown">
        <ul>
            <li class="konto-item"><a href="../../assets/logout.php"onclick="return confirm('Är du säker på att du vill logga ut och gå till förtsa sidan?');" >Logga ut</a></li>
            <li class="konto-item"><a href="../profil.php">Profil</a></li>
            
        </ul>
    </div>
</div>


    
    </header>


  <form name="nyppost" method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
      <table class="yes">
          <tr >
              <td> <?php echo "<p>Skapa ett forum genom:<span class='username'> $username</span></p>"; ?>
              
                  <textarea class="textFiled" name="title" rows="8" id="title1" placeholder="Title:"></textarea>
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
      <?php if (!empty($records2)): ?>
          <?php foreach ($records2 as $row_Recordset1): ?>
              <tr>
                  <td>
                  <?php
// Define the path to the profile picture
$profilePicturePath = '../uploads/' . htmlspecialchars($row_Recordset1['username']) . '_picture.jpg';

// Check if the profile picture exists
if (file_exists($profilePicturePath)) {
    echo "<div class='textdiv'>" . "<strong>" . "<img class='forum_bild' src='" . $profilePicturePath . "' alt='Profile Picture'>" . htmlspecialchars($row_Recordset1['username']) . "</strong>" . "</div>";
} else { echo "<strong>" . htmlspecialchars($row_Recordset1['username']) . "</strong>";}
 ?>
                      <!-- Link to search page for user's posts -->
                      <a href="forum.php?id=<?php echo htmlspecialchars($row_Recordset1['id']); ?>">
                      <div id="title"> <?php echo nl2br(htmlspecialchars_decode($row_Recordset1['title'])); ?></div>
                      </a>
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