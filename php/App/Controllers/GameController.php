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
            $nombreJugador2 = $modoAutomatico ? "Máquina" : ($request['nomJugador2'] ?? ''); 
            $modoAutomatico = isset($request['mode']) && $request['mode'] === 'maquina'; 

            $jugador1 = new Player($nombreJugador1, $colorJugador1);
            if ($modoAutomatico) {
                $jugador2 = new Player("Maquina",$colorJugador1 === 'vermell' ? 'verd' : 'vermell', $modoAutomatico);
            } else {
                $jugador2 = new Player($nombreJugador2, $colorJugador1 === 'vermell' ? 'verd' : 'vermell', $modoAutomatico);
            }

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

    // Obtener información del juego
    $winner = $this->game->getWinner();
    
    if ($winner) {
        // Guardar el estado del juego con el ganador y las puntuaciones
        $_SESSION['game'] = serialize($this->game);
        
        // Mostrar el estado actual antes de reiniciar
        $board = $this->game->getBoard();
        $players = $this->game->getPlayers();
        $scores = $this->game->getScores();
        
        // Cargar la vista con el ganador
        Service::loadView('index', compact('board', 'players', 'winner', 'scores'));
        
        // Reiniciar el tablero después de mostrar al ganador
        $this->game->reset(); 
        return;
    }

    // Guardar el estado del juego actualizado en la sesión si no hay ganador
    $_SESSION['game'] = serialize($this->game);

    // Cargar la vista con el estado actual del juego
    $board = $this->game->getBoard();
    $players = $this->game->getPlayers();
    $scores = $this->game->getScores();

    Service::loadView('index', compact('board', 'players', 'winner', 'scores'));
}

    
}    