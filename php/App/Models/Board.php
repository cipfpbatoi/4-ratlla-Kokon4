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

    public function __construct(){

    }

    //Inicialitza la graella amb valors buits
    private static function initializeBoard(){
        $graella = array();
        for ($i = 0; $i < 6; $i++) {
            $fila = array();
            for ($j = 0; $j < 7; $j++) {
                $fila[] = 0;  
            }
            $graella[] = $fila;
        }
        return $graella;
    } 
    
    //Realitza un moviment en la graella, retorna un array
    public function setMovementOnBoard(int $column, int $player){
        
    }
    
    //Comprova si hi ha un guanyador
    //public function checkWin(array $coord): bool 

    //Comprova si el moviment és vàlid
    //public function isValidMove(int $column): bool 

}

?>