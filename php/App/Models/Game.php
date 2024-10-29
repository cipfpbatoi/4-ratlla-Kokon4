<?php

namespace Joc4enRatlla\Models;

use Joc4enRatlla\Exceptions\IllegalMoveException;
use Joc4enRatlla\Models\Board;
use Joc4enRatlla\Models\Player;
use PhpParser\Node\Stmt\Catch_;

/**
 * Classe Game
 *  
 * Representa un Joc amb la tabla, el seguent jugador els jugadors
 * Si hi ha o no guanyador i el marcador.
 */
class Game
{
    /**
     * @var Board La graella de la partida
     * @var nextPlayer El index del seguent jugador de la partida
     * @var players Llista de jugadors
     * @var winner Guanyador de la partida
     * @var scores Llista de puntuacio per reiniciar les puntuacions
     */
    private Board $board;
    private int $nextPlayer;
    private array $players;
    private ?Player $winner;
    private array $scores;


    /**
     * Constructor de la classe Game
     *
     * @param Player $jugador1 El jugador 1
     * @param Player $jugador2 El jugador 2
     */
    public function __construct(Player $jugador1, Player $jugador2) {
        $this->board = new Board();
        $this->players = [1 => $jugador1, 2 => $jugador2]; 
        $this->nextPlayer = 1; 
        $this->winner = null;
        $this->scores = [1 => 0, 2 => 0];
    }

    /**
     * Obte la graella
     * @return Board Retorna un objecte graella
     */
    public function getBoard(): Board {
        return $this->board;
    }
    
    /**
     * Obte la llista de jugadors
     *
     * @return array La llista de jugadors
     */
    public function getPlayers(): array {
        return $this->players;
    }

    /**
     * Obte el guanyador de la partida
     *
     * @return Player|null Retorna el Player guanyador o null per que encara no hi ha guanyador
     */
    public function getWinner(): ?Player {
        return $this->winner;
    }


    /**
     * Obte el marcador de les partides
     *
     * @return array retorna la puntuacio dels jugadors 
     */
    public function getScores(): array {
        return $this->scores;
    }
   

    /**
     * Resetea la partida
     * @return void
     */
    public function reset(): void {
        $this->board = new Board(); 
        $this->nextPlayer = 1; 
        $this->winner = null; 
       
    }
    


/**
 * Gestiona el flujo de la partida
 *
 * @param integer $columna El número de la columna 
 * @return void
 * @throws IllegalMoveException Si el movimiento no es válido
 */
public function play(int $columna): void {
    try {
        
        if ($this->board->isValidMove($columna)) {
            $coord = $this->board->setMovementOnBoard($columna, $this->nextPlayer);
            
            if ($this->board->checkWin($coord)) {
                $this->winner = $this->players[$this->nextPlayer];
                $this->scores[$this->nextPlayer]++;
            }
            $this->nextPlayer = $this->nextPlayer === 1 ? 2 : 1;
        } else {
            throw new IllegalMoveException('Movimiento no válido');
        }
    } catch (IllegalMoveException $e) {
        $_SESSION['error'] = "Movimiento no válido: " . $e->getMessage();
      
    }
}


    /**
    * Perform automatic move for the AI player
    */
    public function playAutomatic(): void {
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
                if ($tempBoard->checkWin($coord)) {
                    $this->play($col);
                    return;
                }
            } 

        $possibles = [];
        for ($col = 1; $col <= Board::COLUMNS; $col++) {
            if ($this->board->isValidMove($col)) {
                $possibles[] = $col;
            }
        }

        if (!empty($possibles)) {
            $randomIndex = array_rand($possibles);
            $this->play($possibles[$randomIndex]);
        }
    }
    }

    /**
     * Guarda los atributos de la partida en la sesion
     *
     * @return void
     */
    public function save(): void {
        $_SESSION['board'] = serialize($this->board); 
        $_SESSION['nextPlayer'] = $this->nextPlayer;
        $_SESSION['players'] = serialize($this->players);
        $_SESSION['winner'] = $this->winner ? serialize($this->winner) : null; 
        $_SESSION['scores'] = $this->scores;
    }


    /**
     * Restaura les dades de la partida anterior
     * @return Game|null Retorna les dades de la partida anterior si hi hagueren.
     */
    public static function restore(): ?Game
    {
        if (isset($_SESSION['game'])) {
            return unserialize($_SESSION['game']);
        }
        return null;
    }
}
?>