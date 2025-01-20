<?php 
session_start();
if (!isset($_SESSION['userid'])) {
    echo "du är inte välkommen";
    header('Location: ../index.php');
}
require_once '../dbhs.php';
require_once '../assets/uppgifter.php';

?>
    
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css">
    <script type="text/javascript" src="../java.js"></script>
    <title>Profil</title>
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
            <a href="../assets/logout.php" class="dropdown-item" onclick="return confirm('Är du säker på att du vill logga ut och gå till förtsa sidan?');">Hem</a> 
            <a href= "leaderboard.php"class="dropdown-item">Leaderboard </a>
                        

            </div>
        </div>

        <h3 class="rainbow-text">Minesweeper</h3> <!-- Välkomsttext med regnbågsfärg -->
        <div class="konto" onclick="togglekonto(this)">
    <?php echo htmlspecialchars($username); ?>
    <div class="konto-dropdown">
        <ul>
            <li class="konto-item"><a href="../assets/logout.php"onclick="return confirm('Är du säker på att du vill logga ut och gå till förtsa sidan?');" >Logga ut</a></li>
            <li class="konto-item"><a href="pre_game_choice.php" onclick="return confirm('Är du säker att du vill gå till spelmenyn?');">Spelmeny</a> </li>
        </ul>
    </div>
</div>
</header>
    <div id="wrapper">

<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" class="profil-form" enctype="multipart/form-data">
<fieldset>
     <h3>Redigera dina användar uppgifter</h3>
           
           
            <h4>Användarnamn: <?php echo htmlspecialchars(string: $username); ?></h4>
            <div class="form-group3">
            <label for="profile_picture">Profilbild</label>
            <?php
// Define the path to the profile picture
$profilePicturePath = 'uploads/' . $_SESSION["userid"] . '_picture.jpg';

// Check if the profile picture exists
if (file_exists($profilePicturePath)) {
    echo "<img class='profil_bild' src='" . $profilePicturePath . "' alt='Profile Picture'>";
}
?>
            <input type="file" id="profile_picture" name="profile_picture" accept="image">
            </div>
            

            <div class="form-group2">
            <label for="email3">Email</label>
            <input type="mail" id="email3" name="email3" value="<?php echo htmlspecialchars($email); ?>" >
            </div>

    
      
           
            <div class="form-group2">
            <label for="name3">Name</label>
            <input type="text" id="name3" name="name3" value="<?php echo htmlspecialchars($name); ?>" ></li>
            </div>
            
            <div class="form-group2">
             <label for="password3">Lösenord</label>
             <input type="password" id="password3" name="password3" placeholder="•••••••••" >
             </div>
             <?php
    // Display errors if there are any
    if (!empty($errors)) {
        echo '<ul>';
        foreach ($errors as $error) {
            echo '<li>' . htmlspecialchars($error) . '</li>';
        }
        echo '</ul>';
    }
    ?>
             
            
        </ol>
  </fieldset>


  
  <div class="form-actions">
    <input type="submit" name="Ändra" value="Ändra" class="button1">
    </div>
    </form>
    </div>
</body>
</html>