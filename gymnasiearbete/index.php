<?php
require_once 'dbhs.php';
require_once 'assets/uppgifter.php';
?>
<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minesweeper</title>
    <link rel="stylesheet" href="style.css">
    <script type="text/javascript" src="java.js" defer></script>
</head>
<body>
        <!-- Header -->
        <header>
            <div class="menu-container" onclick="toggleMenu(this)">
                <div class="hamburger-menu">
                    <div class="bar1"></div>
                    <div class="bar2"></div>
                    <div class="bar3"></div>
                </div>
                <div class="menu-title">Navigation</div>
                <div class="dropdown-menu">
                    <a href="#regler" class="dropdown-item">Regler</a>
                    <a href="#info" class="dropdown-item">Info</a>
                </div>
            </div>
            <h3 class="rainbow-text" id="topp">Välkommen tillll Minesweeper</h3>
            <div class="header-links">
                <a href="login.php">Login</a>
                <a href="signup.php">Signup</a>
            </div>
        </header>

      
      
        <div class="container">
  <h4>Rolig Fakta</h4> <!-- Rubrik utanför textrutan -->
  <div class="row">
    <div class="textruta">
    1. Minesweeper utvecklades av Microsoft 1989 och inkluderades först i Windows 3.1 1992.
Minesweeper skapades av Curt Johnson, en programmerare på Microsoft, 1989. Det ingick först i Windows Entertainment Pack 1990 och blev populärt först med Windows 3.1 1992.  <br> <br> 

2. Minesweeper var ursprungligen designat som ett verktyg för att lära folk använda musen.
Spelet var inte tänkt att vara ett spel utan ett sätt att lära användare hur man använder en mus. Det var inspirerat av ett äldre spel, Cube, och erbjöd ett intuitivt sätt att lära sig navigera på datorn.<br> <br>

3. Minesweeper var inspirerat av ett mainframe-spel som kallades Cube.
Minesweeper togs fram efter inspiration från ett äldre spel, Cube, som användes för att samla skatter och undvika hinder. <br> <br>

4. Spelet var aldrig tänkt att vara beroendeframkallande, men det blev snabbt ett av de mest populära spelen på Windows.
Minesweeper blev så populärt eftersom det var gratis, enkelt att lära sig och fanns förinstallerat på miljontals datorer. Dess popularitet har hållit i sig i årtionden. <br> <br>

5. Spelet designades först för att likna ett minfält under andra världskriget.
Spelet visade svarta rutor som representerade minor och vita rutor som var säkra områden, en design som härstammar från andra världskriget. <br> <br> 

6. Den smiley som visas när du vinner spelet kallas "Victory Smile".
Victory Smile introducerades 1992 och blev en ikon för spelet. Det är en belöning för att framgångsrikt navigera genom minfältet.   <br> <br> 

7. Spelet var förbjudet i vissa länder, inklusive Kina, eftersom det ansågs vara ett symbol för Vietnamkriget.
Minesweeper förbjöds i vissa länder på grund av dess kopplingar till Vietnamkriget, men trots detta förblev spelet älskat över hela världen.  <br> <br> 

8. Världsrekordet för det snabbaste Minesweeper-spelet hålls av Kamil Muranski, som löste Expert-nivån på 31 sekunder.
Minesweeper är ett utmanande pusselspel och Muranskis  <class="minesweeper-page"></class>rekord på 31 sekunder är en fantastisk prestation och ett bevis på hans skicklighet i spelet. <br> <br>

Sammanfattningsvis har Minesweeper haft en fascinerande resa från att vara ett utbildningsverktyg till att bli ett av världens mest älskade och beroendeframkallande spel.

 </div>

    <div class="flag-wrapper">
    <img src="image/flagga.png" alt="Minesweeper flagga">
    </div>
  </div>
</div>

.

<div class="container">
  <h4>Spelregler</h4> <!-- Rubrik utanför textrutan -->
  <div class="row">
    <div class="textruta">
    Minesweeper är ett spel där miner är dolda i ett fält av rutor. Vid första klicket visas alltid tomma rutor och rutor med sifror i sig. De delar med siffror används till att lista ut vilka rutor som har minor och vilka som är säkra. Säkra rutor har siffror som visar hur många miner som finns runt omkring. Du använder siffrorna för att lista ut vilka rutor som är säkra och kan med hjälp av informationen öppna dem. Men om du klickar på en mina, förlorar du spelet och behöver starta om med ett nytt fält!

I Minesweeper gör första klicket alltid en säker ruta. Du öppnar rutor med vänster musknapp och sätter flaggor på miner med höger musknapp. Om du trycker på höger musknapp igen omvandlas flaggan till ett frågetecken. När du öppnar en ruta utan närhet till miner, blir den tom och angränsande rutor öppnas automatiskt tills du når rutor med siffror. En vanlig strategi för att starta är att slumpmässigt klicka tills du får en stor öppning med många siffror.

Om du flaggar alla miner som är kopplade till en siffra, kan du öppna de återstående rutorna genom att trycka båda musknapparna samtidigt (chording). Detta sparar mycket arbete, men om du flaggar fel rutor kan chording orsaka att minerna exploderar. <br> I vårat spel finns endast en svårighets nivå med en tio gånger tio ruta som spelplan. Dock kommer det finnas många monor så var försiktiga!


 </div>

    <div class="flag-wrapper">
    <img src="image/flagga.png" alt="Minesweeper flagga">
    </div>
  </div>
</div>



        <!-- Upp-knappen -->
        <a href="#topp" id="upp-knapp">
            <p> ⇧ </p>
        </a>

</body>
</html>
