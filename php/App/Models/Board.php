<?php

namespace Joc4enRatlla\Models;

class Board{
    public const FILES = 6;
    public const COLUMNS = 7;
    public const DIRECTIONS = [
        [0, 1],   // Horizontal derecha
        [1, 0],   // Vertical abajo
        [1, 1],   // Diagonal abajo-derecha
        [1, -1]   // Diagonal abajo-izquierda
    ];

    private array $slots; // graella

    public function __construct(){
     $this->slots = self::initializeBoard();
    }

    
    private static function initializeBoard(): array{
        $board = [];
        for ($i = 0; $i < self::FILES; $i++) {
            for ($j = 0; $j < self::COLUMNS; $j++) {
                $board[$i][$j] = "";
            }
        }
        return $board;
    }
        
    public function setMovementOnBoard(int $column, int $player): array {
       for($i = self::FILES - 1; $i >= 0; $i--) {
           if($this->slots[$i][$column] == "") {
               $this->slots[$i][$column] = $player;
               break;
           }
       }
       return $this->slots;
    }

    public function checkWin(array $coords): bool {
        $player = $this->slots[$coords[0]][$coords[1]];
        foreach (self::DIRECTIONS as $direction) {
            if ($this->checkDirection($coords, $player, $direction)) {
                return true;
            }
        }
        return false;
    }

    private function checkDirection(array $coords, int $player, array $direction): bool {
        $x = $coords[0] + $direction[0];
        $y = $coords[1] + $direction[1];
        $count = 1;
        while (isset($this->slots[$x][$y]) && $this->slots[$x][$y] == $player) {
            $x += $direction[0];
            $y += $direction[1];
            $count++;
        }
        return $count >= 4;
    }
    public function isValidMove(int $column): bool {
        return $column >= 0 && $column < self::COLUMNS && $this->slots[0][$column] === null;
    }

    public function comprovarEmpat($graella) {
        foreach ($graella[0] as $celda) {
            if ($celda == 0) {
                return false;  
            }
        }
        return true;  
    }
    
    public function isFull(): bool {
        $isFull = false;
        foreach ($this->slots as $row) {
            if (in_array("", $row)) {
                $isFull = true;
    }
    return $isFull;
    }
    }

    public function getBoard(){
        return $this->slots;
    }

    
}
?>