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

    private array $slots; 

    public function __construct(){
     $this->slots = self::initializeBoard();
    }

    
    private static function initializeBoard(): array {
        return array_fill(1, self::FILES, array_fill(1, self::COLUMNS, 0));
    }
        
    public function setMovementOnBoard(int $column, int $player): array {
        for ($row = self::FILES; $row >= 1; $row--) {
            if ($this->slots[$row][$column] == 0) {
                $this->slots[$row][$column] = $player;
                return [$row, $column];
            }
        }
        throw new \Exception("Column is full");
    }

    public function checkWin(array $coord): bool {
        $player = $this->slots[$coord[0]][$coord[1]];
        foreach (self::DIRECTIONS as $direction) {
            $count = 1;
            for ($multiplier = 1; $multiplier <= 3; $multiplier++) {
                $newRow = $coord[0] + $direction[0] * $multiplier;
                $newCol = $coord[1] + $direction[1] * $multiplier;
                if ($this->isInBounds($newRow, $newCol) && $this->slots[$newRow][$newCol] == $player) {
                    $count++;
                } else {
                    break;
                }
            }
            for ($multiplier = 1; $multiplier <= 3; $multiplier++) {
                $newRow = $coord[0] - $direction[0] * $multiplier;
                $newCol = $coord[1] - $direction[1] * $multiplier;
                if ($this->isInBounds($newRow, $newCol) && $this->slots[$newRow][$newCol] == $player) {
                    $count++;
                } else {
                    break;
                }
            }
            if ($count >= 4) return true;
        }
        return false;
    }

    private function isInBounds(int $row, int $col): bool {
        return $row >= 1 && $row <= self::FILES && $col >= 1 && $col <= self::COLUMNS;
    }

    public function isValidMove(int $column): bool {
        return $this->slots[1][$column] == 0;
    }

    public function isFull(): bool {
        for ($col = 1; $col <= self::COLUMNS; $col++) {
            if ($this->isValidMove($col)) return false;
        }
        return true;
    }

    public function getSlots(): array {
        return $this->slots;
    }
    
}
?>