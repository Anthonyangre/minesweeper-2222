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
    <div class="minesweeper-page">
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
            <h3 class="rainbow-text" id="topp">Välkommen till Minesweeper</h3>
            <div class="header-links">
                <a href="login.php">Login</a>
                <a href="signup.php">Signup</a>
            </div>
        </header>

        <!-- Header image -->
        <div id="head2">
            <img src="image/header_bild.png" alt="Header-bild">
        </div>

        <!-- Första rad: Kub och flagga -->
        <div class="row">
        <div class="textruta">

        År 1789, då den franska revolutionen inleddes, var Ludvig XVI kung i Frankrike. Ludvig var en liten, närsynt och fumlig man. Hans främsta intressen var mat och jakt.
        </div>
                
                    
        

            <div class="flag-wrapper">
                <img src="image/flagga.png" alt="Minesweeper flagga">
            </div>
        </div>
        </div>

        <!-- Andra rad: Kub och flagga -->
        <div class="row">
        <div class="textruta">

            Hello

        </div>


            <div class="flag-wrapper">
                <img src="image/flagga.png" alt="Minesweeper flagga">
            </div>
            </div>


        <!-- Upp-knappen -->
        <a href="#topp" id="upp-knapp">
            <img src="image/upp.png" alt="Upp-knapp">
        </a>

</body>
</html>
