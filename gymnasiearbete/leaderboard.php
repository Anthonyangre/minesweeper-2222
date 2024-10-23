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
  <a href="index.php"><div class="dropdown-item">Hem</div></a> 
  <a href="index.php#regler" class="dropdown-item">Regler</a>  <!-- Länk till regler-sektionen på index.php -->
  <a href="index.php#info" class="dropdown-item">Info</a> <!-- Länk till info-sektionen på index.php -->
</div>
        </div>

        <h3 class="rainbow-text">Välkommen till Minesweeper</h3> <!-- Välkomsttext med regnbågsfärg -->

<!-- Länkar för inloggning och registrering -->


        
    </header>