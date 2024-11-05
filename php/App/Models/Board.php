<?php

namespace Joc4enRatlla\Models;

/**
 * Class Board
 * 
 * Representa la tabla del juego "4 en raya". 
 * Gestiona la configuración inicial del tablero, las jugadas de los jugadores, 
 * y verifica condiciones de victoria.
 */
class Board
{
    /** 
     * @var int El número de filas de la tabla (constante).
     */
    public const FILES = 6;

    /** 
     * @var int El número de columnas de la tabla (constante).
     */
    public const COLUMNS = 7;

    /** 
     * @var array Array que contiene las 4 direcciones posibles para verificar el 4 en raya.
     */
    public const DIRECTIONS = [
        [0, 1],   // Horizontal derecha
        [1, 0],   // Vertical abajo
        [1, 1],   // Diagonal abajo-derecha
        [1, -1]   // Diagonal abajo-izquierda
    ];

    /**
     * @var array Representa las casillas del tablero.
     */
    private array $slots; 

    /**
     * Constructor de la clase Board.
     * 
     * Inicializa el tablero vacío llamando a un método estático que lo configura.
     */
    public function __construct()
    {
        $this->slots = self::initializeBoard();
    }

    /**
     * Inicializa la cuadrícula vacía.
     *
     * @return array La cuadrícula vacía, representada como un array bidimensional.
     */
    private static function initializeBoard(): array 
    {
        return array_fill(1, self::FILES, array_fill(1, self::COLUMNS, 0));
    }

    /**
     * Gestiona el movimiento de los jugadores, actualizando el tablero.
     *
     * @param int $column Número de la columna en la que se realiza el movimiento.
     * @param int $player El jugador que realiza el movimiento (1 o 2).
     * @return array Coordenadas (fila, columna) de la casilla donde se colocó la ficha.
     * @throws \Exception Si la columna está llena.
     */
    public function setMovementOnBoard(int $column, int $player): array 
    {
        for ($row = self::FILES; $row >= 1; $row--) {
            if ($this->slots[$row][$column] == 0) {
                $this->slots[$row][$column] = $player;
                return [$row, $column];
            }
        }
        throw new \Exception("Column is full");
    }

    /**
     * Verifica si hay 4 en raya en la dirección especificada.
     *
     * @param array $coord Coordenadas (fila, columna) de la última jugada.
     * @return bool Retorna true si hay 4 en raya, false si no.
     */
    public function checkWin(array $coord): bool 
    {
        $player = $this->slots[$coord[0]][$coord[1]];
        foreach (self::DIRECTIONS as $direction) {
            $count = 1;
            // Verificar en una dirección
            for ($multiplier = 1; $multiplier <= 3; $multiplier++) {
                $newRow = $coord[0] + $direction[0] * $multiplier;
                $newCol = $coord[1] + $direction[1] * $multiplier;
                if ($this->isInBounds($newRow, $newCol) && $this->slots[$newRow][$newCol] == $player) {
                    $count++;
                } else {
                    break;
                }
            }
            // Verificar en la dirección opuesta
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
     * Verifica si las coordenadas están dentro de los límites de la cuadrícula.
     *
     * @param int $row Fila a verificar.
     * @param int $col Columna a verificar.
     * @return bool True si está dentro de la cuadrícula, false si no.
     */
    private function isInBounds(int $row, int $col): bool 
    {
        return $row >= 1 && $row <= self::FILES && $col >= 1 && $col <= self::COLUMNS;
    }

    /**
     * Verifica si el movimiento en la columna especificada es válido.
     *
     * @param int $column Número de la columna a verificar.
     * @return bool True si el movimiento es válido, false si no.
     */
    public function isValidMove(int $column): bool 
    {
      
        for ($row = Board::FILES; $row >= 1; $row--) { 
            if ($this->slots[$row][$column] == 0) {
                return true; 
            }
        }
        return false; 
    }
    

    /**
     * Verifica si la cuadrícula está llena.
     *
     * @return bool True si está llena, false si no.
     */
    public function isFull(): bool 
    {
        for ($col = 1; $col <= self::COLUMNS; $col++) {
            if ($this->isValidMove($col)) return false;
        }
        return true;
    }

    /**
     * Obtiene el array que representa la cuadrícula.
     *
     * @return array Retorna el array que representa la cuadrícula.
     */
    public function getSlots(): array 
    {
        return $this->slots;
    }
}