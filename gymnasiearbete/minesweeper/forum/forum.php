<?php
//sql connection till våran sql server genom minesweeper usern
$sql = new mysqli("localhost", "Minesweeper", "Minesweeper", "Minesweeper");
// de funktionerna som krävs för att forumet ska fungera och databasen.
require_once 'assets/functions.php';
require_once '../../dbhs.php';

//session start
session_start(); 
//om det finns ingen userid, och därför ingen user ska man bli utloggad och tagen till startsidan
if (!isset($_SESSION['userid'])) {
    echo "Du måste vara inloggad för att komma åt den här sidan.";
    header('Location: ../../index.php');
} else {
    // annars sätter vi variabeln username till userid
    $username = $_SESSION['userid'];
}


$username = $_SESSION['userid'];
// ta idet från urlen genom get
$id = isset($_GET['id']) ? intval($_GET['id']) : null;

// genom funktionen får vi posterna på forumet vars id är iden i urlen
$records = getForumPosts($id);
//kod för att få titlen av huvudforumet
function getForumTitle($id) {
    $conn = new mysqli("localhost", "Minesweeper", "Minesweeper", "Minesweeper");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $title = "Okänt Forum"; // om det finns inget huvudforum är det okänt.

    try {
        $sql = "SELECT title FROM pforum WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            $title = $row['title'];
        }

        $stmt->close();
        $conn->close();
    } catch (Exception $e) {
        error_log($e->getMessage());
    }

    return $title;
}
//variabel som innehållet titeln av forumet
$forumTitle = getForumTitle($id);
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
    <!-- menyn på vänstra sida av hemsidan -->
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
            <a href= "../leaderboard.php"class="dropdown-item">Topplista</a>
            <a href="../pre_game_choice.php" class="dropdown-item" onclick="return confirm('Är du säker att du vill gå till spelmenyn?');">Spelmeny</a>
            <a href="index.php" class="dropdown-item" onclick="return confirm('Är du säker att du vill gå tillbaka till startforumet?');">Forum</a>
            </div>
        </div>

        <h3 class="rainbow-text">Meddelanden</h3> <!-- Välkomsttext med regnbågsfärg -->
        <div class="konto" onclick="togglekonto(this)"><?php
// variabel för var profilbilden lagras.
$profilePicturePath = '../uploads/' . $_SESSION["userid"] . '_picture.jpg';

// kollar om den finns, om den gör det visar den bilden.
if (file_exists($profilePicturePath)) {
    echo "<img class='bild' src='" . $profilePicturePath . "' alt='Profile Picture'>";
}
?> <!-- user menyn visas vid klick av usernamet -->
        <?php echo htmlspecialchars($username) . "<p id='arrow'>🢓</p>"; ?>   <!-- skriver ut användarnamnet i konto delen samt pilen som kan ändra riktning när man trycker på kanppen -->
    <div class="konto-dropdown">
        <ul>
            <li class="konto-item"><a href="../../assets/logout.php"onclick="return confirm('Är du säker på att du vill logga ut och gå till förtsa sidan?');" >Logga ut</a></li>
            <li class="konto-item"><a href="../profil.php">Profil</a></li>
            
        </ul>
    </div>
</div>


    
    </header>

<!-- formen för att skriva meddelanden genom forumet -->
  <form name="nypost" method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) . '?id=' . urlencode($id); ?>">
      <table class="yes">
      
          <tr >
          
              <td> <?php echo "<strong>Forum: $forumTitle</strong>"; ?>
                <?php echo "<p>Skicka meddelande genom:<span class='username'> $username</span></p>"; ?>
              
                  <textarea class="textFiled" name="msg" rows="8" id="msg" placeholder="message"></textarea>
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
                    
                  <?php

$profilePicturePath = '../uploads/' . htmlspecialchars($row_Recordset1['username']) . '_picture.jpg';


if (file_exists($profilePicturePath)) {
    echo "<img class='forum_bild' src='" . $profilePicturePath . "' alt='Profile Picture'>";
}
?><?php echo "<strong class='user'>" . htmlspecialchars($row_Recordset1['username']) . "</strong>"; ?> <?php echo "<small>" . htmlspecialchars($row_Recordset1['tid']) . "</small>"; ?>
                      <!-- visar meddelanden genom  records -->
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