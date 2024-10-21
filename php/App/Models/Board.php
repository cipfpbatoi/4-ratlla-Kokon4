<?php

namespace Joc4enRatlla\Models;

class Board
{
    public const FILES = 6;
    public const COLUMNS = 7;
    public const DIRECTIONS = [
        [0, 1],   // Horizontal derecha
        [1, 0],   // Vertical abajo
        [1, 1],   // Diagonal abajo-derecha
        [1, -1]   // Diagonal abajo-izquierda
    ];

    private array $slots;

    //public function __construct();

    //private static function initializeBoard(): array //Inicialitza la graella amb valors buits
    //public function setMovementOnBoard(int $column, int $player): array //Realitza un moviment en la graella
    //public function checkWin(array $coord): bool //Comprova si hi ha un guanyador
    //public function isValidMove(int $column): bool //Comprova si el moviment és vàlid


}

?>