<?php
namespace Joc4enRatlla\Controllers;

use Joc4enRatlla\Models\Player;
use Joc4enRatlla\Models\Game;
use Joc4enRatlla\Services\Service;

/**
 * GameController
 * Classe encarregada de controlar el fluxe de joc.
 */
class GameController
{
    /**
     * @var Game El objecte joc que gastara el controller.
     */
    private Game $game;

    /**
     * Constructor de la clase GameController 
     * @param array $request Son les dades que se li serán asignades
     * per formulari
     */
    public function __construct(array $request = [])
    {
        $this->game = Game::restore() ?? $this->createNewGame();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handlePost($request);
        }
    }
    

    /**
     * Crea un nou Joc cuan es demane reiniciar la partida.
     *
     * @return Game El nou joc.
     */
    private function createNewGame(): Game
    {
        $jugador1 = new Player("Player 1", "vermell");
        $jugador2 = new Player("Player 2", "verd");
        return new Game($jugador1, $jugador2);
    }
    
    /**
     * Gestiona les dades del primer formulari.
     *
     * @param array $request Les dades del formulari
     * @return void No retorna res, ja que només asigna els valors.
     */
    private function handlePost(array $request): void
    {
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

            $this->game = new Game($jugador1, $jugador2);

            $_SESSION['game'] = serialize($this->game);
            $_SESSION['scores'] = [1 => 0, 2 => 0]; 
        }
    }

    /**
     * Ejecuta el joc.
     *
     * @param array $request Son les dades que envia el usuari, per ejemple si vol reiniciar la partida o tancar sessió.
     */
    public function play(array $request)
{
    if (isset($request['exit'])) {
        session_destroy();
        Service::loadView('jugador'); 
        return; 
    }

    if (isset($request['reset'])) {
        $this->game->reset();
    } elseif (isset($request['columna'])) {
        $this->game->play((int)$request['columna']);
    }
    
  
    $winner = $this->game->getWinner();
    
    if ($winner) {
        $_SESSION['game'] = serialize($this->game);
        $board = $this->game->getBoard();
        $players = $this->game->getPlayers();
        $scores = $this->game->getScores();
 
        Service::loadView('index', compact('board', 'players', 'winner', 'scores'));
        $this->game->reset(); 
        return;
    }

    $_SESSION['game'] = serialize($this->game);

    $board = $this->game->getBoard();
    $players = $this->game->getPlayers();
    $scores = $this->game->getScores();

    Service::loadView('index', compact('board', 'players', 'winner', 'scores'));
}  
}    