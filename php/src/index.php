<?php

session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/../vendor/autoload.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/../Helpers/functions.php';
use Joc4enRatlla\Controllers\GameController;

// Si se ha enviado un formulario para iniciar sesión
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Crea el GameController con los datos enviados
    $gameController = new GameController($_POST);
    $gameController->play($_POST); // Llama a play después de manejar el post
} else {
    // Si no hay juego en progreso, carga la vista de inicio de sesión
    if (!isset($_SESSION['game'])) {
        loadView('jugador.view.php'); 
    } else {
        // Si hay un juego en progreso, intenta restaurarlo
        $gameController = new GameController();
        $gameController->play([]); // Llama a play sin datos para cargar el juego
    }
}

