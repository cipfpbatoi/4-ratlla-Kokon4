<?php
namespace Joc4enRatlla\Controllers;

use Joc4enRatlla\Models\Player;
use Joc4enRatlla\Models\Game;
use Joc4enRatlla\Services\Service;

/**
 * Class GameController
 * 
 * Controlador encargado de gestionar el flujo del juego.
 * Se encarga de inicializar el juego, manejar las solicitudes del formulario 
 * y gestionar las interacciones entre los jugadores.
 */
class GameController
{
    /**
     * @var Game $game Instancia del objeto juego que maneja el controlador.
     */
    private Game $game;

    /**
     * GameController constructor.
     * 
     * Inicializa una nueva instancia de GameController y 
     * restaura el juego si existe en la sesión. 
     * También maneja las solicitudes POST para inicializar el juego.
     *
     * @param array $request Datos enviados desde el formulario.
     */
    public function __construct(array $request = [])
    {
        
        // Intenta restaurar el juego de la sesión o crea un nuevo juego.
        $this->game = Game::restore() ?? $this->createNewGame();
        
        // Maneja la solicitud POST si se recibe.
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handlePost($request);
        }
    }
    
    /**
     * Crea un nuevo juego cuando se solicita reiniciar la partida.
     *
     * @return Game Nueva instancia de Game.
     */
    private function createNewGame(): Game
    {
        $jugador1 = new Player("Player 1", "vermell");
        $jugador2 = new Player("Player 2", "verd");
        return new Game($jugador1, $jugador2);
    }
    
    /**
     * Maneja las solicitudes de datos del formulario inicial.
     *
     * Se encarga de asignar los valores de los jugadores y establecer 
     * el modo de juego (manual o automático).
     *
     * @param array $request Datos enviados desde el formulario.
     * @return void No retorna nada, solo asigna los valores a las propiedades.
     */
    private function handlePost(array $request): void
    {
        // Verifica que se hayan enviado los datos del jugador.
        if (isset($request['nom']) && isset($request['color'])) {
            $nombreJugador1 = $request['nom'];
            $colorJugador1 = $request['color'];
            $modoAutomatico = isset($request['modo_automatico']);
            $nombreJugador2 = $modoAutomatico ? "Máquina" : ($request['nomJugador2'] ?? ''); 
            $modoAutomatico = isset($request['mode']) && $request['mode'] === 'maquina'; 

            $jugador1 = new Player($nombreJugador1, $colorJugador1);
            if ($modoAutomatico) {
                $jugador2 = new Player("Maquina", $colorJugador1 === 'vermell' ? 'verd' : 'vermell', $modoAutomatico);
            } else {
                $jugador2 = new Player($nombreJugador2, $colorJugador1 === 'vermell' ? 'verd' : 'vermell', $modoAutomatico);
            }

            $this->game = new Game($jugador1, $jugador2);
            // Guarda el estado del juego y los puntajes en la sesión.
            $_SESSION['game'] = serialize($this->game);
            $_SESSION['scores'] = [1 => 0, 2 => 0]; 
        }
    }

    /**
     * Ejecuta el juego en función de las solicitudes del usuario.
     *
     * Maneja acciones como reiniciar la partida, cerrar sesión 
     * o realizar un movimiento en el juego.
     *
     * @param array $request Datos enviados por el usuario, por ejemplo, si desea reiniciar la partida o cerrar sesión.
     * @return void No retorna nada, pero carga la vista correspondiente.
     */
    public function play(array $request): void
    {
        // Maneja la solicitud para salir del juego.
        if (isset($request['exit'])) {
            session_destroy();
            Service::loadView('jugador'); 
            return; 
        }
    
        // Maneja la solicitud para reiniciar el juego.
        if (isset($request['reset'])) {
            $this->game->reset();
        } elseif (isset($request['columna'])) {
            // Procesa la jugada en la columna seleccionada.
            $this->game->play((int)$request['columna']);
            
            $winner = $this->game->getWinner();
            // Si no hay ganador, verifica si el siguiente jugador es automático.
            if (!$winner) {
                $nextPlayer = $this->game->getPlayers()[2]; 
                if ($nextPlayer->isAutomatic()) {
                    $this->game->playAutomatic(); 
                }
            }
        }
        
        // Comprueba si hay un ganador después de cada jugada.
        $winner = $this->game->getWinner();
    
        // Si hay un ganador, reinicia el juego y carga la vista.
        if ($winner) {
            $_SESSION['game'] = serialize($this->game);
            $board = $this->game->getBoard();
            $players = $this->game->getPlayers();
            $scores = $this->game->getScores();
    
            Service::loadView('index', compact('board', 'players', 'winner', 'scores'));
            $this->game->reset(); 
            return;
        }
    
        // Guarda el estado actual del juego en la sesión y carga la vista.
        $_SESSION['game'] = serialize($this->game);
        $board = $this->game->getBoard();
        $players = $this->game->getPlayers();
        $scores = $this->game->getScores();
    
        Service::loadView('index', compact('board', 'players', 'winner', 'scores'));
    }    
}
