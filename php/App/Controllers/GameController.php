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
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handlePost($request);
        } else {
            $this->game = Game::restore();
        }
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
            $_SESSION['scores'] = [1 => 0, 2 => 0]; // Reiniciar puntuaciones
        }
    }

    public function play(array $request)
    {
        
    
        if (isset($request['exit'])) {
            // Destruir la sesión
            session_destroy();
            // Redirigir a la vista de inicio de sesión
            Service::loadView('jugador.view.php'); 
            return; // Salir del método
        }
    
        // Manejo de otros movimientos de juego
        if (isset($request['reset'])) {
            $this->game->reset();
            $_SESSION['game'] = serialize($this->game);
        } elseif (isset($request['columna'])) {
            $this->game->play((int)$request['columna']);
            $_SESSION['game'] = serialize($this->game);
        }
    
        // Cargar la vista con el estado del juego
        $board = $this->game->getBoard();
        $players = $this->game->getPlayers();
        $winner = $this->game->getWinner();
        $scores = $this->game->getScores();
    
        Service::loadView('index', compact('board', 'players', 'winner', 'scores'));
    }
}    