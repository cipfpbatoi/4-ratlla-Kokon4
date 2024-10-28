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
    private $victories;

    public function __construct(Player $jugador1, Player $jugador2) {
        $this->board = new Board();
        $this->players = [1 => $jugador1, 2 => $jugador2]; 
        $this->nextPlayer = 1; 
        $this->winner = null;
        $this->victories = [1 => 0, 2 => 0];
    }

    public function getBoard(): Board {
        return $this->board;
    }
    
    public function getPlayers(): array {
        return $this->players;
    }

    public function getWinner(): ?Player {
        return $this->winner;
    }

    public function getScores(): array {
        return $this->scores;
    }

    public function reset(): void {
        $this->board = new Board(); 
        $this->nextPlayer = 1; 
        $this->winner = null; 
       
    }
    

    public function play(int $columna): void {
        if ($this->board->isValidMove($columna)) {
            $coord = $this->board->setMovementOnBoard($columna, $this->nextPlayer);
            if ($this->board->checkWin($coord)) {
                $this->winner = $this->players[$this->nextPlayer];
                $this->scores[$this->nextPlayer]++; 
            }
            $this->nextPlayer = $this->nextPlayer === 1 ? 2 : 1; 
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

    public function save(): void {
        $_SESSION['board'] = serialize($this->board); 
        $_SESSION['nextPlayer'] = $this->nextPlayer;
        $_SESSION['players'] = serialize($this->players);
        $_SESSION['winner'] = $this->winner ? serialize($this->winner) : null; 
        $_SESSION['scores'] = $this->scores;
    }

    public static function restore(): ?Game
    {
        if (isset($_SESSION['game'])) {
            return unserialize($_SESSION['game']);
        }
        return null;
    }
    
    
}


?>