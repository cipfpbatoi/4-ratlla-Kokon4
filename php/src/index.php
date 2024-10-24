<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/../vendor/autoload.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/../Helpers/functions.php';
use Joc4enRatlla\Controllers\GameController;


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $gameController = new GameController($_POST); 
} else {
    loadView('jugador');
}

// La vista jugador.view.php te que mostrar el color i tal del jguador i enviarla aquiindex.php Tinc que fer un formulari que agafe el jugador i el color 
// i l'envie aci.
