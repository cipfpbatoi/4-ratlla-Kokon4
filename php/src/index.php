<?php

session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/../vendor/autoload.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/../Helpers/functions.php';
use Joc4enRatlla\Controllers\GameController;
use Joc4enRatlla\Models\User;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['name'], $_POST['password'])) {
    $nom_usuari = $_POST['name'];
    $password = $_POST['password'];

    // Buscamos al usuario por su nombre de usuario
    $user = User::getByNomUsuari($nom_usuari);

    if ($user) {
        // Obtenemos el hash almacenado en la base de datos
        $storedHash = $user->getContrasenya();

        // Comparamos la contraseña proporcionada en texto plano con el hash almacenado
        if (password_verify($password, $storedHash)) {
            // La contraseña es correcta
            $_SESSION['user_data'] = [
                'name' => $nom_usuari
                // No almacenes la contraseña en la sesión, solo el nombre de usuario
            ];
            loadView('jugador'); // Mostrar la pantalla de juego
        } else {
            // La contraseña es incorrecta
            echo "Contrasenya incorrecta.";
            loadView('validacio'); // Volver al formulario de validación
        }
    } else {
        // Si el usuario no existe, lo creamos
        $user = new User($nom_usuari, $password);
        if ($user->save()) {
            // Usuario creado correctamente
            $_SESSION['user_data'] = [
                'name' => $nom_usuari
            ];
            loadView('jugador'); // Mostrar la pantalla de juego
        } else {
            // No se pudo registrar al usuario
            echo "No s'ha pogut registrar l'usuari.";
            loadView('validacio'); // Volver al formulario de validación
        }
    }
    exit;
} else {
    // Si no se han enviado los datos, mostrar el formulario de validación
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

