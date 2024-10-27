<div class="marcador">
    <h2>Marcador</h2>
    <p>Jugador 1: <?= $players[1]->getName() ?> - Puntuación: <?= $scores[1] ?></p>
    <p>Jugador 2: <?= $players[2]->getName() ?> - Puntuación: <?= $scores[2] ?></p>
</div>

<form action="Game.php" method="POST">
    <div class="tauler">
        <?php 
        $slots = $board->getSlots(); // Obtén el estado del tablero
        for ($row = 1; $row <= \Joc4enRatlla\Models\Board::FILES; $row++): ?>
            <div class="fila">
                <?php for ($col = 1; $col <= \Joc4enRatlla\Models\Board::COLUMNS; $col++): ?>
                    <div class="cel·la">
                        <?php if ($slots[$row][$col] === 1): ?>
                            <div class="fitxa vermell"></div>
                        <?php elseif ($slots[$row][$col] === 2): ?>
                            <div class="fitxa verd"></div>
                        <?php else: ?>
                            <div class="fitxa buida"></div>
                        <?php endif; ?>
                    </div>
                <?php endfor; ?>
            </div>
        <?php endfor; ?>
    </div>

    <div class="controls">
        <?php for ($col = 1; $col <= \Joc4enRatlla\Models\Board::COLUMNS; $col++): ?>
            <button type="submit" name="columna" value="<?= $col ?>"> <?= $col ?></button>
        <?php endfor; ?>
    </div>

    <div class="action-buttons">
        <button type="submit" name="action" value="reset">Reiniciar</button>
        <button type="submit" name="exit" value="1">Cerrar sesión</button> 
    </div>
</form>
