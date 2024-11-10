<?php

session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/../vendor/autoload.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/../Helpers/functions.php';
use Joc4enRatlla\Controllers\GameController;
use Joc4enRatlla\Models\User;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['name'], $_POST['password'])) {
    // Comprovem si l'usuari ja existeix
    $nom_usuari = $_POST['name'];
    $password = $_POST['password'];

    // Busquem l'usuari per nom
    $user = User::getByNomUsuari($nom_usuari);

    if ($user) {
        // Si l'usuari ja existeix, validem la contrasenya
        if (password_verify($password, $user->getContrasenya())) {
            // Guardem els dades de l'usuari a la sessió
            $_SESSION['user_data'] = [
                'name' => $nom_usuari,
                'password' => $password
            ];
            loadView('jugador'); // Mostrar la pantalla de joc
        } else {
            // Si la contrasenya no és correcta, mostrar un missatge d'error
            echo "Contrasenya incorrecta.";
            loadView('validacio'); // Tornar al formulari de validació
        }
    } else {
        // Si l'usuari no existeix, el creem
        $user = new User($nom_usuari, $password);
        if ($user->save()) {
            // Si l'usuari s'ha guardat correctament, es guarda a la sessió i es mostra la pantalla de joc
            $_SESSION['user_data'] = [
                'name' => $nom_usuari,
                'password' => $password
            ];
            loadView('jugador'); // Mostrar la pantalla de joc
        } else {
            // Si no s'ha pogut guardar l'usuari, mostrar un error
            echo "No s'ha pogut registrar l'usuari.";
            loadView('validacio'); // Tornar al formulari de validació
        }
    }

    exit;
} else {
    // Si no s'han enviat dades, mostrem el formulari de validació
    loadView('validacio');
    exit;
}
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

