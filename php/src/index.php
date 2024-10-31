<?php

session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/../vendor/autoload.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/../Helpers/functions.php';
use Joc4enRatlla\Controllers\GameController;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $gameController = new GameController($_POST);
    $gameController->play($_POST); 
} else {

    if (!isset($_SESSION['game'])) {
        loadView('jugador'); 
    } else {
    
        $gameController = new GameController();
        $gameController->play([]); 
    }
}

