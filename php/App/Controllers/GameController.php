<?php
namespace Joc4enRatlla\Controllers;

use Joc4enRatlla\Models\Player;
use Joc4enRatlla\Models\Game;


class GameController{
private Game $game;

// Request és l'array $_POST

public function __construct($request=null)
{   
    //Aqui tinc que recollir les dades del primer formulari per a fer el jugador1
    // TODO: Inicialització del joc.Ha d'inicializar el Game si és la primera vegada o agafar les dades de la sessió si ja ha estat inicialitzada

    $jugador1 = new Player($request['nom1'], $request['color1']);
    $jugador2 = new Player($request['nom2'], $request['color2']);
    $this->game = new Game($jugador1, $jugador2);
    // Crear jugador 1 i jugador 2 i crear un new game
    // Tambe he de guardar la partida en les variables de sesio
    // la guarde cridant a la funcio game->save;
    $this->play($request);
}

public function play(Array $request)  
{
    $board = $this->game->getBoard();
    $players = $this->game->getPlayers();
    $winner = $this->game->getWinner();
    $scores = $this->game->getScores();

    loadView('index',compact('board','players','winner','scores'));
 
}
}


?>