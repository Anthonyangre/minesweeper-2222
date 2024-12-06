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
            <h3 class="rainbow-text">Välkommen till Minesweeper</h3>
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
            <div class="cube-wrapper">
                <div class="tredim_textarea">
                    <div class="cube" id="cube1">
                        <div class="face front">
                            <h2>Framsidan</h2>
                            <p>Text på framsidan.</p>
                        </div>
                        <div class="face back">
                            <h2>Baksidan</h2>
                            <p>Text på baksidan.</p>
                        </div>
                        <div class="face left">
                            <h2>Vänster</h2>
                            <p>Text på vänster sida.</p>
                        </div>
                        <div class="face right">
                            <h2>Höger</h2>
                            <p>Text på höger sida.</p>
                        </div>
                        <div class="face top">
                            <h2>Toppen</h2>
                            <p>Text på toppen.</p>
                        </div>
                        <div class="face bottom">
                            <h2>Botten</h2>
                            <p>Text på botten.</p>
                        </div>
                    </div>
                    <button class="rotate-left" onclick="rotateCube('cube1', 'left')">◄</button>
                    <button class="rotate-right" onclick="rotateCube('cube1', 'right')">►</button>
                </div>
            </div>
            <div class="flag-wrapper">
                <img src="image/flagga.png" alt="Minesweeper flagga">
            </div>
        </div>

        <!-- Andra rad: Kub och flagga -->
        <div class="row">
            <div class="cube-wrapper">
                <div class="tredim_textarea">
                    <div class="cube" id="cube2">
                        <div class="face front">
                            <h2>Framsidan</h2>
                            <p>Text på framsidan.</p>
                        </div>
                        <div class="face back">
                            <h2>Baksidan</h2>
                            <p>Text på baksidan.</p>
                        </div>
                        <div class="face left">
                            <h2>Vänster</h2>
                            <p>Text på vänster sida.</p>
                        </div>
                        <div class="face right">
                            <h2>Höger</h2>
                            <p>Text på höger sida.</p>
                        </div>
                        <div class="face top">
                            <h2>Toppen</h2>
                            <p>Text på toppen.</p>
                        </div>
                        <div class="face bottom">
                            <h2>Botten</h2>
                            <p>Text på botten.</p>
                        </div>
                    </div>
                    <button class="rotate-left" onclick="rotateCube('cube2', 'left')">◄</button>
                    <button class="rotate-right" onclick="rotateCube('cube2', 'right')">►</button>
                </div>
            </div>
            <div class="flag-wrapper">
                <img src="image/flagga.png" alt="Minesweeper flagga">
            </div>
        </div>

        <!-- Upp-knappen -->
        <a href="#topp" id="upp-knapp">
            <img src="image/upp.png" alt="Upp-knapp">
        </a>
    </div>
</body>
</html>
