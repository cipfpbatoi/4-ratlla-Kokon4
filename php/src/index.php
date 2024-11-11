<?php

session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/../vendor/autoload.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/../Helpers/functions.php';
use Joc4enRatlla\Controllers\GameController;
use Joc4enRatlla\Models\User;

//Logica autenticacio Usuari.

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['name'], $_POST['password'])) {
    $nom_usuari = $_POST['name'];
    $password = $_POST['password'];
    $user = User::getByNomUsuari($nom_usuari);

    if ($user) {
        $storedHash = $user->getContrasenya();
        if (password_verify($password, $storedHash)) {
            $_SESSION['user_data'] = [
                'name' => $nom_usuari
            ];
            loadView('jugador');
        } else {
            echo "Contrasenya incorrecta.";
            loadView('validacio'); 
        }
    } else {
        $user = new User($nom_usuari, $password);
        if ($user->save()) {
            $_SESSION['user_data'] = [
                'name' => $nom_usuari
            ];
            loadView('jugador');
        } else {
            echo "No s'ha pogut registrar l'usuari.";
            loadView('validacio'); 
        }
    }
    exit;
} else {
    loadView('validacio');
    exit;
}


//Logica de joc
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

