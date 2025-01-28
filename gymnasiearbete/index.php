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

      
      
        
  <h4>Rolig Fakta</h4> <!-- Rubrik utanför textrutan -->
     <div class=" textruta_mitten">
    <div class="textruta">
    <ul>
    <li> Minesweeper utvecklades av Microsoft 1989 och inkluderades först i Windows 3.1 1992.
Minesweeper skapades av Curt Johnson, en programmerare på Microsoft, 1989. Det ingick först i Windows Entertainment Pack 1990 och blev populärt först med Windows 3.1 1992.  <br> <br> 
</li>
<li>
Minesweeper var ursprungligen designat som ett verktyg för att lära folk använda musen.
Spelet var inte tänkt att vara ett spel utan ett sätt att lära användare hur man använder en mus. Det var inspirerat av ett äldre spel, Cube, och erbjöd ett intuitivt sätt att lära sig navigera på datorn. I t mainframe-spelet Cube samlade man skatter och undvek hinder<br> <br>
</li>
<li>
 Spelet var aldrig tänkt att vara beroendeframkallande, men det blev snabbt ett av de mest populära spelen på Windows.
Minesweeper blev så populärt eftersom det var gratis, enkelt att lära sig och fanns förinstallerat på miljontals datorer. Dess popularitet har hållit i sig i årtionden. <br> <br>
</li>
<li>
 Spelet designades först för att likna ett minfält under andra världskriget.
Spelet visade svarta rutor som representerade minor och vita rutor som var säkra områden, en design som härstammar från andra världskriget. <br> <br> 
</li>
<li>
 Spelet var förbjudet i vissa länder, inklusive Kina, eftersom det ansågs vara ett symbol för Vietnamkriget.
Minesweeper förbjöds i vissa länder på grund av dess kopplingar till Vietnamkriget, men trots detta förblev spelet älskat över hela världen.  <br> <br> 
</li>
<li>
Världsrekordet för det snabbaste Minesweeper-spelet hålls av Kamil Muranski, som löste Expert-nivån på 31 sekunder.
Minesweeper är ett utmanande pusselspel och Muranskis  rekord på 31 sekunder är en fantastisk prestation och ett bevis på hans skicklighet i spelet. <br> <br>
</li>
</ul>
 </div>
 </div>

 

  <h4>Spelregler</h4> <!-- Rubrik utanför textrutan -->
  <div class=" textruta_mitten">
    <div class="textruta">
        <ul>

        
    <li>Vid första klicket visas alltid tomma rutor och rutor med sifror i sig.
    </li>
    <li>
     Rutor med siffror används till att lista ut vilka rutor som har minor och vilka som är säkra.
     siffrran inuti rutan visar hur många minor den nuddar, både horisontelt,vertikalt och diagonalt. exempelvis om det finns numer ett som endast nudar ett hörn måste det finnas en mina där. <br>
     </li>
     <li>
      Om du klickar på en mina, förlorar du spelet och behöver starta om med ett nytt fält! <br>
      </li>
      <li>
      Du öppnar rutor med vänster musknapp och sätter flaggor på miner med höger musknapp. När du öppnar en ruta med flera toma rutor runtom kommer de alla öpnnas automatsikt tills du når en ruta med en siffra i sig.
 En vanlig strategi för att starta är att slumpmässigt klicka tills du får en stor öppning med många siffror.
     </li>
     <li>
   I vårat spel finns endast en svårighets nivå med en tio gånger tio ruta som spelplan. Dock kommer det finnas många monor så var försiktiga!
 </li>

 </ul>
 </div>
 </div>

   


        <!-- Upp-knappen -->
        <a href="#topp" id="upp-knapp">
            <p> ⇧ </p>
        </a>

</body>
</html>
