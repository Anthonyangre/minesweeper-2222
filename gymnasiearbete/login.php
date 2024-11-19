<?php
//infogar funktionalitet för inloggningen 
require_once 'dbhs.php';
?> 
<html lang="sv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minesweeper login</title>
    <link rel="stylesheet" href="style.css">
    <script type="text/javascript" src="java.js"></script>
   
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
  <a href="index.php" class="dropdown-item">Hem</a> 
  <a href="index.php#regler" class="dropdown-item">Regler</a>  <!-- Länk till regler-sektionen på index.php -->
  <a href="index.php#info" class="dropdown-item">Info</a> <!-- Länk till info-sektionen på index.php -->
  <a href= "./leaderboard.php"class="dropdown-item">Leaderboard </a>
  
  <!-- Leaderboard kanske inte finns på index.php, så ingen ankarlänk här -->
</div>
        </div>

        <h3 class="rainbow-text">Login</h3> <!-- Välkomsttext med regnbågsfärg -->

<!-- Länkar för inloggning och registrering -->
<div class="header-links">
  <a href="signup.php">Signup</a>
   
    </div>

        
    </header>
    
<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
    <h1>    Ange inloggnings uppgifter</h1>
  <fieldset>
         <div class=" inlogg_border">
            <li>
              <label for="username">Användarnamn</label>
              <input type="text"  id="username" name="username">
            </li>

            <li>
             <label for="password">Lösenord</label>
             <input type="password" id="password" name="password">
            </li>
            </div>
            <div class="box">
            <input type="submit" name="submit" value="submit" class="button">
            </div>
            <a href="signup.php">Inget konto? Signup.</a>
  </fieldset>
    

    </form>
    <?php
    //kontrollerar om det finns felmeddelanden
    if (count($errors) > 0) {
        
        echo '<ul>
            <li>'.implode('</li><li>', $errors).'</li>
        </ul>';
        }
    ?>
    </div>
    
</body>
</html>