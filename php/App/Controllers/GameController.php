<?php

use Joc4enRatlla\Models\Player;
use Joc4enRatlla\Models\Game;


class GameController
{
private Game $game;

// Request és l'array $_POST

public function __construct($request=null)
{
    // TODO: Inicialització del joc.Ha d'inicializar el Game si és la primera vegada o agafar les dades de la sessió si ja ha estat inicialitzada
    // Crear jugador 1 i jugador 2 i crear un new game
    // Tambe he de guardar la partida en les variables de sesio
    // la guarde cridant a la funcio game->save;
    $this->play($request);

}

public function play(Array $request)  
{
    // TODO : Gestió del joc. Ací es on s'ha de vore si hi ha guanyador, si el que juga es automàtic  o manual, s'ha polsat reiniciar...



    $board = $this->game->getBoard();
    $players = $this->game->getPlayers();
    $winner = $this->game->getWinner();
    $scores = $this->game->getScores();

    loadView('index',compact('board','players','winner','scores'));
 }
}


?>