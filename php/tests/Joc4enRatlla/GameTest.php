<?php
namespace Joc4enRatlla\Tests;

use Joc4enRatlla\Models\Board;
use Joc4enRatlla\Models\Game;
use Joc4enRatlla\Models\Player;
use Joc4enRatlla\Exceptions\IllegalMoveException;
use PHPUnit\Framework\TestCase;

class GameTest extends TestCase
{
    private Game $game;

    protected function setUp(): void
    {
        // Inicialització de dos jugadors
        $player1 = new Player("Jugador 1", 1);
        $player2 = new Player("Jugador 2", 2);
        $this->game = new Game($player1, $player2);
    }

    public function testInitialSetup()
    {
        // Comprova que la partida estigui inicialitzada correctament
        $this->assertNotNull($this->game->getBoard());
        $this->assertCount(2, $this->game->getPlayers());
        $this->assertNull($this->game->getWinner());
        $this->assertEquals([1 => 0, 2 => 0], $this->game->getScores());
       
    }

    public function testPlayValidMove()
    {
        // Prova un moviment vàlid
        $this->game->play(1); // Jugador 1 juga a la columna 1
        $this->assertNull($this->game->getWinner()); // No hi ha guanyador encara
    }

    public function testPlayInvalidMove()
    {
        for ($i = 1; $i <= Board::FILES; $i++) {
            $this->game->play(1);
        }
        $this->expectException(IllegalMoveException::class);
        $this->game->play(1); // Això hauria de llençar IllegalMoveException
    }

    public function testWinDetection()
    {
        // Simulació d'un guanyador
        $this->game->play(1); // Jugador 1
        $this->game->play(2); // Jugador 2
        $this->game->play(1); // Jugador 1
        $this->game->play(2); // Jugador 2
        $this->game->play(1); // Jugador 1
        $this->game->play(2); // Jugador 2
        $this->game->play(1); // Jugador 1 - guanya

        $this->assertEquals($this->game->getPlayers()[1], $this->game->getWinner()); // Jugador 1 ha guanyat
        $this->assertEquals(1, $this->game->getScores()[1]); // Jugador 1 ha guanyat 1 partida
    }

    public function testReset()
    {
        // Simula una partida i després la reinicia
        $this->game->play(1);
        $this->game->reset();

        $this->assertNull($this->game->getWinner());
        $this->assertEquals([1 => 0, 2 => 0], $this->game->getScores());
        $this->assertNotNull($this->game->getBoard()); // La graella s'ha reinicialitzat
    }

    public function testSaveAndRestore()
    {
        // Simula un joc i desa l'estat
        $this->game->play(1);
        $this->game->save();

        // Restaura el joc
        $restoredGame = Game::restore();

        
        $this->assertNotNull($restoredGame);
        $this->assertEquals($this->game->getBoard(), $restoredGame->getBoard());
        $this->assertEquals($this->game->getScores(), $restoredGame->getScores());
    }
}
