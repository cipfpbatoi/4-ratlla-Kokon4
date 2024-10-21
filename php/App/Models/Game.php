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

    //public function __construct( Player $jugador1, Player $jugador2);

    //getters i setters

    //public function reset(): void //Reinicia el joc
    //public function play($columna)  //Realitza un moviment
    //public function playAutomatic(){copiarDelsApunts}
    //public function save()  //Guarda l'estat del joc a les sessions
    //public static function restore() //Restaura l'estat del joc de les sessions
}


?>