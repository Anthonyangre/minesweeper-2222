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

  


            </div>
        </div>

        <h3 class="rainbow-text">Registrering</h3> <!-- Välkomsttext med regnbågsfärg -->

<!-- Länkar för inloggning och registrering -->
<div class="header-links">
    <a href="login.php">Login</a> 
   
    </div>

        
    </header>
    <div id="wrapper">
    
    

  <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" class="login-form">
    <fieldset>
        <legend class="form-title">Registrera Konto</legend>
        <div class="form-group">
            <label for="username">Användarnamn</label>
            <input type="text" id="username" name="username" placeholder="Ange ditt användarnamn" required>
        </div>
        <div class="form-group">
             <label for="email">email</label>
             <input type="mail" id="email" name="email">
</div>
<div class="form-group">
             <label for="name">name</label>
             <input type="text" id="name" name="name">
</div>
        <div class="form-group">
            <label for="password">Lösenord</label>
            <input type="password" id="password" name="password" placeholder="Ange ditt lösenord" required>
        </div>
        <div class="form-actions">
            <button type="submit" name="register" value="register" class="submit-button">Registrera</button>
        </div>
        <div class="extra-links">
            <a href="signup.php">Har du inget konto? Registrera dig här</a>
        </div>
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
    <br>
</body>
</html>