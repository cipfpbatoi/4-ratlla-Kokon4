<?php

namespace Joc4enRatlla\Models;


/**
 * Classe Board
 * Representa la tabla del 4 en ralla.
 */
class Board{
    /** 
     * @var files El numero de files de la tabla
     * @var columnes El numero de columnes de la tabla
     * @var directions Array que conte les 4 direccions
    */ 
    public const FILES = 6;
    public const COLUMNS = 7;
    public const DIRECTIONS = [
        [0, 1],   // Horizontal derecha
        [1, 0],   // Vertical abajo
        [1, 1],   // Diagonal abajo-derecha
        [1, -1]   // Diagonal abajo-izquierda
    ];

    /**
     * Array que representa les caselles
     *
     * @var slots son les caselles de la tabla.
     */
    private array $slots; 


    /**
     * Constructor de la classe Board
     * 
     * Llama a un metodo que la inicializa vacía
     */
    public function __construct(){
     $this->slots = self::initializeBoard();
    }

    /**
     * Inicializa la Graella vacía
     * @return array La graella vacía
     */
    private static function initializeBoard(): array {
        return array_fill(1, self::FILES, array_fill(1, self::COLUMNS, 0));
    }
        

    /**
     * Gestiona el moviment dels jugadors, els pinta en la tabla
     *
     * @param integer $column Numero de columna
     * @param integer $player El jugador que fa el moviment
     * @return array retorna la graella en la fitxa posada.
     */
    public function setMovementOnBoard(int $column, int $player): array {
        for ($row = self::FILES; $row >= 1; $row--) {
            if ($this->slots[$row][$column] == 0) {
                $this->slots[$row][$column] = $player;
                return [$row, $column];
            }
        }
        throw new \Exception("Column is full");
    }

    /**
     * Comprova si hi ha cuatre en ratlla.
     * @param array Es pasen les cordenades per verificar si hi ha 4 en ralla
     * @return bool Retorna true si hi ha 4 en ralla, returna false si no
     */
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


    /**
     * Verifica si les coordenades estan dins de la graella
     *
     * @param integer $row Files 
     * @param integer $col Columnes
     * @return boolean True si esta dins de la graella, false si no
     */
    private function isInBounds(int $row, int $col): bool {
        return $row >= 1 && $row <= self::FILES && $col >= 1 && $col <= self::COLUMNS;
    }

    /**
     * Verifica si el movimient es válid
     *
     * @param integer $column El numero de columna
     * @return boolean True si el movimiento es válido, false si no.
     */
    public function isValidMove(int $column): bool {
        return $this->slots[1][$column] == 0;
    }


    /**
     * Verifica si la graella esta plena
     *
     * @return boolean True si esta plena, false si no
     */
    public function isFull(): bool {
        for ($col = 1; $col <= self::COLUMNS; $col++) {
            if ($this->isValidMove($col)) return false;
        }
        return true;
    }


    /**
     * Obté l'array de la graella
     *
     * @return array Retorna el array de la graella
     */
    public function getSlots(): array {
        return $this->slots;
    }
}
?>