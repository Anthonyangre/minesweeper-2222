document.addEventListener('DOMContentLoaded', () => {
    const gameBoard = document.getElementById('game-board');
    const statusDiv = document.getElementById('status');
    const turnDiv = document.getElementById('current-turn');

    function renderGrid() {
        gameBoard.innerHTML = '';
        for (let row = 0; row < currentGrid.length; row++) {
            for (let col = 0; col < currentGrid[row].length; col++) {
                const cell = document.createElement('div');
                cell.classList.add('cell');

                if (currentRevealed[row][col]) {
                    cell.classList.add('revealed');
                    cell.textContent = currentGrid[row][col] === 'M' ? '💣' : currentGrid[row][col] || '';
                }

                if (currentFlags[row][col]) {
                    cell.classList.add('flag');
                    cell.textContent = '🚩';
                }

                // Only allow clicks if it's the player's turn
                if (currentTurn === myUserId) {
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

    function updateTurnDisplay() {
        turnDiv.textContent = `Current Turn: Player ${currentTurn === myUserId ? 'You' : 'Opponent'}`;
    }
});
