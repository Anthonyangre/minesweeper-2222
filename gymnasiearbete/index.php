<?php

require_once 'dbhs.php';
require_once 'assets/uppgifter.php';
?> 
<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="UTF-8"> <!-- Anger teckenkodning -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Gör sidan responsiv -->
    <title>Minesweeper login</title> <!-- Titel på sidan -->
    <link rel="stylesheet" href="style.css"> <!-- Länk till CSS-stilmallen -->
    <script type="text/javascript" src="java.js" defer></script> <!-- Länk till JavaScript-filen -->
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
            <a href="#regler" class="dropdown-item">Regler</a>
            <a href="#info" class="dropdown-item">Info</a>
            <a href= "leaderboard.php"class="dropdown-item">Leaderboard </a>
               

            </div>
        </div>

        <h3 class="rainbow-text">Välkommen till Minesweeper</h3> <!-- Välkomsttext med regnbågsfärg -->

<!-- Länkar för inloggning och registrering -->
<div class="header-links">

    <a href="login.php">Login</a><a href="signup.php">Signup</a>


   
    </div>

        
    </header>

    <div id="head2">  <h2> <?php echo htmlspecialchars(string: $username); ?></h2></div> 
    z
   

    <div class="wrapper"> 
    <div class="info_text"> 
        <h2 id="regler">Regler </h2> 
        <p>Regler Mål MS Röj går ut på att så fort som möjligt hitta alla 40 minor på spelplanen, markera dom med en flagga, och öppna alla rutor som inte innehåller en mina. Varje ruta på spelplanen kan antingen vara en mina, en siffra eller tom.

        Du förlorar så fort du öppnar en ruta som innehåller en mina. Placera istället en flagga på dessa rutor genom att högerklicka på rutan. Om en ruta innehåller en siffra så står siffran för hur många rutor som innehåller en mina och är bredvid rutan med siffran (horisontellt, vertikalt och diagonalt). Om en ruta är tom så betyder det att det inte finns någon mina bredvid den. Rutorna bredvid som inte innehåller en mina öppnas automatiskt när en tom ruta öppnas. Tillåtna drag Börja med att klicka på en ruta. Den första rutan innehåller aldrig en mina. När du öppnar den första tomma rutan så kommer andra tomma rutor bredvid förmodligen också att öppnas, så att du får en ungefärlig uppskattning om vart minorna befinner sig. Du kan sedan börja jobba dig runt minorna, med informationen som du fick från den första rutan. När du har identifierat en ruta som innehåller en mina så markerar du den genom att högerklicka på rutan. Om du inte är säker om en ruta innehåller en mina eller inte så kan du markera rutan med ett frågetecken genom att högerklicka två gånger på rutan. Om du råkar markera en ruta fel så kan du helt enkelt fortsätta att högerklicka på rutan för att växla markör. När du högerklickar på en ruta så kommer rutan att växla mellan tre utseenden: blank, flagga, frågetecken.

        Mål Spelet går ut på att öppna alla rutor som inte innehåller minor, vilket betyder att det inte är ett måste att markera alla minor, men det hjälper dig komma ihåg vart dom finns.
        </p>   
    </div>
    <div class="bild"> 
        <img src="./image/flagga.png" alt="Bild på minesweeper flagga">  
    </div>
</div>

<div class="wrapper"> 
    <div class="info_text"> 
        <h2 id="info">Såhär spelar du</h2> 
        <p>The Leek. According to legend, St David advised the Britons on the eve of a battle with the Saxons, to wear leeks in their caps so as to easily distinguish friend from foe. This helped to secure a great victory. Today Welsh people around the world wear leeks on St David's Day.</p>
    </div>
    <div class="bild"> 
        <img src="./image/flagga.png" alt="Bild på minesweeper flagga">  
    </div>
</div>


    
</body>
</html>
