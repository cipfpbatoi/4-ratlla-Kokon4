<?php

namespace Joc4enRatlla\Models;

use Joc4enRatlla\Models\Board;
use Joc4enRatlla\Models\Player;

class Game
{
    private Board $board;
    private int $nextPlayer;
    private array $players;
    private ?Player $winner;
    private array $scores = [1 => 0, 2 => 0];

    public function __construct( Player $jugador1, Player $jugador2){
        // TODO: S'han d'inicialitzar les variables tenint en compte que el array de jugador ha de començar amb l'index 1
        $this->board = new Board();
        $this->players = [$jugador1, $jugador2];
        $this->nextPlayer = 1;
    }

    // TODO: getters i setters

    public function reset(): void{
        
    }
    public function play($columna){
        // TODO: Realitza un moviment
    }


    /**
    * Realitza moviment automàtic
    * @return void
    */                                          
    public function playAutomatic(){
        $opponent = $this->nextPlayer === 1 ? 2 : 1;

        for ($col = 1; $col <= Board::COLUMNS; $col++) {
            if ($this->board->isValidMove($col)) {
                $tempBoard = clone($this->board);
                $coord = $tempBoard->setMovementOnBoard($col, $this->nextPlayer);

                if ($tempBoard->checkWin($coord)) {
                    $this->play($col);
                    return;
                }
            }
        }

        for ($col = 1; $col <= Board::COLUMNS; $col++) {
            if ($this->board->isValidMove($col)) {
                $tempBoard = clone($this->board);
                $coord = $tempBoard->setMovementOnBoard($col, $opponent);
                if ($tempBoard->checkWin($coord )) {
                    $this->play($col);
                    return;
                }
            }
        }

        $possibles = array();
        for ($col = 1; $col <= Board::COLUMNS; $col++) {
            if ($this->board->isValidMove($col)) {
                $possibles[] = $col;
            }
        }
        if (count($possibles)>2) {
            $random = rand(-1,1);
        }
        $middle = (int) (count($possibles) / 2)+$random;
        $inthemiddle = $possibles[$middle];
        $this->play($inthemiddle);
    }
    public function save(){
        $_SESSION['board'] = $this->board;
        $_SESSION['nextPlayer'] = $this->nextPlayer;
        $_SESSION['players'] = $this->players;
        $_SESSION['winner'] = $this->winner;
        $_SESSION['scores'] = $this->scores;
    }
    public static function restore(){
        $board = $_SESSION['board'];
        $nextPlayer = $_SESSION['nextPlayer'];
        $players = $_SESSION['players'];
        $winner = $_SESSION['winner'];
        $scores = $_SESSION['scores'];

        // TODO: Restaura l'estat del joc de les sessions
        // Retornar la partida que estaba abans 
        // pq cada volta que buide la graella es borrara tot
        // i aci guarde les variables de sessio de la partida
    }

}


?>