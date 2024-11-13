<?php
session_start();
require_once '../dbhs.php';
if (!isset($_SESSION['grid'])) {
    resetGame();
}

function resetGame() {
    $rows = 10;
    $cols = 10;
    $mines = 10;

    $_SESSION['grid'] = generateGrid($rows, $cols, $mines);
    $_SESSION['revealed'] = array_fill(0, $rows, array_fill(0, $cols, false));
    $_SESSION['flags'] = array_fill(0, $rows, array_fill(0, $cols, false));
    $_SESSION['game_state'] = 'ongoing'; // 'ongoing', 'won', 'lost'
}

function generateGrid($rows, $cols, $mines) {
    $grid = array_fill(0, $rows, array_fill(0, $cols, 0));

    // Place mines
    $minesPlaced = 0;
    while ($minesPlaced < $mines) {
        $r = rand(0, $rows - 1);
        $c = rand(0, $cols - 1);
        if ($grid[$r][$c] == 0) {
            $grid[$r][$c] = 'M'; // Mine
            $minesPlaced++;

            // Update surrounding cells
            for ($i = max(0, $r - 1); $i <= min($r + 1, $rows - 1); $i++) {
                for ($j = max(0, $c - 1); $j <= min($c + 1, $cols - 1); $j++) {
                    if ($grid[$i][$j] != 'M') {
                        $grid[$i][$j]++;
                    }
                }
            }
        }
    }
    return $grid;
}

if (isset($_GET['reset'])) {
    resetGame();
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minesweeper</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="../style.css">
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
            <a href="../index.php" class="dropdown-item">Hem</a> 
  <a href="../index.php#regler" class="dropdown-item">Regler</a>  <!-- Länk till regler-sektionen på index.php -->
  <a href="../index.php#info" class="dropdown-item">Info</a> <!-- Länk till info-sektionen på index.php -->
  <a href= "../leaderboard.php"class="dropdown-item">Leaderboard </a>
               

            </div>
        </div>

        <h3 class="rainbow-text">Välkommen till Minesweeper</h3> <!-- Välkomsttext med regnbågsfärg -->
        <div class="konto">
    <!-- Toggle button -->
    <button class="toggle-button" onclick="togglekonto(this)">Konto</button>

    <div class="konto-menu-container">
        <!-- Title that slides in -->
        <div class="konto-title">Navigation</div>

        <!-- Dropdown menu container -->
        <div class="konto-dropdown">
            <a href="../profil.php" class="konto-item">Leaderboard</a>
            <!-- Add more .konto-item links if necessary -->
        </div>
    </div>
</div>
<script>
document.addEventListener("DOMContentLoaded", function() {
    function togglekonto(button) {
  // Access the sibling container
  const menuContainer = button.nextElementSibling;
  console.log("menuContainer:", menuContainer); // Log to check

  if (!menuContainer) {
    console.error("Menu container not found.");
    return;
  }

  const menuTitle = menuContainer.querySelector('.konto-title');
  const dropdownMenu = menuContainer.querySelector('.konto-dropdown');

  // Log each part to ensure they are being found
  console.log("menuTitle:", menuTitle); 
  console.log("dropdownMenu:", dropdownMenu);

  if (!menuTitle || !dropdownMenu) {
    console.error("Required elements (menuTitle or dropdownMenu) not found.");
    return;
  }

  const dropdownItems = dropdownMenu.querySelectorAll('.konto-item');
  console.log("dropdownItems:", dropdownItems); // Check dropdownItems

  // Check if any dropdown items are found
  if (!dropdownItems || dropdownItems.length === 0) {
    console.error("No dropdown items found in dropdownMenu.");
    return;
  }

  // Check if menu is open
  const isOpen = menuTitle.style.display === "block";

  if (!isOpen) {
    // Show and animate title and dropdown menu
    menuTitle.style.display = "block";
    dropdownMenu.style.display = "block";
    setTimeout(() => {
      menuTitle.style.transform = "translateX(0) scaleX(1)";
      dropdownMenu.style.transform = "scale(1)";
      dropdownMenu.style.opacity = 1;

      dropdownItems.forEach((item, index) => {
        setTimeout(() => {
          item.style.opacity = 1;
          item.style.transform = 'scale(1)';
        }, index * 100);
      });
    }, 50);
  } else {
    // Hide and reset title and dropdown menu
    menuTitle.style.transform = "translateX(-100%) scaleX(0)";
    dropdownMenu.style.transform = "scale(0)";
    dropdownMenu.style.opacity = 0;
    setTimeout(() => {
      menuTitle.style.display = "none";
      dropdownMenu.style.display = "none";
      dropdownItems.forEach(item => {
        item.style.opacity = 0;
        item.style.transform = 'scale(0)';
      });
    }, 500);
  }
}

});
</script>

        
    </header>

    <div id="status">Game in progress...</div>
    <div id="game-board"></div>
    <button id="reset-button">Reset</button>
    
    <script src="script.js"></script>
    <script src="../java.js"></script>
    <script>
        const grid = <?= json_encode($_SESSION['grid']) ?>;
        const revealed = <?= json_encode($_SESSION['revealed']) ?>;
        const flags = <?= json_encode($_SESSION['flags']) ?>;
        const gameState = '<?= $_SESSION['game_state'] ?>';
    </script>
</body>
</html>
