<?php
namespace Joc4enRatlla\Controllers;

use Joc4enRatlla\Models\Player;
use Joc4enRatlla\Models\Game;
use Joc4enRatlla\Services\Service;

class GameController
{
    private Game $game;

    public function __construct(array $request = [])
    {
        $this->game = Game::restore() ?? $this->createNewGame();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handlePost($request);
        }
    }
    
    
    private function createNewGame(): Game
    {
        $jugador1 = new Player("Player 1", "vermell");
        $jugador2 = new Player("Player 2", "verd");
        return new Game($jugador1, $jugador2);
    }
    
    private function handlePost(array $request): void
    {
        // Verifica que los datos requeridos estén en el request
        if (isset($request['nom']) && isset($request['color'])) {
            $nombreJugador1 = $request['nom'];
            $colorJugador1 = $request['color'];
            $modoAutomatico = isset($request['modo_automatico']);
            $nombreJugador2 = $modoAutomatico ? "Máquina" : ($request['nom2'] ?? ''); 

            // Crea los jugadores
            $jugador1 = new Player($nombreJugador1, $colorJugador1);
            $jugador2 = new Player($nombreJugador2, $colorJugador1 === 'vermell' ? 'verd' : 'vermell', $modoAutomatico);
            
            // Crea la instancia del juego
            $this->game = new Game($jugador1, $jugador2);
            
            // Guarda el estado del juego en la sesión
            $_SESSION['game'] = serialize($this->game);
            $_SESSION['scores'] = [1 => 0, 2 => 0]; 
        }
    }

    public function play(array $request)
    {
        // Si el jugador decide cerrar sesión
        if (isset($request['exit'])) {
            session_destroy();
            Service::loadView('jugador'); 
            return; 
        }
    
        // Reiniciar el juego
        if (isset($request['reset'])) {
            $this->game->reset();
        } elseif (isset($request['columna'])) {
            $this->game->play((int)$request['columna']);
        }
    
        // Guarda el estado del juego actualizado en la sesión
        $_SESSION['game'] = serialize($this->game);
    
        // Cargar la vista con el estado del juego actualizado
        $board = $this->game->getBoard();
        $players = $this->game->getPlayers();
        $winner = $this->game->getWinner();
        $scores = $this->game->getScores();
    
        Service::loadView('index', compact('board', 'players', 'winner', 'scores'));
    }
    
}    