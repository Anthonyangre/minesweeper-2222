document.addEventListener('DOMContentLoaded', () => {
    const gameBoard = document.getElementById('game-board');
    const resetButton = document.getElementById('reset-button');
    const statusDiv = document.getElementById('status');

    // sätter vad griden innehåller
    let currentGrid = JSON.parse(JSON.stringify(grid));
    let currentRevealed = JSON.parse(JSON.stringify(revealed));
    let currentFlags = JSON.parse(JSON.stringify(flags));
    let currentGameState = gameState;

    // updaterar stausen
    updateStatus();

    // rendererar griden
    renderGrid();

    // reset funktionen av spelet
    resetButton.addEventListener('click', () => {
        window.location.href = 'index.php?reset=true';
    });
    function renderGrid() {
    gameBoard.innerHTML = '';
    for (let row = 0; row < currentGrid.length; row++) {
        for (let col = 0; col < currentGrid[row].length; col++) {
            const cell = document.createElement('div');
            cell.classList.add('cell');

            if (currentRevealed[row][col]) {
                cell.classList.add('revealed');
                if (currentGrid[row][col] === 'M') {
                    cell.classList.add('mine');
                    cell.textContent = '💣';
                } else {
                    if (currentGrid[row][col] === 1) {
                        cell.classList.add('one'); // Apply the 'one' class for styling
                    }
                    if (currentGrid[row][col] === 2) {
                        cell.classList.add('two'); // Apply the 'one' class for styling
                    }
                    if (currentGrid[row][col] === 3) {
                        cell.classList.add('three'); // Apply the 'one' class for styling
                    }
                    if (currentGrid[row][col] === 4) {
                        cell.classList.add('four'); // Apply the 'one' class for styling
                    }
                    if (currentGrid[row][col] === 5) {
                        cell.classList.add('five'); // Apply the 'one' class for styling
                    }
                    if (currentGrid[row][col] === 6) {
                        cell.classList.add('six'); // Apply the 'one' class for styling
                    }
                    if (currentGrid[row][col] === 7) {
                        cell.classList.add('seven'); // Apply the 'one' class for styling
                    }
                    if (currentGrid[row][col] === 8) {
                        cell.classList.add('eight'); // Apply the 'one' class for styling
                    }
                    cell.textContent = currentGrid[row][col] > 0 ? currentGrid[row][col] : '';
                }
            }

            if (currentFlags[row][col]) {
                cell.classList.add('flag');
                cell.textContent = '🚩';
            }

            // gör så att man inte han ändra i spelet om spelet har slutat
            if (currentGameState === 'ongoing') {
                cell.addEventListener('click', () => revealCell(row, col));
                cell.addEventListener('contextmenu', (e) => {
                    e.preventDefault();
                    toggleFlag(row, col);
                });
            }

            gameBoard.appendChild(cell);
        }
    }
}
// funktionen för att visa cellen 
    function revealCell(row, col) {
        if (currentGameState !== 'ongoing') return;

        fetch('game.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `action=reveal&row=${row}&col=${col}`
        })
        .then(response => response.json())
        .then(data => {
            currentGrid = data.grid;
            currentRevealed = data.revealed;
            currentFlags = data.flags;
            currentGameState = data.game_state;
            updateStatus();
            renderGrid();
        });
    }
// funktionen för att visa en flagga om man höger klickar
    function toggleFlag(row, col) {
        if (currentGameState !== 'ongoing') return;
        
        fetch('game.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `action=flag&row=${row}&col=${col}`
        })
        .then(response => response.json())
        .then(data => {
            currentGrid = data.grid;
            currentRevealed = data.revealed;
            currentFlags = data.flags;
            currentGameState = data.game_state;
            updateStatus();
            renderGrid();
        });
    }
// om man har vunnit ska det visa att man har vunnit genom denna funktion.
    function updateStatus() {
        if (currentGameState === 'won') {
            statusDiv.textContent = 'Grattis! Du vann!';
            document.body.classList.add('game-over');
        } else if (currentGameState === 'lost') {
            statusDiv.textContent = 'Du träffade en mina! Spelet avslutades!';
            document.body.classList.add('game-over');
        } else {
            statusDiv.textContent = 'Spel pågår';
            document.body.classList.remove('game-over');
        }
    }
});



